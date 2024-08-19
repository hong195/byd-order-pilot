<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240819154254 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE inventory_film_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE media_files_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE orders_order_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE orders_printer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE orders_roll_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE refresh_token_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE users_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE inventory_film (id INT NOT NULL, name VARCHAR(255) NOT NULL, length BIGINT NOT NULL, type VARCHAR(255) NOT NULL, date_added TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, dtype VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN inventory_film.date_added IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE media_files (id INT NOT NULL, filename VARCHAR(255) NOT NULL, source VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, type VARCHAR(50) DEFAULT NULL, owner_id INT DEFAULT NULL, owner_type VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE orders_order (id INT NOT NULL, cut_file_id INT DEFAULT NULL, print_file_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, length BIGINT DEFAULT NULL, lamination_type VARCHAR(255) DEFAULT NULL, film_type VARCHAR(255) DEFAULT NULL, has_priority BOOLEAN DEFAULT NULL, product_type VARCHAR(255) NOT NULL, order_number BIGINT DEFAULT NULL, sort_order INT DEFAULT 0 NOT NULL, date_added TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B4833C399D3FDAA8 ON orders_order (cut_file_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B4833C39B6A86CB2 ON orders_order (print_file_id)');
        $this->addSql('COMMENT ON COLUMN orders_order.date_added IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE orders_printer (id INT NOT NULL, name VARCHAR(255) NOT NULL, color VARCHAR(255) DEFAULT NULL, is_available BOOLEAN NOT NULL, film_types jsonb DEFAULT \'[]\' NOT NULL, lamination_types jsonb DEFAULT \'[]\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE orders_roll (id INT NOT NULL, printer_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, film_id INT DEFAULT NULL, process VARCHAR(255) DEFAULT NULL, date_added TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8060F36546EC494A ON orders_roll (printer_id)');
        $this->addSql('COMMENT ON COLUMN orders_roll.date_added IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE orders_roll_order (roll_id INT NOT NULL, order_id INT NOT NULL, PRIMARY KEY(roll_id, order_id))');
        $this->addSql('CREATE INDEX IDX_F2AF27E3AB0B6D26 ON orders_roll_order (roll_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F2AF27E38D9F6D38 ON orders_roll_order (order_id)');
        $this->addSql('CREATE TABLE refresh_token (id INT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C74F2195C74F2195 ON refresh_token (refresh_token)');
        $this->addSql('CREATE TABLE users (id INT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) DEFAULT NULL, roles JSON DEFAULT \'[]\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('ALTER TABLE orders_order ADD CONSTRAINT FK_B4833C399D3FDAA8 FOREIGN KEY (cut_file_id) REFERENCES media_files (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders_order ADD CONSTRAINT FK_B4833C39B6A86CB2 FOREIGN KEY (print_file_id) REFERENCES media_files (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders_roll ADD CONSTRAINT FK_8060F36546EC494A FOREIGN KEY (printer_id) REFERENCES orders_printer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders_roll_order ADD CONSTRAINT FK_F2AF27E3AB0B6D26 FOREIGN KEY (roll_id) REFERENCES orders_roll (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders_roll_order ADD CONSTRAINT FK_F2AF27E38D9F6D38 FOREIGN KEY (order_id) REFERENCES orders_order (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE inventory_film_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE media_files_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE orders_order_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE orders_printer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE orders_roll_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE refresh_token_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE users_id_seq CASCADE');
        $this->addSql('ALTER TABLE orders_order DROP CONSTRAINT FK_B4833C399D3FDAA8');
        $this->addSql('ALTER TABLE orders_order DROP CONSTRAINT FK_B4833C39B6A86CB2');
        $this->addSql('ALTER TABLE orders_roll DROP CONSTRAINT FK_8060F36546EC494A');
        $this->addSql('ALTER TABLE orders_roll_order DROP CONSTRAINT FK_F2AF27E3AB0B6D26');
        $this->addSql('ALTER TABLE orders_roll_order DROP CONSTRAINT FK_F2AF27E38D9F6D38');
        $this->addSql('DROP TABLE inventory_film');
        $this->addSql('DROP TABLE media_files');
        $this->addSql('DROP TABLE orders_order');
        $this->addSql('DROP TABLE orders_printer');
        $this->addSql('DROP TABLE orders_roll');
        $this->addSql('DROP TABLE orders_roll_order');
        $this->addSql('DROP TABLE refresh_token');
        $this->addSql('DROP TABLE users');
    }
}
