<?php
namespace App\Command;

use App\Entity\Offers;

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
        $output->writeln("Expired offers :");
        $offers = $this->doctrine->getRepository(Offers::class)->findAll();
        // Search offers by expiration date and if expired, display it
        foreach ($offers as $offer) {
            if ($offer->getEndDate() < new \DateTime()) {
                $output->writeln($offer->getName());
            }
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
