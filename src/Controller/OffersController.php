<?php

namespace App\Controller;

use App\Entity\Companies;
use App\Entity\Offers;
use App\Entity\Skills;
use App\Form\OffersCreatorFormType;
use App\Form\OffersFormType;
use App\Repository\OffersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OffersController extends AbstractController
{
    #[Route('/offers', name: 'app_offers')]
    public function index(OffersRepository $offersRepository): Response
    {
        $offers = $offersRepository->findAll();

        return $this->render('offers/index.html.twig', [
            'offers' => $offers,
        ]);
    }

    #[Route('/offers/create', name: 'app_offers')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $offer = new Offers();
        // Set default values for Creation date and update date
        $offer->setCreationDate(new \DateTime());
        $offer->setUpdateDate(new \DateTime());
        $offer->setCompanyId($entityManager->getRepository(Companies::class)->find(7));
        $skills = $entityManager->getRepository(Skills::class)->findAll();
        $skillsArray = [];
        foreach ($skills as $skill) {
            $skillsArray[$skill->getName()] = $skill->getId();
        }
        $form = $this->createForm(OffersFormType::class, $offer, [
            'attr' => $skillsArray
        ]);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $skills = $form->get('skills')->getData();
            foreach ($skills as $skill) {
                $offer->addSkill($entityManager->getRepository(Skills::class)->find($skill));
            }
            $entityManager->persist($offer);
            $entityManager->flush();
            return $this->redirectToRoute('app_offers');
        }

        return $this->render('offers/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}