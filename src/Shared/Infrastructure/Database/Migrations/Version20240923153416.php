<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240923153416 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders_product DROP CONSTRAINT fk_223f76d6ab0b6d26');
        $this->addSql('DROP SEQUENCE orders_printer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE orders_roll_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE orders_roll_history_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE prod_process_history_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE prod_process_job_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE prod_process_printer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE prod_process_roll_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE prod_process_history (id INT NOT NULL, roll_id INT DEFAULT NULL, parent_roll_id INT DEFAULT NULL, employee_id INT DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, process VARCHAR(255) NOT NULL, happened_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN prod_process_history.happened_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE prod_process_job (id INT NOT NULL, order_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, length NUMERIC(15, 2) DEFAULT NULL, lamination_type VARCHAR(255) DEFAULT NULL, film_type VARCHAR(255) DEFAULT NULL, has_priority BOOLEAN DEFAULT NULL, sort_order INT DEFAULT 0, date_added TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6B76934C8D9F6D38 ON prod_process_job (order_id)');
        $this->addSql('COMMENT ON COLUMN prod_process_job.date_added IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE prod_process_printer (id INT NOT NULL, name VARCHAR(255) NOT NULL, color VARCHAR(255) DEFAULT NULL, is_available BOOLEAN NOT NULL, film_types jsonb DEFAULT \'[]\' NOT NULL, lamination_types jsonb DEFAULT \'[]\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE prod_process_roll (id INT NOT NULL, parent_roll_id INT DEFAULT NULL, printer_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, employee_id INT DEFAULT NULL, film_id INT DEFAULT NULL, process VARCHAR(255) DEFAULT NULL, date_added TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E229EB28D9431960 ON prod_process_roll (parent_roll_id)');
        $this->addSql('CREATE INDEX IDX_E229EB2846EC494A ON prod_process_roll (printer_id)');
        $this->addSql('COMMENT ON COLUMN prod_process_roll.date_added IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE prod_process_job ADD CONSTRAINT FK_6B76934C8D9F6D38 FOREIGN KEY (order_id) REFERENCES prod_process_roll (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prod_process_roll ADD CONSTRAINT FK_E229EB28D9431960 FOREIGN KEY (parent_roll_id) REFERENCES prod_process_roll (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prod_process_roll ADD CONSTRAINT FK_E229EB2846EC494A FOREIGN KEY (printer_id) REFERENCES prod_process_printer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders_roll DROP CONSTRAINT fk_8060f36546ec494a');
        $this->addSql('ALTER TABLE orders_roll DROP CONSTRAINT fk_8060f365d9431960');
        $this->addSql('DROP TABLE orders_roll_history');
        $this->addSql('DROP TABLE orders_roll');
        $this->addSql('DROP TABLE orders_printer');
        $this->addSql('DROP INDEX idx_223f76d6ab0b6d26');
        $this->addSql('ALTER TABLE orders_product DROP roll_id');
        $this->addSql('ALTER TABLE orders_product DROP status');
        $this->addSql('ALTER TABLE orders_product DROP length');
        $this->addSql('ALTER TABLE orders_product DROP has_priority');
        $this->addSql('ALTER TABLE orders_product DROP sort_order');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE prod_process_history_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE prod_process_job_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE prod_process_printer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE prod_process_roll_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE orders_printer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE orders_roll_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE orders_roll_history_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE orders_roll_history (id INT NOT NULL, roll_id INT DEFAULT NULL, employee_id INT DEFAULT NULL, process VARCHAR(255) NOT NULL, happened_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, type VARCHAR(255) DEFAULT NULL, parent_roll_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN orders_roll_history.happened_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE orders_roll (id INT NOT NULL, printer_id INT DEFAULT NULL, parent_roll_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, film_id INT DEFAULT NULL, process VARCHAR(255) DEFAULT NULL, date_added TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, employee_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_8060f365d9431960 ON orders_roll (parent_roll_id)');
        $this->addSql('CREATE INDEX idx_8060f36546ec494a ON orders_roll (printer_id)');
        $this->addSql('COMMENT ON COLUMN orders_roll.date_added IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE orders_printer (id INT NOT NULL, name VARCHAR(255) NOT NULL, color VARCHAR(255) DEFAULT NULL, is_available BOOLEAN NOT NULL, film_types jsonb DEFAULT \'[]\' NOT NULL, lamination_types jsonb DEFAULT \'[]\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE orders_roll ADD CONSTRAINT fk_8060f36546ec494a FOREIGN KEY (printer_id) REFERENCES orders_printer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders_roll ADD CONSTRAINT fk_8060f365d9431960 FOREIGN KEY (parent_roll_id) REFERENCES orders_roll (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prod_process_job DROP CONSTRAINT FK_6B76934C8D9F6D38');
        $this->addSql('ALTER TABLE prod_process_roll DROP CONSTRAINT FK_E229EB28D9431960');
        $this->addSql('ALTER TABLE prod_process_roll DROP CONSTRAINT FK_E229EB2846EC494A');
        $this->addSql('DROP TABLE prod_process_history');
        $this->addSql('DROP TABLE prod_process_job');
        $this->addSql('DROP TABLE prod_process_printer');
        $this->addSql('DROP TABLE prod_process_roll');
        $this->addSql('ALTER TABLE orders_product ADD roll_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders_product ADD status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE orders_product ADD length NUMERIC(15, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE orders_product ADD has_priority BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE orders_product ADD sort_order INT DEFAULT 0');
        $this->addSql('ALTER TABLE orders_product ADD CONSTRAINT fk_223f76d6ab0b6d26 FOREIGN KEY (roll_id) REFERENCES orders_roll (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_223f76d6ab0b6d26 ON orders_product (roll_id)');
    }
}
