<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Create three users (we'll use them in the next section)
        $user1 = new User();
        $user1->setFirstname('firstuser');
        $user1->setLastname('firstuser');
        $user1->setAge(20);
        $user1->setAddress('Paris');
        $user1->setEmail('firstuser@gmail.com');
        $user1->setPassword('firstuserpassword');
        $user1->setDescription("Hello I'm first user and I'm a huge worker");
        $user1->setSkills(["Flutter", "PHP", "Symfony", "React", "Angular"]);
        $user1->setRoles(["ROLE_USER"]);
        $user1->setCreatedAt(new \DateTime());
        $user1->setUpdatedAt(new \DateTime());
        $manager->persist($user1);

        $user2 = new User();
        $user2->setFirstname('seconduser');
        $user2->setLastname('seconduser');
        $user2->setAge(20);
        $user2->setAddress('Bordeaux');
        $user2->setEmail('seconduser@gmail.com');
        $user2->setPassword('seconduserpassword');
        $user2->setDescription("Hello I'm second user and I'm a great thinker");
        $user2->setSkills(["JavaScript", "Ruby", "Ruby on Rails", "React", "Vue"]);
        $user2->setRoles(["ROLE_USER"]);
        $user2->setCreatedAt(new \DateTime());
        $user2->setUpdatedAt(new \DateTime());
        $manager->persist($user2);

        $user3 = new User();
        $user3->setFirstname('Orchidée');
        $user3->setLastname('Rose');
        $user3->setAge(24);
        $user3->setAddress(('Toulouse'));
        $user3->setEmail('orchidee@flower.com');
        $user3->setPassword('orchidee');
        $user3->setDescription("Hello I'm Orchidée and I'm a great killer");
        $user3->setSkills(["Java", "Ruby", "PHP", "React", "Flutter"]);
        $user3->setRoles(["ROLE_ADMIN"]);
        $user3->setCreatedAt(new \DateTime());
        $user3->setUpdatedAt(new \DateTime());
        $manager->persist($user3);

        $company1 = new Company();
        $company1->setName('Company 1');
        $company1->setDescription('This is the first company');
        $company1->setAddress('Paris');
        $company1->setUser($user1);
        $company1->setCreatedAt(new \DateTime());
        $company1->setUpdatedAt(new \DateTime());
        $manager->persist($company1);

        $company2 = new Company();
        $company2->setName('Company 2');
        $company2->setDescription('This is the second company');
        $company2->setAddress('Paris');
        $company2->setUser($user2);
        $company2->setCreatedAt(new \DateTime());
        $company2->setUpdatedAt(new \DateTime());
        $manager->persist($company2);


        //Create three job offers that are linked to company 1 and 2 (title description skills, company, createdAt, updatedAt, endsAt)
        $jobOffer1 = new JobOffer();
        $jobOffer1->setTitle('Job offer 1');
        $jobOffer1->setDescription('This is the first job offer');
        $jobOffer1->setSkills(["PHP", "Symfony", "React", "Angular"]);
        $jobOffer1->setCompany($company1);
        $jobOffer1->setCreatedAt(new \DateTime());
        $jobOffer1->setUpdatedAt(new \DateTime());
        $jobOffer1->setEndsAt(new \DateTime('+1 month'));
        $manager->persist($jobOffer1);

        $jobOffer2 = new JobOffer();
        $jobOffer2->setTitle('Job offer 2');
        $jobOffer2->setDescription('This is the second job offer');
        $jobOffer2->setSkills(["Ruby on Rails", "Ruby", "Angular"]);
        $jobOffer2->setCompany($company1);
        $jobOffer2->setCreatedAt(new \DateTime());
        $jobOffer2->setUpdatedAt(new \DateTime());
        $jobOffer2->setEndsAt(new \DateTime('+1 month'));
        $manager->persist($jobOffer2);

        $jobOffer3 = new JobOffer();
        $jobOffer3->setTitle('Job offer 3');
        $jobOffer3->setDescription('This is the third job offer');
        $jobOffer3->setSkills(["JavaFX"]);
        $jobOffer3->setCompany($company2);
        $jobOffer3->setCreatedAt(new \DateTime());
        $jobOffer3->setUpdatedAt(new \DateTime());
        $jobOffer3->setEndsAt(new \DateTime('+1 month'));
        $manager->persist($jobOffer3);

        // Create a job offer that has expired
        $jobOffer4 = new JobOffer();
        $jobOffer4->setTitle('Job offer 4');
        $jobOffer4->setDescription('This is the fourth job offer');
        $jobOffer4->setSkills(["Angular"]);
        $jobOffer4->setCompany($company2);
        $jobOffer4->setCreatedAt(new \DateTime());
        $jobOffer4->setUpdatedAt(new \DateTime());
        // Make the job offer that has expired
        $jobOffer4->setEndsAt(new \DateTime('-1 month'));
        $manager->persist($jobOffer4);

        $manager->flush();
    }
}
