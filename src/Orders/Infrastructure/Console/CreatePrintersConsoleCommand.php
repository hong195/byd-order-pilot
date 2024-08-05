<?php

namespace App\Orders\Infrastructure\Console;

use App\Orders\Application\UseCase\PrivateCommandInteractor;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-printers',
    description: 'Create Printers',
)]
final class CreatePrintersConsoleCommand extends Command
{
    public function __construct(
        private readonly PrivateCommandInteractor $privateCommandInteractor,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->privateCommandInteractor->createPrinters();

        return Command::SUCCESS;
    }
}
