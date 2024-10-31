<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241030124448 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE inventory_film_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE inventory_history_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE media_files_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE orders_extra_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE orders_order_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE orders_product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE prod_process_error_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE prod_process_printed_product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE prod_process_printer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE prod_process_roll_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE prod_process_roll_history_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE refresh_token_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE users_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE inventory_film (id INT NOT NULL, name VARCHAR(255) NOT NULL, length DOUBLE PRECISION NOT NULL, type VARCHAR(255) NOT NULL, date_added TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, dtype VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN inventory_film.date_added IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE inventory_history (id INT NOT NULL, film_id INT NOT NULL, inventory_type VARCHAR(255) NOT NULL, film_type VARCHAR(255) NOT NULL, event_type VARCHAR(255) NOT NULL, new_size NUMERIC(15, 2) NOT NULL, old_size NUMERIC(15, 2) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN inventory_history.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE media_files (id INT NOT NULL, filename VARCHAR(255) NOT NULL, source VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, type VARCHAR(50) DEFAULT NULL, owner_id INT DEFAULT NULL, owner_type VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE orders_extra (id INT NOT NULL, order_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, order_number VARCHAR(255) NOT NULL, count INT DEFAULT 0 NOT NULL, is_packed BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C95A2C48D9F6D38 ON orders_extra (order_id)');
        $this->addSql('CREATE TABLE orders_order (id INT NOT NULL, order_number VARCHAR(255) DEFAULT NULL, shipping_address TEXT DEFAULT NULL, packaging_instructions TEXT DEFAULT NULL, date_added TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, customer_name VARCHAR(255) NOT NULL, customer_notes TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN orders_order.date_added IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE orders_product (id INT NOT NULL, cut_file_id INT DEFAULT NULL, print_file_id INT DEFAULT NULL, order_id INT DEFAULT NULL, lamination_type VARCHAR(255) DEFAULT NULL, length NUMERIC(15, 2) NOT NULL, film_type VARCHAR(255) DEFAULT NULL, is_packed BOOLEAN DEFAULT false NOT NULL, date_added TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_223F76D69D3FDAA8 ON orders_product (cut_file_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_223F76D6B6A86CB2 ON orders_product (print_file_id)');
        $this->addSql('CREATE INDEX IDX_223F76D68D9F6D38 ON orders_product (order_id)');
        $this->addSql('COMMENT ON COLUMN orders_product.date_added IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE prod_process_error (id INT NOT NULL, printed_product_id INT NOT NULL, process VARCHAR(255) NOT NULL, responsible_employee_id INT NOT NULL, noticer_id INT NOT NULL, reason TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX printed_product_id_index ON prod_process_error (printed_product_id)');
        $this->addSql('CREATE INDEX process_index ON prod_process_error (process)');
        $this->addSql('CREATE INDEX noticer_id_index ON prod_process_error (noticer_id)');
        $this->addSql('CREATE INDEX createdAt_index ON prod_process_error (created_at)');
        $this->addSql('COMMENT ON COLUMN prod_process_error.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE prod_process_printed_product (id INT NOT NULL, roll_id INT DEFAULT NULL, related_product_id INT DEFAULT NULL, length NUMERIC(15, 2) DEFAULT NULL, order_number VARCHAR(255) DEFAULT NULL, lamination_type VARCHAR(255) DEFAULT NULL, film_type VARCHAR(255) DEFAULT NULL, has_priority BOOLEAN DEFAULT NULL, sort_order INT DEFAULT 0, is_reprint BOOLEAN DEFAULT false NOT NULL, date_added TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_453DEB62AB0B6D26 ON prod_process_printed_product (roll_id)');
        $this->addSql('COMMENT ON COLUMN prod_process_printed_product.date_added IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE prod_process_printer (id INT NOT NULL, name VARCHAR(255) NOT NULL, color VARCHAR(255) DEFAULT NULL, is_available BOOLEAN NOT NULL, film_types jsonb DEFAULT \'[]\' NOT NULL, lamination_types jsonb DEFAULT \'[]\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE prod_process_roll (id INT NOT NULL, parent_roll_id INT DEFAULT NULL, printer_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, employee_id INT DEFAULT NULL, film_id INT DEFAULT NULL, process VARCHAR(255) DEFAULT NULL, date_added TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E229EB28D9431960 ON prod_process_roll (parent_roll_id)');
        $this->addSql('CREATE INDEX IDX_E229EB2846EC494A ON prod_process_roll (printer_id)');
        $this->addSql('COMMENT ON COLUMN prod_process_roll.date_added IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE prod_process_roll_history (id INT NOT NULL, roll_id INT DEFAULT NULL, parent_roll_id INT DEFAULT NULL, employee_id INT DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, process VARCHAR(255) NOT NULL, happened_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN prod_process_roll_history.happened_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE refresh_token (id INT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C74F2195C74F2195 ON refresh_token (refresh_token)');
        $this->addSql('CREATE TABLE users (id INT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) DEFAULT NULL, roles JSON DEFAULT \'[]\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('ALTER TABLE orders_extra ADD CONSTRAINT FK_C95A2C48D9F6D38 FOREIGN KEY (order_id) REFERENCES orders_order (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders_product ADD CONSTRAINT FK_223F76D69D3FDAA8 FOREIGN KEY (cut_file_id) REFERENCES media_files (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders_product ADD CONSTRAINT FK_223F76D6B6A86CB2 FOREIGN KEY (print_file_id) REFERENCES media_files (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders_product ADD CONSTRAINT FK_223F76D68D9F6D38 FOREIGN KEY (order_id) REFERENCES orders_order (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prod_process_printed_product ADD CONSTRAINT FK_453DEB62AB0B6D26 FOREIGN KEY (roll_id) REFERENCES prod_process_roll (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prod_process_roll ADD CONSTRAINT FK_E229EB28D9431960 FOREIGN KEY (parent_roll_id) REFERENCES prod_process_roll (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prod_process_roll ADD CONSTRAINT FK_E229EB2846EC494A FOREIGN KEY (printer_id) REFERENCES prod_process_printer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE inventory_film_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE inventory_history_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE media_files_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE orders_extra_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE orders_order_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE orders_product_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE prod_process_error_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE prod_process_printed_product_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE prod_process_printer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE prod_process_roll_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE prod_process_roll_history_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE refresh_token_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE users_id_seq CASCADE');
        $this->addSql('ALTER TABLE orders_extra DROP CONSTRAINT FK_C95A2C48D9F6D38');
        $this->addSql('ALTER TABLE orders_product DROP CONSTRAINT FK_223F76D69D3FDAA8');
        $this->addSql('ALTER TABLE orders_product DROP CONSTRAINT FK_223F76D6B6A86CB2');
        $this->addSql('ALTER TABLE orders_product DROP CONSTRAINT FK_223F76D68D9F6D38');
        $this->addSql('ALTER TABLE prod_process_printed_product DROP CONSTRAINT FK_453DEB62AB0B6D26');
        $this->addSql('ALTER TABLE prod_process_roll DROP CONSTRAINT FK_E229EB28D9431960');
        $this->addSql('ALTER TABLE prod_process_roll DROP CONSTRAINT FK_E229EB2846EC494A');
        $this->addSql('DROP TABLE inventory_film');
        $this->addSql('DROP TABLE inventory_history');
        $this->addSql('DROP TABLE media_files');
        $this->addSql('DROP TABLE orders_extra');
        $this->addSql('DROP TABLE orders_order');
        $this->addSql('DROP TABLE orders_product');
        $this->addSql('DROP TABLE prod_process_error');
        $this->addSql('DROP TABLE prod_process_printed_product');
        $this->addSql('DROP TABLE prod_process_printer');
        $this->addSql('DROP TABLE prod_process_roll');
        $this->addSql('DROP TABLE prod_process_roll_history');
        $this->addSql('DROP TABLE refresh_token');
        $this->addSql('DROP TABLE users');
    }
}
