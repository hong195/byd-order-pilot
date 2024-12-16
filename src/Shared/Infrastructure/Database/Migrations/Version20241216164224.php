<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241216164224 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE refresh_token_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE inventory_film (id VARCHAR(26) NOT NULL, name VARCHAR(255) NOT NULL, length DOUBLE PRECISION NOT NULL, type VARCHAR(255) NOT NULL, date_added TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, dtype VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN inventory_film.date_added IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE inventory_history (id VARCHAR(26) NOT NULL, film_id VARCHAR(255) NOT NULL, inventory_type VARCHAR(255) NOT NULL, film_type VARCHAR(255) NOT NULL, event_type VARCHAR(255) NOT NULL, new_size NUMERIC(15, 2) NOT NULL, old_size NUMERIC(15, 2) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN inventory_history.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE media_files (id VARCHAR(26) NOT NULL, filename VARCHAR(255) NOT NULL, source VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, type VARCHAR(50) DEFAULT NULL, owner_id VARCHAR(255) DEFAULT NULL, owner_type VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE orders_extra (id VARCHAR(26) NOT NULL, order_id VARCHAR(26) DEFAULT NULL, name VARCHAR(255) NOT NULL, order_number VARCHAR(255) NOT NULL, count INT DEFAULT 0 NOT NULL, is_packed BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C95A2C48D9F6D38 ON orders_extra (order_id)');
        $this->addSql('CREATE TABLE orders_order (id VARCHAR(26) NOT NULL, order_number VARCHAR(255) DEFAULT NULL, shipping_address TEXT DEFAULT NULL, packaging_instructions TEXT DEFAULT NULL, date_added TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, customer_name VARCHAR(255) NOT NULL, customer_notes TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN orders_order.date_added IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE orders_product (id VARCHAR(26) NOT NULL, cut_file_id VARCHAR(26) DEFAULT NULL, print_file_id VARCHAR(26) DEFAULT NULL, order_id VARCHAR(26) DEFAULT NULL, lamination_type VARCHAR(255) DEFAULT NULL, length NUMERIC(15, 2) NOT NULL, film_type VARCHAR(255) DEFAULT NULL, is_packed BOOLEAN DEFAULT false NOT NULL, date_added TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_223F76D69D3FDAA8 ON orders_product (cut_file_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_223F76D6B6A86CB2 ON orders_product (print_file_id)');
        $this->addSql('CREATE INDEX IDX_223F76D68D9F6D38 ON orders_product (order_id)');
        $this->addSql('COMMENT ON COLUMN orders_product.date_added IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE prod_process_error (id VARCHAR(26) NOT NULL, printed_product_id INT NOT NULL, process VARCHAR(255) NOT NULL, responsible_employee_id INT NOT NULL, noticer_id INT NOT NULL, reason TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX printed_product_id_index ON prod_process_error (printed_product_id)');
        $this->addSql('CREATE INDEX process_index ON prod_process_error (process)');
        $this->addSql('CREATE INDEX noticer_id_index ON prod_process_error (noticer_id)');
        $this->addSql('CREATE INDEX createdAt_index ON prod_process_error (created_at)');
        $this->addSql('COMMENT ON COLUMN prod_process_error.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE prod_process_printed_product (id VARCHAR(26) NOT NULL, photo_id VARCHAR(26) DEFAULT NULL, roll_id VARCHAR(26) DEFAULT NULL, related_product_id INT DEFAULT NULL, length NUMERIC(15, 2) DEFAULT NULL, order_number VARCHAR(255) DEFAULT NULL, lamination_type VARCHAR(255) DEFAULT NULL, film_type VARCHAR(255) DEFAULT NULL, has_priority BOOLEAN DEFAULT NULL, sort_order INT DEFAULT 0, is_reprint BOOLEAN DEFAULT false NOT NULL, date_added TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_453DEB627E9E4C8C ON prod_process_printed_product (photo_id)');
        $this->addSql('CREATE INDEX IDX_453DEB62AB0B6D26 ON prod_process_printed_product (roll_id)');
        $this->addSql('COMMENT ON COLUMN prod_process_printed_product.date_added IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE prod_process_printer (id VARCHAR(26) NOT NULL, name VARCHAR(255) NOT NULL, is_available BOOLEAN DEFAULT true NOT NULL, is_default BOOLEAN DEFAULT false NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE prod_process_printer_condition (id VARCHAR(26) NOT NULL, printer_id VARCHAR(26) NOT NULL, film_type VARCHAR(50) DEFAULT NULL, lamination_type VARCHAR(50) DEFAULT NULL, lamination_required BOOLEAN DEFAULT false, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_874224D446EC494A ON prod_process_printer_condition (printer_id)');
        $this->addSql('CREATE INDEX idx_film_type ON prod_process_printer_condition (film_type)');
        $this->addSql('CREATE INDEX idx_lamination_type ON prod_process_printer_condition (lamination_type)');
        $this->addSql('CREATE TABLE prod_process_roll (id VARCHAR(26) NOT NULL, parent_roll_id VARCHAR(26) DEFAULT NULL, printer_id VARCHAR(26) DEFAULT NULL, name VARCHAR(255) NOT NULL, employee_id INT DEFAULT NULL, film_id INT DEFAULT NULL, process VARCHAR(255) DEFAULT NULL, is_locked BOOLEAN DEFAULT false NOT NULL, date_added TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E229EB28D9431960 ON prod_process_roll (parent_roll_id)');
        $this->addSql('CREATE INDEX IDX_E229EB2846EC494A ON prod_process_roll (printer_id)');
        $this->addSql('COMMENT ON COLUMN prod_process_roll.date_added IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE prod_process_roll_history (id VARCHAR(26) NOT NULL, roll_id INT DEFAULT NULL, parent_roll_id INT DEFAULT NULL, employee_id INT DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, process VARCHAR(255) NOT NULL, happened_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN prod_process_roll_history.happened_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE refresh_token (id INT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C74F2195C74F2195 ON refresh_token (refresh_token)');
        $this->addSql('CREATE TABLE users (id VARCHAR(26) NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) DEFAULT NULL, roles JSON DEFAULT \'[]\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('ALTER TABLE orders_extra ADD CONSTRAINT FK_C95A2C48D9F6D38 FOREIGN KEY (order_id) REFERENCES orders_order (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders_product ADD CONSTRAINT FK_223F76D69D3FDAA8 FOREIGN KEY (cut_file_id) REFERENCES media_files (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders_product ADD CONSTRAINT FK_223F76D6B6A86CB2 FOREIGN KEY (print_file_id) REFERENCES media_files (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders_product ADD CONSTRAINT FK_223F76D68D9F6D38 FOREIGN KEY (order_id) REFERENCES orders_order (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prod_process_printed_product ADD CONSTRAINT FK_453DEB627E9E4C8C FOREIGN KEY (photo_id) REFERENCES media_files (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prod_process_printed_product ADD CONSTRAINT FK_453DEB62AB0B6D26 FOREIGN KEY (roll_id) REFERENCES prod_process_roll (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prod_process_printer_condition ADD CONSTRAINT FK_874224D446EC494A FOREIGN KEY (printer_id) REFERENCES prod_process_printer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prod_process_roll ADD CONSTRAINT FK_E229EB28D9431960 FOREIGN KEY (parent_roll_id) REFERENCES prod_process_roll (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prod_process_roll ADD CONSTRAINT FK_E229EB2846EC494A FOREIGN KEY (printer_id) REFERENCES prod_process_printer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE refresh_token_id_seq CASCADE');
        $this->addSql('ALTER TABLE orders_extra DROP CONSTRAINT FK_C95A2C48D9F6D38');
        $this->addSql('ALTER TABLE orders_product DROP CONSTRAINT FK_223F76D69D3FDAA8');
        $this->addSql('ALTER TABLE orders_product DROP CONSTRAINT FK_223F76D6B6A86CB2');
        $this->addSql('ALTER TABLE orders_product DROP CONSTRAINT FK_223F76D68D9F6D38');
        $this->addSql('ALTER TABLE prod_process_printed_product DROP CONSTRAINT FK_453DEB627E9E4C8C');
        $this->addSql('ALTER TABLE prod_process_printed_product DROP CONSTRAINT FK_453DEB62AB0B6D26');
        $this->addSql('ALTER TABLE prod_process_printer_condition DROP CONSTRAINT FK_874224D446EC494A');
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
        $this->addSql('DROP TABLE prod_process_printer_condition');
        $this->addSql('DROP TABLE prod_process_roll');
        $this->addSql('DROP TABLE prod_process_roll_history');
        $this->addSql('DROP TABLE refresh_token');
        $this->addSql('DROP TABLE users');
    }
}
