<?php

namespace App\Controller;

use App\Entity\Companies;
use App\Entity\Offers;
use App\Entity\Users;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(EntityManagerInterface $doctrine): Response
    {
        if ($this->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }

        // Get the logged user
        $user = $this->getUser();
        $offers = $doctrine->getRepository(Offers::class)->findAll();
        $companies = $doctrine->getRepository(Companies::class)->findAll();

        $isOwner = !(count($doctrine->getRepository(Companies::class)->findBy(['user_id' => $this->getUser()->getId()])) === 0);
        $offersToReturn = [];
        $myCompanies = [];
        $noLinkedOffers = [];

        if (!$isOwner) {
            foreach ($offers as $offer) {
                $company = $doctrine->getRepository(Companies::class)->find($offer->getCompanyId());
                if ($company->getCityCode() == $user->getCityCode()) {
                    $offersToReturn[] = $offer;
                }
            }

            // Check if the user have at least 1 skill in common with the offer
            foreach ($offersToReturn as $key => $offer) {
                $skills = $offer->getSkills();
                $skillsCount = 0;
                foreach ($skills as $skill) {
                    if ($user->getSkills()->contains($skill)) {
                        $skillsCount++;
                    }
                }
                if ($skillsCount < 1) {
                    unset($offersToReturn[$key]);
                }
            }



            // Place on the top the offers with more skills in common
            usort($offersToReturn, function ($a, $b) use ($user) {
                $skillsA = $a->getSkills();
                $skillsB = $b->getSkills();
                $skillsCountA = 0;
                $skillsCountB = 0;
                foreach ($skillsA as $skill) {
                    if ($user->getSkills()->contains($skill)) {
                        $skillsCountA++;
                    }
                }
                foreach ($skillsB as $skill) {
                    if ($user->getSkills()->contains($skill)) {
                        $skillsCountB++;
                    }
                }
                return $skillsCountB <=> $skillsCountA;
            });

            /* Then add to offersToReturn the offers that have not the same zipcode but that got at least 1 languages in common*/
            foreach ($offers as $offer) {
                $company = $doctrine->getRepository(Companies::class)->find($offer->getCompanyId());
                if ($company->getCityCode() !=$user->getCityCode()) {
                    $skills = $offer->getSkills();
                    $skillsCount = 0;
                    foreach ($skills as $skill) {
                        if ($user->getSkills()->contains($skill)) {
                            $skillsCount++;
                        }
                    }
                    if ($skillsCount >= 1) {
                        $noLinkedOffers[] = $offer;
                    }
                }

                /* Sort noLinkedOffers  then add it to offersToReturn */
                usort($noLinkedOffers, function ($a, $b) use ($user) {
                    $skillsA = $a->getSkills();
                    $skillsB = $b->getSkills();
                    $skillsCountA = 0;
                    $skillsCountB = 0;
                    foreach ($skillsA as $skill) {
                        if ($user->getSkills()->contains($skill)) {
                            $skillsCountA++;
                        }
                    }
                    foreach ($skillsB as $skill) {
                        if ($user->getSkills()->contains($skill)) {
                            $skillsCountB++;
                        }
                    }
                    return $skillsCountB <=> $skillsCountA;
                });
            }
            foreach ($noLinkedOffers as $newOffer) {
                $offersToReturn[] = $newOffer;
            }
        } else {
            $myCompanies = $doctrine->getRepository(Companies::class)->findBy(['user_id' => $this->getUser()->getId()]);
        }

        // Return $user and $offersToReturn (with the companies) to the view
        return $this->render('index/index.html.twig', [
            'user' => $user,
            'offers' => $offersToReturn,
            'companies' => $companies,
            'myCompanies' => $myCompanies,
            'isOwner' => $isOwner,
        ]);
    }
}
