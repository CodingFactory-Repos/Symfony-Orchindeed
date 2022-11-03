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
    public function isLogged(): bool
    {
        return $this->getUser() !== null;
    }

    #[Route('/', name: 'app_index')]
    public function index(EntityManagerInterface $doctrine): Response
    {
//        if(!$this->isLogged()) {
//            return $this->redirectToRoute('app_login');
//        }

        // Get the last registered user
        $user = $doctrine->getRepository(Users::class)->findOneBy([], ['id' => 'DESC']);
        $offers = $doctrine->getRepository(Offers::class)->findAll();

        $offersInSameZipcode = [];
        foreach ($offers as $offer) {
            $company = $doctrine->getRepository(Companies::class)->find($offer->getCompanyId());
            if (substr($company->getZipcode(), 0, 2) == substr($user->getZipcode(), 0, 2)) {
                $offersInSameZipcode[] = $offer;
            }
        }

        // Check if the user have more 3 skills in common with the offer
        foreach($offersInSameZipcode as $key => $offer) {
            $skills = $offer->getSkills();
            $skillsCount = 0;
            foreach($skills as $skill) {
                if($user->getSkills()->contains($skill)) {
                    $skillsCount++;
                }
            }
            if($skillsCount < 1) {
                unset($offersInSameZipcode[$key]);
            }
        }

        // Place on the top the offers with more skills in common
        usort($offersInSameZipcode, function($a, $b) use ($user) {
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

        // Return $user and $offersInSameZipcode (with the companies) to the view
        return $this->render('index/index.html.twig', [
            'user' => $user,
            'offers' => $offersInSameZipcode,
        ]);
    }
}
