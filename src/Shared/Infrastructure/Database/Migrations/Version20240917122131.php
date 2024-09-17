<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240917122131 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inventory_history ADD old_size NUMERIC(15, 2) NOT NULL');
        $this->addSql('ALTER TABLE inventory_history DROP remaining_size');
        $this->addSql('ALTER TABLE inventory_history RENAME COLUMN change_amount TO new_size');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE inventory_history ADD change_amount NUMERIC(15, 2) NOT NULL');
        $this->addSql('ALTER TABLE inventory_history ADD remaining_size NUMERIC(6, 2) NOT NULL');
        $this->addSql('ALTER TABLE inventory_history DROP new_size');
        $this->addSql('ALTER TABLE inventory_history DROP old_size');
    }
}
