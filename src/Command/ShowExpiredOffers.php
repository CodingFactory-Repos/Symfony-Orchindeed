<?php
namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ShowExpiredOffers extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return Command::SUCCESS;
    }

    protected function configure()
    {
        $this->setName('app:show-expired-offers');
        $this->setHelp('Show expired offers');
        $this->setDescription('Show expired offers');
    }
}