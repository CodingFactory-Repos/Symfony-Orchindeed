<?php
namespace App\Command;

use App\Entity\Companies;
use App\Entity\Offers;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ShowNoMatchOrders extends Command
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
            // Get zipcode of the company (split only 2 first numbers)
            $companyZipcode = substr($company->getZipcode(), 0, 2);
            foreach ($users as $user) {
                $userZipcode = substr($user->getZipcode(), 0, 2);
                if (!array_intersect($offer->getSkills(), $user->getSkills()) && $companyZipcode != $userZipcode) {
                    $counter++;
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
