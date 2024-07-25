<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240725101226 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_files ALTER type DROP NOT NULL');
        $this->addSql('ALTER TABLE media_files ALTER owner_id DROP NOT NULL');
        $this->addSql('ALTER TABLE media_files ALTER owner_type DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE media_files ALTER type SET NOT NULL');
        $this->addSql('ALTER TABLE media_files ALTER owner_id SET NOT NULL');
        $this->addSql('ALTER TABLE media_files ALTER owner_type SET NOT NULL');
    }
}
