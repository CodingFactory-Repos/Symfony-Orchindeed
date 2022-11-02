<?php

namespace App\Controller;

use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(): Response
    {

        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }

    #[Route('/secondPage', name: 'app_second_page')]
    public function createUser($en): Response
    {
        $test = new Users();
        $hashedPassword = $en->hasPassword($test);

        $test->setFirstName("Thomas");
        $test->setLastName("Lamiable");
        $test->setEmail("lamiablethomas@gmail.com");
        $test->setPassword($hashedPassword);
        $test->setAge(10);
        $test->setCreationDate(new \DateTime(2014-03-02));
        $test->setDescription("Jeune travailleur + male alpha");
        $test->setSkills(array(["PHP"], ["Symfony"], ["React"], ["Angular"]));
        $test->setUpdateDate(new \DateTime(2014-03-02));
        $test->setZipcode(95800);

        $en->persist($test);
        $en->flush();

        return new Response("Saved user".$test->getId());
    }
}
