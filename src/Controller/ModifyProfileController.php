<?php

namespace App\Controller;

use App\Entity\Skills;
use App\Entity\Users;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class ModifyProfileController extends AbstractController
{
    #[Route('/modify/profile', name: 'app_modify_profile')]
    public function index(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        if ($this->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }

        $user = $this->getUser()
            ->setFirstName($this->getUser()->getFirstName())
            ->setLastName($this->getUser()->getLastName())
            ->setEmail($this->getUser()->getEmail())
            ->setAge($this->getUser()->getAge())
            ->setZipCode($this->getUser()->getZipCode())
            ->setEmail($this->getUser()->getEmail())
//        $user-> setPassword($userPasswordHasher->hashPassword($user, $request->request->get('password')));
            ->setPassword($this->getUser()->getPassword())
            ->setDescription($this->getUser()->getDescription());
//        remove user skills
        foreach ($this->getUser()->getSkills() as $skill) {
            $user->removeSkill($skill);
        }
        $user->setCreationDate(new \DateTime())
            ->setUpdateDate(new \DateTime())
            ->setRoles(['ROLE_USER']);
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

            // hash the password (based on the security.yaml config for the $user class)
            $hashedPassword = $userPasswordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            );
            $user->setPassword($hashedPassword);

            $skills = $form->get('skills')->getData();
            foreach ($skills as $skill) {
                $user->addSkill($entityManager->getRepository(Skills::class)->find($skill));
            }

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_login');
        }

        $skilll = $this->getUser()->getSkills();
        $theUser = $user;
        $form->handleRequest($request);
        return $this->render('modify_profile/index.html.twig', [
            'registrationForm' => $form->createView(),
            'skilll' => $skilll,
            'theUser' => $theUser,
            'skillsArray' => $skillsArray,
        ]);
    }
}
