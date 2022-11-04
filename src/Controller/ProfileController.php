<?php

namespace App\Controller;

use App\Entity\Companies;
use App\Entity\Offers;
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

        $offers = $entityManager->getRepository(Offers::class)->findAll();

        $userCompanies = $entityManager->getRepository(Companies::class)->findBy(['user_id' => $this->getUser()->getId()]);

        /* Check for all offers who participates and if user is in it, stock it in an array */
        $userOffers = [];
        foreach ($offers as $offer) {
            $participants = $offer->getUsers();
            foreach ($participants as $participant) {
                if ($participant->getId() === $user->getId()) {
                    $userOffers[] = $offer;
                }
            }
        }





        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'companies' => $userCompanies,
            'offers' => $userOffers,

        ]);
    }
}
