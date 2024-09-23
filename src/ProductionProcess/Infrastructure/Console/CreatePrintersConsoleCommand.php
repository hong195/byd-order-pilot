<?php

namespace App\ProductionProcess\Infrastructure\Console;

use App\ProductionProcess\Application\UseCase\PrivateCommandInteractor;
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
    /**
     * Constructor method for the class.
     *
     * @param PrivateCommandInteractor $privateCommandInteractor an instance of the PrivateCommandInteractor class
     */
    public function __construct(private readonly PrivateCommandInteractor $privateCommandInteractor)
    {
        parent::__construct();
    }

    /**
     * Execute method for the class.
     *
     * @param InputInterface  $input  an instance of the InputInterface class
     * @param OutputInterface $output an instance of the OutputInterface class
     *
     * @return int the success status code
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->privateCommandInteractor->createPrinters();

        return Command::SUCCESS;
    }
}
