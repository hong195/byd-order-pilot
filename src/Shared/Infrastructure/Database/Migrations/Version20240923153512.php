<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240923153512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE prod_process_history_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE prod_process_roll_history_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE prod_process_roll_history (id INT NOT NULL, roll_id INT DEFAULT NULL, parent_roll_id INT DEFAULT NULL, employee_id INT DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, process VARCHAR(255) NOT NULL, happened_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN prod_process_roll_history.happened_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('DROP TABLE prod_process_history');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE prod_process_roll_history_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE prod_process_history_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE prod_process_history (id INT NOT NULL, roll_id INT DEFAULT NULL, parent_roll_id INT DEFAULT NULL, employee_id INT DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, process VARCHAR(255) NOT NULL, happened_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN prod_process_history.happened_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('DROP TABLE prod_process_roll_history');
    }
}
