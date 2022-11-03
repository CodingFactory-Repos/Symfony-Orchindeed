<?php

namespace App\Controller;

use App\Entity\Companies;
use App\Entity\Users;
use App\Form\CompaniesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CompaniesController extends AbstractController
{
    #[Route('/companies/create', name: 'app_companies')]
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
}
