<?php

namespace App\Shared\Infrastructure\Console;

use App\Users\Application\UseCase\AdminUseCaseInteractor;
use App\Users\Application\UseCase\Command\CreateUser\CreateUserCommand;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Creates a new user using the Symfony console command.
 *
 * @AsCommand(
 *     name="app:users:create-user",
 *     description="create user"
 * )
 */
#[AsCommand(
    name: 'app:system-reset',
    description: 'reset all migrations and data',
)]
final class ResetConsoleCommand extends Command
{
    /**
     * Class constructor.
     *
     * @param AdminUseCaseInteractor $adminCommandInteractor The admin use case interactor
     * @param EntityManagerInterface $entityManager          The entity manager interface
     */
    public function __construct(private readonly AdminUseCaseInteractor $adminCommandInteractor, private readonly EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Step 1: Drop all tables
        $io->section('Dropping all tables...');
        $dropProcess = new Process(['php', 'bin/console', 'doctrine:schema:drop', '--force']);
        $dropProcess->run();

        if (!$dropProcess->isSuccessful()) {
            $io->error('Error occurred while dropping tables.');
            throw new ProcessFailedException($dropProcess);
        }

        $io->success('All tables have been successfully dropped.');

        // Step 2: Drop doctrine_migration_versions table if it exists
        $io->section('Dropping doctrine_migration_versions table...');
        $connection = $this->entityManager->getConnection();
        $schemaManager = $connection->createSchemaManager();

        if ($schemaManager->tablesExist(['doctrine_migration_versions'])) {
            $connection->executeStatement('DROP TABLE doctrine_migration_versions');
            $io->success('doctrine_migration_versions table has been successfully dropped.');
        } else {
            $io->warning('doctrine_migration_versions table not found.');
        }

        // Step 3: Run migrations
        $io->section('Running all migrations...');
        $migrateProcess = new Process(['php', 'bin/console', 'doctrine:migrations:migrate', '--no-interaction']);
        $migrateProcess->run();

        if (!$migrateProcess->isSuccessful()) {
            $io->error('Error occurred while running migrations.');
            throw new ProcessFailedException($migrateProcess);
        }

        $io->success('Migrations have been successfully run.');

        $io->success('Creating admin user...');

        $this->adminCommandInteractor->createUser(new CreateUserCommand(
            name: 'Admin',
            email: 'admin@gmail.com',
            password: '123',
        ));

        $io->success('Admin user has been successfully created.');

        return Command::SUCCESS;
    }
}
