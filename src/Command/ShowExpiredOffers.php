<?php
namespace App\Command;

use App\Entity\Offers;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ShowExpiredOffers extends Command
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
        $output->writeln("Expired offers :");
        $offers = $this->doctrine->createQuery('SELECT o FROM App\Entity\Offers o WHERE o.endDate < :now');
        $offers->setParameter('now', new \DateTime());
        $offers = $offers->getResult();

        foreach ($offers as $offer) {
                $output->writeln($offer->getName());
        }
        $output->writeln("End of expired offers");

        return Command::SUCCESS;
    }

    protected function configure()
    {
        $this->setName('app:show-expired-offers');
        $this->setHelp('Show expired offers');
        $this->setDescription('Show expired offers');
    }
}
