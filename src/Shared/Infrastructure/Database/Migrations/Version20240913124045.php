<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240913124045 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders_roll ADD parent_roll_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders_roll ADD CONSTRAINT FK_8060F365D9431960 FOREIGN KEY (parent_roll_id) REFERENCES orders_roll (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8060F365D9431960 ON orders_roll (parent_roll_id)');
        $this->addSql('ALTER TABLE orders_roll_history ADD parent_roll_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE orders_roll DROP CONSTRAINT FK_8060F365D9431960');
        $this->addSql('DROP INDEX UNIQ_8060F365D9431960');
        $this->addSql('ALTER TABLE orders_roll DROP parent_roll_id');
        $this->addSql('ALTER TABLE orders_roll_history DROP parent_roll_id');
    }
}
