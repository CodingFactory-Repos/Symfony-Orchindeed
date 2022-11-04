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
        if($this->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }

        foreach ($this->getUser()->getSkills() as $skill) {
            $user = $this->getUser();
            $user->addSkill($skill->getName());
//            $lesSkills= $skill->getName();
//            $user->addSkill($lesSkills);

        }

        $user = $this->getUser();
        $user-> setFirstName($this->getUser()->getFirstName());
        $user-> setLastName($this->getUser()->getLastName());
        $user-> setEmail($this->getUser()->getEmail());
        $user-> setAge($this->getUser()->getAge());
        $user-> setZipCode($this->getUser()->getZipCode());
        $user-> setEmail($this->getUser()->getEmail());
//        $user-> setPassword($userPasswordHasher->hashPassword($user, $request->request->get('password')));
        $user-> setPassword($this->getUser()->getPassword());
        $user-> setDescription($this->getUser()->getDescription());
//        $user-> addSkill($this->getUser()->getSkills());
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
            'lesSkills' => $lesSkills,
        ]);
    }
}
