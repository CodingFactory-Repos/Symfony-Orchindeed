<?php
// Create a unit test for Sort of offers

namespace App\Tests\Controllers;

use App\Entity\Companies;
use App\Entity\Offers;
use App\Entity\Skills;
use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;



class SortTest extends WebTestCase
{
    public function testSortOffers()
    {
        /* Create a user, three orders and a company */
        $user = new Users();
        $user->setEmail('test@test.fr');
        $user->setPassword('test');
        $user->setFirstname('test');
        $user->setLastname('test');
        $user->setZipcode('75000');
        $user->setRoles(['ROLE_USER']);
        $user->setCreationDate(new \DateTime());
        $user->setUpdateDate(new \DateTime());
        $user->setAge(20);
        $user->setDescription('test');

        $user2 = new Users();
        $user2->setEmail('creator@example.com');
        $user2->setPassword('creator');
        $user2->setFirstname('creator');
        $user2->setLastname('creator');
        $user2->setZipcode('75000');
        $user2->setRoles(['ROLE_USER']);
        $user2->setCreationDate(new \DateTime());
        $user2->setUpdateDate(new \DateTime());
        $user2->setAge(20);
        $user2->setDescription('creator');

        /* Set 4 skills to this user PHP Ruby Symfony and JavaScript*/
        $skill1 = new Skills();
        $skill1->setName('PHP');
        $user->addSkill($skill1);
        $skill2 = new Skills();
        $skill2->setName('Ruby');
        $user->addSkill($skill2);
        $skill3 = new Skills();
        $skill3->setName('Symfony');
        $user->addSkill($skill3);
        $skill4 = new Skills();
        $skill4->setName('JavaScript');

        /* Create two companies */
        $company = new Companies();
        $company->setName('test');
        $company->setDescription('test');
        $company->setZipcode('75000');
        $company->setUserId($user2);
        $company->setCreationDate(new \DateTime());
        $company->setUpdateDate(new \DateTime());

        $company2 = new Companies();
        $company2->setName('test2');
        $company2->setDescription('test2');
        $company2->setZipcode('95800');
        $company2->setUserId($user2);
        $company2->setCreationDate(new \DateTime());
        $company2->setUpdateDate(new \DateTime());

        /* Create three offers linked to this company */

        $offer1 = new Offers();
        $offer1->setTitle('testPartialMatch');
        $offer1->setDescription('testPartialMatch');
        $offer1->setCompanyId($company);
        $offer1->setCreationDate(new \DateTime());
        $offer1->setUpdateDate(new \DateTime());
        $offer1->addSkill($skill1);
        $offer1->addSkill($skill2);
        $offer1->addSkill($skill4);

        $offer2 = new Offers();
        $offer2->setTitle('testFullMatch');
        $offer2->setDescription('testFullMatch');
        $offer2->setCompanyId($company);
        $offer2->setCreationDate(new \DateTime());
        $offer2->setUpdateDate(new \DateTime());
        $offer2->addSkill($skill1);
        $offer2->addSkill($skill2);
        $offer2->addSkill($skill3);

        $offer3 = new Offers();
        $offer3->setTitle('testNoMatch');
        $offer3->setDescription('testNoMatch');
        $offer3->setCompanyId($company);
        $offer3->setCreationDate(new \DateTime());
        $offer3->setUpdateDate(new \DateTime());
        $offer3->addSkill($skill4);

        $offer4 = new Offers();
        $offer4->setTitle('testOtherZipcode');
        $offer4->setDescription('testOtherZipcode');
        $offer4->setCompanyId($company2);
        $offer4->setCreationDate(new \DateTime());
        $offer4->setUpdateDate(new \DateTime());
        $offer4->addSkill($skill1);
        $offer4->addSkill($skill2);
        $offer4->addSkill($skill3);


        $offers = [$offer1, $offer2, $offer3, $offer4];

        foreach ($offers as $offer) {
            $company = $offer->getCompanyId();
            if (substr($company->getZipcode(), 0, 2) == substr($user->getZipcode(), 0, 2)) {
                $offersInSameZipcode[] = $offer;
            }
        }

        foreach ($offersInSameZipcode as $key => $offer) {
            $skills = $offer->getSkills();
            $skillsCount = 0;
            foreach ($skills as $skill) {
                if ($user->getSkills()->contains($skill)) {
                    $skillsCount++;
                }
            }
            if ($skillsCount < 1) {
                unset($offersInSameZipcode[$key]);
            }
        }

        usort($offersInSameZipcode, function ($a, $b) use ($user) {
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

        $this->assertEquals($offersInSameZipcode[0], $offer2);
        $this->assertEquals($offersInSameZipcode[1], $offer1);
        $this->assertNotContains($offer3, $offersInSameZipcode);
        $this->assertNotContains($offer4, $offersInSameZipcode);















    }

}
