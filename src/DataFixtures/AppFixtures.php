<?php

namespace App\DataFixtures;

use App\Entity\Skills;
use App\Entity\Users;
use App\Entity\Offers;
use App\Entity\Companies;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
// Import user



class AppFixtures extends Fixture
{

    public function hashPassword($password)
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        return $hash;
    }
    public function load(ObjectManager $manager): void
    {

        $skills = ["PHP", "JS", "HTML", "CSS", "C", "C++", "C#", "Java", "Python", "Ruby", "Swift", "Go", "Rust", "Kotlin", "Scala", "Perl", "R", "SQL", "Bash", "PowerShell", "Ruby on Rails", "Laravel", "Symfony", "React", "Angular", "Vue", "Node", "Django", "Flask", "Spring", "Express", "Docker", "Kubernetes", "AWS", "Azure", "GCP", "Heroku", "Netlify", "Vercel", "Firebase", "MongoDB", "MySQL", "PostgreSQL", "Redis", "ElasticSearch", "Neo4j", "Cassandra", "RabbitMQ", "Kafka"];
        // Add skills to table skills
        foreach ($skills as $new_skill) {
            $skill = new Skills();
            $skill->setName($new_skill);
            $manager->persist($skill);
        }
        $manager->flush();

        // Create three users (we'll use them in the next section)
        $user1 = new Users();
        $user1->setFirstName('firstuser');
        $user1->setLastName('firstuser');
        $user1->setAge(20);
        $user1->setZipcode(75018);
        $user1->setEmail('firstuser@gmail.com');
        $user1->setPassword($this->hashPassword('firstuserpassword'));
        $user1->setDescription("Hello I'm first user and I'm a huge worker");
        $user1->setRoles(["ROLE_USER"]);
        $user1->setCreationDate(new \DateTime());
        $user1->setUpdateDate(new \DateTime());
        $manager->persist($user1);

        $user2 = new Users();
        $user2->setFirstname('seconduser');
        $user2->setLastname('seconduser');
        $user2->setAge(20);
        $user2->setZipcode(30072);
        $user2->setEmail('seconduser@gmail.com');
        $user2->setPassword($this->hashPassword('seconduserpassword'));
        $user2->setDescription("Hello I'm second user and I'm a great thinker");
        $user2->setRoles(["ROLE_USER"]);
        $user2->setCreationDate(new \DateTime());
        $user2->setUpdateDate(new \DateTime());
        $manager->persist($user2);

        $user3 = new Users();
        $user3->setFirstname('Orchidée');
        $user3->setLastname('Rose');
        $user3->setAge(24);
        $user3->setZipcode(31000);
        $user3->setEmail('orchidee@flower.com');
        $user3->setPassword($this->hashPassword('orchidee'));
        $user3->setDescription("Hello I'm Orchidée and I'm a great killer");
        $user3->setRoles(["ROLE_ADMIN"]);
        $user3->setCreationDate(new \DateTime());
        $user3->setUpdateDate(new \DateTime());
        $manager->persist($user3);

        // Add 5 skills to each user (randomly)
        $skills = $manager->getRepository(Skills::class)->findAll();
        // Add 5 random skills (from 0 to length of skills) to each user
        foreach ([$user1, $user2, $user3] as $user) {
            for ($i = 0; $i < rand(1,5); $i++) {
                $user->addSkill($skills[rand(0, count($skills) - 1)]);
            }
        }
        // Add to user 1 2 and 3 the same skills
        $user1->addSkill($skills[0]);
        $user1->addSkill($skills[0]);
        $user1->addSkill($skills[0]);

        $company1 = new Companies();
        $company1->setName('Company 1');
        $company1->setDescription('This is the first company');
        $company1->setZipcode(75000);
        $company1->setUserId($user1);
        $company1->setCreationDate(new \DateTime());
        $company1->setUpdateDate(new \DateTime());
        $manager->persist($company1);

        $company2 = new Companies();
        $company2->setName('Company 2');
        $company2->setDescription('This is the second company');
        $company2->setZipcode(75000);
        $company2->setUserId($user2);
        $company2->setCreationDate(new \DateTime());
        $company2->setUpdateDate(new \DateTime());
        $manager->persist($company2);


        //Create three job offers that are linked to company 1 and 2 (title description skills, company, createdAt, updatedAt, endsAt)
        $jobOffer1 = new Offers();
        $jobOffer1->setName('Job offer 1');
        $jobOffer1->setDescription('This is the first job offer');
        $jobOffer1->setCompanyId($company1);
        $jobOffer1->setCreationDate(new \DateTime());
        $jobOffer1->setUpdateDate(new \DateTime());
        $jobOffer1->setEndDate(new \DateTime('+1 month'));
        $manager->persist($jobOffer1);

        $jobOffer2 = new Offers();
        $jobOffer2->setName('Job offer 2');
        $jobOffer2->setDescription('This is the second job offer');
        $jobOffer2->setCompanyId($company1);
        $jobOffer2->setCreationDate(new \DateTime());
        $jobOffer2->setUpdateDate(new \DateTime());
        $jobOffer2->setEndDate(new \DateTime('+1 month'));
        $manager->persist($jobOffer2);

        $jobOffer3 = new Offers();
        $jobOffer3->setName('Job offer 3');
        $jobOffer3->setDescription('This is the third job offer');
        $jobOffer3->setCompanyId($company2);
        $jobOffer3->setCreationDate(new \DateTime());
        $jobOffer3->setUpdateDate(new \DateTime());
        $jobOffer3->setEndDate(new \DateTime('+1 month'));
        $manager->persist($jobOffer3);

        // Create a job offer that has expired
        $jobOffer4 = new Offers();
        $jobOffer4->setName('Job offer 4');
        $jobOffer4->setDescription('This is the fourth job offer');
        $jobOffer4->setCompanyId($company2);
        $jobOffer4->setCreationDate(new \DateTime());
        $jobOffer4->setUpdateDate(new \DateTime());
        $jobOffer4->setEndDate(new \DateTime('-1 month'));
        $manager->persist($jobOffer4);

        // Add to each job offer 5 skills (randomly)
        $skills = $manager->getRepository(Skills::class)->findAll();
        // Add 5 random skills (from 0 to length of skills) to each job offer
        foreach ([$jobOffer1, $jobOffer2, $jobOffer3, $jobOffer4] as $jobOffer) {
            for ($i = 0; $i < rand(1, 4); $i++) {
                $jobOffer->addSkill($skills[rand(0, count($skills) - 1)]);
            }
        }
        // Add to job offer 3 and 2 one skill that corresponds to the user's skills
        $jobOffer3->addSkill($skills[0]);
        $jobOffer2->addSkill($skills[0]);
        $manager->flush();
    }
}
