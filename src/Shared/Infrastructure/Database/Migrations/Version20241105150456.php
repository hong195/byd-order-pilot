<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241105150456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE prod_process_printer_condition_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE prod_process_printer_condition (id INT NOT NULL, printer_id INT NOT NULL, film_type VARCHAR(50) DEFAULT NULL, lamination_type VARCHAR(50) DEFAULT NULL, lamination_required BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_874224D446EC494A ON prod_process_printer_condition (printer_id)');
        $this->addSql('CREATE INDEX idx_film_type ON prod_process_printer_condition (film_type)');
        $this->addSql('CREATE INDEX idx_lamination_type ON prod_process_printer_condition (lamination_type)');
        $this->addSql('CREATE INDEX idx_lamination_required ON prod_process_printer_condition (lamination_required)');
        $this->addSql('ALTER TABLE prod_process_printer_condition ADD CONSTRAINT FK_874224D446EC494A FOREIGN KEY (printer_id) REFERENCES prod_process_printer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prod_process_printer ADD "default" BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE prod_process_printer DROP color');
        $this->addSql('ALTER TABLE prod_process_printer DROP film_types');
        $this->addSql('ALTER TABLE prod_process_printer DROP lamination_types');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE prod_process_printer_condition_id_seq CASCADE');
        $this->addSql('ALTER TABLE prod_process_printer_condition DROP CONSTRAINT FK_874224D446EC494A');
        $this->addSql('DROP TABLE prod_process_printer_condition');
        $this->addSql('ALTER TABLE prod_process_printer ADD color VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE prod_process_printer ADD film_types jsonb DEFAULT \'[]\' NOT NULL');
        $this->addSql('ALTER TABLE prod_process_printer ADD lamination_types jsonb DEFAULT \'[]\' NOT NULL');
        $this->addSql('ALTER TABLE prod_process_printer DROP "default"');
    }
}
