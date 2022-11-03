<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    public function isLogged(): bool
    {
        return $this->getUser() !== null;
    }

    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        if(!$this->isLogged()) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
}
