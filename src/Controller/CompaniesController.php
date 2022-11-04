<?php

namespace App\Controller;

use App\Entity\Companies;
use App\Entity\Offers;
use App\Entity\Users;
use App\Form\CompaniesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CompaniesController extends AbstractController
{
    #[Route('/company/create', name: 'app_companies')]
    public function index(Request $request,EntityManagerInterface $entityManager): Response
    {//        get user name
        if($this->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }

        $user = $this->getUser();
        $lastCompanies = $entityManager->getRepository(Companies::class)->findBy([], ['id' => 'DESC'], 5);
        $allCompanies = $entityManager->getRepository(Companies::class)->findAll();

        $companies = new Companies();
        $companies->setUserId($entityManager->getRepository(Users::class)->find($user->getId()));
        $companies->setCreationDate(new \DateTime());
        $companies->setUpdateDate(new \DateTime());

        $form = $this->createForm(CompaniesType::class, $companies);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $companies = $form->getData();
            $entityManager->persist($companies);
            $entityManager->flush();

            return $this->redirectToRoute('app_index');
        }

        return $this->render('companies/index.html.twig', [
            'form' => $form->createView(),
            'lastCompanies' => $lastCompanies,
            'companies' => $allCompanies,
            'user' => $user
        ]);
    }

    #[Route('/company/{id}', name: 'app_show_company')]
    public function showAllCompanyOffers(EntityManagerInterface $doctrine, int $id): Response
    {
        if($this->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }

        $company = $doctrine->getRepository(Companies::class)->find($id);
        $offers = $doctrine->getRepository(Offers::class)->findBy(['company_id' => $id]);
        $user = $this->getUser();

        $isOwner = !(count($doctrine->getRepository(Companies::class)->findBy(['user_id' => $this->getUser()->getId()])) === 0);

        // Place on the top the offers with more skills in common
        usort($offers, function($a, $b) use ($user) {
            $skillsA = $a->getSkills();
            $skillsB = $b->getSkills();
            $skillsCountA = 0;
            $skillsCountB = 0;
            foreach($skillsA as $skill) {
                if($user->getSkills()->contains($skill)) {
                    $skillsCountA++;
                }
            }
            foreach($skillsB as $skill) {
                if($user->getSkills()->contains($skill)) {
                    $skillsCountB++;
                }
            }
            return $skillsCountB <=> $skillsCountA;
        });

        return $this->render('show_offer/show_all_company_offers.html.twig', [
            'company' => $company,
            'offers' => $offers,
            'user' => $user,
            'isOwner' => $isOwner
        ]);
    }
}
