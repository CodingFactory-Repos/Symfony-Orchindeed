<?php

namespace App\Controller;

use App\Repository\OffersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}