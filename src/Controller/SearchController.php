<?php

namespace App\Controller;

use App\Entity\Companies;
use App\Entity\Offers;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/search/{search}', name: 'app_search')]
    public function index(EntityManagerInterface $doctrine, string $search = null): Response
    {
        if ($this->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }

        $user = $this->getUser();

        $offers = $doctrine->getRepository(Offers::class)->findAll();
        $offersInSearch = [];
        foreach ($offers as $offer) {
            if (str_contains($offer->getName(), $search)) {
                $offersInSearch[] = $offer;
            }
        }

        $companies = $doctrine->getRepository(Companies::class)->findAll();
        $companiesInSearch = [];
        foreach ($companies as $company) {
            if (str_contains($company->getName(), $search)) {
                $companiesInSearch[] = $company;
            }
        }

        $user = $this->getUser();
        // Place on the top the offers with more skills in common
        usort($offersInSearch, function ($a, $b) use ($user) {
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

        // Check if the user have a company
        $isOwner = !(count($doctrine->getRepository(Companies::class)->findBy(['user_id' => $this->getUser()->getId()])) === 0);

        return $this->render('search/index.html.twig', [
            'user' => $user,
            'companies' => $companiesInSearch,
            'offers' => $offersInSearch,
            'searched' => $search,
            'isOwner' => $isOwner,
        ]);
    }
}
