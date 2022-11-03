<?php

namespace App\Controller;

use App\Entity\Companies;
use App\Entity\Offers;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShowOfferController extends AbstractController
{
    #[Route('/show/offer/{id}', name: 'app_show_offer')]
    public function index(EntityManagerInterface $doctrine, int $id): Response
    {
        if($this->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }

        $offer = $doctrine->getRepository(Offers::class)->find($id);
        $company = $doctrine->getRepository(Companies::class)->find($offer->getCompanyId());
        $user = $this->getUser();
        $skills = $offer->getSkills();
        $skillsCount = 0;

        foreach($skills as $skill) {
            if($user->getSkills()->contains($skill)) {
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
        ]);
    }

    #[Route('/show/company/{id}', name: 'app_show_company')]
    public function showAllCompanyOffers(EntityManagerInterface $doctrine, int $id): Response
    {
        if($this->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }

        $company = $doctrine->getRepository(Companies::class)->find($id);
        $offers = $doctrine->getRepository(Offers::class)->findBy(['company_id' => $id]);
        $user = $this->getUser();

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
        ]);
    }
}
