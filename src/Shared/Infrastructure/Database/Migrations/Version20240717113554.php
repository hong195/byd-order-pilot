<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240717113554 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE laminations_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE laminations (id INT NOT NULL, name VARCHAR(255) NOT NULL, date_added TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, length INT NOT NULL, quality VARCHAR(255) NOT NULL, quality_notes VARCHAR(255) DEFAULT NULL, lamination_type VARCHAR(255) NOT NULL, priority INT NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE laminations_id_seq CASCADE');
        $this->addSql('DROP TABLE laminations');
    }
}
