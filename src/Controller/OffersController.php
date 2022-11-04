<?php

namespace App\Controller;

use App\Entity\Companies;
use App\Entity\Offers;
use App\Entity\Skills;
use App\Form\OffersCreatorFormType;
use App\Form\OffersFormType;
use App\Repository\OffersRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OffersController extends AbstractController
{
    #[Route('/offer/create', name: 'app_offers')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }

        $offer = new Offers();
        // Set default values for Creation date and update date
        $offer->setCreationDate(new DateTime());
        $offer->setUpdateDate(new DateTime());
        $companies = $entityManager->getRepository(Companies::class)->findBy(['user_id' => $this->getUser()->getId()]);
        if (count($companies) === 0) {
            return $this->redirectToRoute('app_companies');
        }
        $companiesName = [];
        foreach ($companies as $company) {
            $companiesName[$company->getName()] = $company->getId();
        }
        $skills = $entityManager->getRepository(Skills::class)->findAll();
        $skillsArray = [];
        foreach ($skills as $skill) {
            $skillsArray[$skill->getName()] = $skill->getId();
        }
        $form = $this->createForm(OffersFormType::class, $offer, [
            'attr' => $skillsArray,
            // Send the list of companies to the form
            'empty_data' => $companiesName
        ]);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $skills = $form->get('skills')->getData();
            foreach ($skills as $skill) {
                $offer->addSkill($entityManager->getRepository(Skills::class)->find($skill));
            }
            $offer->setCompanyId($entityManager->getRepository(Companies::class)->find($form->get('company')->getData()));
            $entityManager->persist($offer);
            $entityManager->flush();
            return $this->redirectToRoute('app_index');
        }

        return $this->render('offers/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/offer/{id}', name: 'app_show_offer')]
    public function index(EntityManagerInterface $doctrine, int $id): Response
    {
        if ($this->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }

        $offer = $doctrine->getRepository(Offers::class)->find($id);
        $company = $doctrine->getRepository(Companies::class)->find($offer->getCompanyId());
        $user = $this->getUser();
        $skills = $offer->getSkills();
        $skillsCount = 0;

        $userParticipate = (bool)$offer->getUsers()->contains($user);

        foreach ($skills as $skill) {
            if ($user->getSkills()->contains($skill)) {
                $skillsCount++;
            }
        }

        $compatibilityPercentage = $skillsCount / count($skills) * 100;

        return $this->render('show_offer/index.html.twig', [
            'offer' => $offer,
            'user' => $user,
            'company' => $company,
            'skillsCount' => $skillsCount,
            'compatibilityPercentage' => $compatibilityPercentage,
            'userParticipate' => $userParticipate
        ]);
    }

    #[Route('/offer/participate/{id}', name: 'app_participate_offer')]
    public function participate(EntityManagerInterface $doctrine, int $id): Response
    {
        if ($this->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }

        // Check if the user is already participating to the offer
        $offer = $doctrine->getRepository(Offers::class)->find($id);
        $user = $this->getUser();

        if ($offer->getUsers()->contains($user)) {
            // If the user is already participating to the offer, remove him from the offer
            $offer->removeUser($user);
        } else {
            $offer->addUser($user);
        }

        $doctrine->persist($offer);
        $doctrine->flush();

        return $this->redirectToRoute('app_show_offer', ['id' => $id]);
    }
}
