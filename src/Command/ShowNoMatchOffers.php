<?php
namespace App\Command;

use App\Entity\Companies;
use App\Entity\Offers;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ShowNoMatchOffers extends Command
{

    // Construct
    public function __construct(EntityManagerInterface $doctrine)
    {
        $this->doctrine = $doctrine;
        parent::__construct();
    }

    // Execute
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Not matched offers :");
        $offers = $this->doctrine->getRepository(Offers::class)->findAll();
        $users = $this->doctrine->getRepository(Users::class)->findAll();
        foreach ($offers as $offer) {
            $counter = 0;
            // With offer.getCompanyId() get the company  that posted the offer
            $company = $this->doctrine->getRepository(Companies::class)->find($offer->getCompanyId());
            foreach ($users as $user) {
                $userSkills = $user->getSkills();
                $offerSkills = $offer->getSkills();
                // If there is no match between user and offer, increment counter

                // For each user skills and offer skills if there is a match, increment counter
                foreach ($userSkills as $userSkill) {
                    foreach ($offerSkills as $offerSkill) {
                        if ($userSkill->getName() === $offerSkill->getName()) {
                            $counter++;
                        }
                    }
                }
            }
            if ($counter == count($users)) {
                $output->writeln($offer->getName());
            }
        }

        $output->writeln("End of no match offers");

        return Command::SUCCESS;
    }

    protected function configure()
    {
        $this->setName('app:show-no-match-offers');
        $this->setHelp('Show offers that does not match any profile');
        $this->setDescription('Show offers that does not match any profile');
    }
}
