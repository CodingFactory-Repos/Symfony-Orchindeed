<?php

namespace App\Controller;

use App\Entity\Skills;
use App\Entity\Users;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new Users();
        $user->setCreationDate(new \DateTime());
        $user->setUpdateDate(new \DateTime());
        $user->setRoles(['ROLE_USER']);
        $skills = $entityManager->getRepository(Skills::class)->findAll();
        $skillsArray = [];
        foreach ($skills as $skill) {
            $skillsArray[$skill->getName()] = $skill->getId();
        }

        $form = $this->createForm(RegistrationFormType::class, $user, [
            'attr' => $skillsArray
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $form->get('plainPassword')->getData()
            );
            $skills = $form->get('skills')->getData();
            foreach ($skills as $skill) {
                $user->addSkill($entityManager->getRepository(Skills::class)->find($skill));
            }
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
