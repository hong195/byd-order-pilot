<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240805151446 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE media_files_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE order_stacks_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE orders_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE orders_printers_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE refresh_token_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE rolls_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE users_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE media_files (id INT NOT NULL, filename VARCHAR(255) NOT NULL, source VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, type VARCHAR(50) DEFAULT NULL, owner_id INT DEFAULT NULL, owner_type VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE order_stacks (id INT NOT NULL, name VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, length BIGINT NOT NULL, lamination_type VARCHAR(255) DEFAULT NULL, roll_type VARCHAR(255) DEFAULT NULL, date_added TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN order_stacks.date_added IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE order_stacks_orders (order_id INT NOT NULL, order_stack_id INT NOT NULL, PRIMARY KEY(order_id, order_stack_id))');
        $this->addSql('CREATE INDEX IDX_54761E038D9F6D38 ON order_stacks_orders (order_id)');
        $this->addSql('CREATE INDEX IDX_54761E03D63FE2D1 ON order_stacks_orders (order_stack_id)');
        $this->addSql('CREATE TABLE orders (id INT NOT NULL, cut_file_id INT DEFAULT NULL, print_file_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, length BIGINT DEFAULT NULL, lamination_type VARCHAR(255) DEFAULT NULL, roll_type VARCHAR(255) DEFAULT NULL, has_priority BOOLEAN DEFAULT NULL, product_type VARCHAR(255) NOT NULL, order_number BIGINT DEFAULT NULL, date_added TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E52FFDEE9D3FDAA8 ON orders (cut_file_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E52FFDEEB6A86CB2 ON orders (print_file_id)');
        $this->addSql('COMMENT ON COLUMN orders.date_added IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE orders_printers (id INT NOT NULL, name VARCHAR(255) NOT NULL, color VARCHAR(255) DEFAULT NULL, is_available BOOLEAN NOT NULL, roll_types JSON DEFAULT \'[]\' NOT NULL, lamination_types JSON DEFAULT \'[]\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE refresh_token (id INT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C74F2195C74F2195 ON refresh_token (refresh_token)');
        $this->addSql('CREATE TABLE rolls (id INT NOT NULL, name VARCHAR(255) NOT NULL, date_added TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, length INT NOT NULL, quality VARCHAR(255) NOT NULL, quality_notes TEXT DEFAULT NULL, roll_type VARCHAR(255) NOT NULL, priority INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN rolls.date_added IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE users (id INT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) DEFAULT NULL, roles JSON DEFAULT \'[]\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('ALTER TABLE order_stacks_orders ADD CONSTRAINT FK_54761E038D9F6D38 FOREIGN KEY (order_id) REFERENCES order_stacks (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_stacks_orders ADD CONSTRAINT FK_54761E03D63FE2D1 FOREIGN KEY (order_stack_id) REFERENCES orders (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE9D3FDAA8 FOREIGN KEY (cut_file_id) REFERENCES media_files (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEB6A86CB2 FOREIGN KEY (print_file_id) REFERENCES media_files (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE media_files_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE order_stacks_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE orders_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE orders_printers_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE refresh_token_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE rolls_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE users_id_seq CASCADE');
        $this->addSql('ALTER TABLE order_stacks_orders DROP CONSTRAINT FK_54761E038D9F6D38');
        $this->addSql('ALTER TABLE order_stacks_orders DROP CONSTRAINT FK_54761E03D63FE2D1');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT FK_E52FFDEE9D3FDAA8');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT FK_E52FFDEEB6A86CB2');
        $this->addSql('DROP TABLE media_files');
        $this->addSql('DROP TABLE order_stacks');
        $this->addSql('DROP TABLE order_stacks_orders');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE orders_printers');
        $this->addSql('DROP TABLE refresh_token');
        $this->addSql('DROP TABLE rolls');
        $this->addSql('DROP TABLE users');
    }
}
