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
        $offer = $doctrine->getRepository(Offers::class)->find($id);
        $company = $doctrine->getRepository(Companies::class)->find($offer->getCompanyId());
        $user = $doctrine->getRepository(Users::class)->findOneBy([], ['id' => 'DESC']);
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
}
