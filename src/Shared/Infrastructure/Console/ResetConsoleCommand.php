<?php

namespace App\Shared\Infrastructure\Console;

use App\Users\Application\UseCase\AdminUseCaseInteractor;
use App\Users\Application\UseCase\Command\CreateUser\CreateUserCommand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

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

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $connection = $this->entityManager->getConnection();
        $schemaManager = $connection->createSchemaManager();

        // Step 1: Drop all foreign key constraints
        $io->section('Dropping all foreign key constraints...');
        $tables = $schemaManager->listTableNames();
        foreach ($tables as $table) {
            $constraints = $connection->fetchAllAssociative("
                SELECT constraint_name
                FROM information_schema.table_constraints
                WHERE table_name = :table AND constraint_type = 'FOREIGN KEY'
            ", ['table' => $table]);

            foreach ($constraints as $constraint) {
                $connection->executeStatement(sprintf('ALTER TABLE "%s" DROP CONSTRAINT "%s"', $table, $constraint['constraint_name']));
            }
        }
        $io->success('All foreign key constraints have been removed.');

        // Step 2: Drop all tables with CASCADE to remove indexes and relations
        $io->section('Dropping all tables with CASCADE...');
        foreach ($tables as $table) {
            $connection->executeStatement("DROP TABLE IF EXISTS \"$table\" CASCADE");
        }
        $io->success('All tables have been successfully dropped with CASCADE.');

        // Step 3: Drop all sequences
        $io->section('Dropping all sequences...');
        $sequences = $connection->fetchAllAssociative("
            SELECT sequence_name
            FROM information_schema.sequences
            WHERE sequence_schema = 'public'
        ");

        foreach ($sequences as $sequence) {
            $connection->executeStatement(sprintf('DROP SEQUENCE IF EXISTS "%s" CASCADE', $sequence['sequence_name']));
        }
        $io->success('All sequences have been successfully dropped.');

        // Step 5: Run migrations
        $io->section('Running all migrations...');
        $migrateProcess = new Process(['php', 'bin/console', 'doctrine:migrations:migrate', '--no-interaction']);
        $migrateProcess->run();

        if (!$migrateProcess->isSuccessful()) {
            $io->error('Error occurred while running migrations.');
            throw new ProcessFailedException($migrateProcess);
        }

        $io->success('Migrations have been successfully run.');

        // Step 6: Create admin user
        $io->success('Creating admin user...');
        $this->adminCommandInteractor->createUser(new CreateUserCommand(
            name: 'Admin',
            email: 'admin@gmail.com',
            password: '123',
        ));
        $io->success('Admin user has been successfully created.');

        $io->success('Creating printers....');
        $migrateProcess = new Process(['php', 'bin/console', 'app:create-printers', '--no-interaction']);
        $migrateProcess->run();
        $io->success('Printers has been successfully created.');

        return Command::SUCCESS;
    }
}
