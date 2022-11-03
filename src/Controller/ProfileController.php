<?php

namespace App\Controller;

use App\Entity\Companies;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        if($this->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();

        $companies = $entityManager->getRepository(Companies::class)->findBy(['user_id' => $this->getUser()->getId()]);

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'companies' => $companies,

        ]);
    }
}
