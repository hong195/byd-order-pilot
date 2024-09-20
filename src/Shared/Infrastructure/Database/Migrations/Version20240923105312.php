<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240923105312 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE orders_product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE orders_product (id INT NOT NULL, cut_file_id INT DEFAULT NULL, print_file_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, length NUMERIC(15, 2) DEFAULT NULL, lamination_type VARCHAR(255) DEFAULT NULL, film_type VARCHAR(255) DEFAULT NULL, has_priority BOOLEAN DEFAULT NULL, sort_order INT DEFAULT 0, is_packed BOOLEAN DEFAULT false NOT NULL, date_added TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, roll_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_223F76D69D3FDAA8 ON orders_product (cut_file_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_223F76D6B6A86CB2 ON orders_product (print_file_id)');
        $this->addSql('CREATE INDEX IDX_223F76D6AB0B6D26 ON orders_product (roll_id)');
        $this->addSql('COMMENT ON COLUMN orders_product.date_added IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE orders_product ADD CONSTRAINT FK_223F76D69D3FDAA8 FOREIGN KEY (cut_file_id) REFERENCES media_files (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders_product ADD CONSTRAINT FK_223F76D6B6A86CB2 FOREIGN KEY (print_file_id) REFERENCES media_files (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders_order DROP CONSTRAINT fk_b4833c399d3fdaa8');
        $this->addSql('ALTER TABLE orders_order DROP CONSTRAINT fk_b4833c39b6a86cb2');
        $this->addSql('ALTER TABLE orders_order DROP CONSTRAINT fk_b4833c39ab0b6d26');
        $this->addSql('DROP INDEX idx_b4833c39ab0b6d26');
        $this->addSql('DROP INDEX uniq_b4833c39b6a86cb2');
        $this->addSql('DROP INDEX uniq_b4833c399d3fdaa8');
        $this->addSql('ALTER TABLE orders_order DROP cut_file_id');
        $this->addSql('ALTER TABLE orders_order DROP print_file_id');
        $this->addSql('ALTER TABLE orders_order DROP roll_id');
        $this->addSql('ALTER TABLE orders_order DROP status');
        $this->addSql('ALTER TABLE orders_order DROP length');
        $this->addSql('ALTER TABLE orders_order DROP lamination_type');
        $this->addSql('ALTER TABLE orders_order DROP film_type');
        $this->addSql('ALTER TABLE orders_order DROP has_priority');
        $this->addSql('ALTER TABLE orders_order DROP sort_order');
        $this->addSql('ALTER TABLE orders_order DROP is_packed');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE orders_product_id_seq CASCADE');
        $this->addSql('ALTER TABLE orders_product DROP CONSTRAINT FK_223F76D69D3FDAA8');
        $this->addSql('ALTER TABLE orders_product DROP CONSTRAINT FK_223F76D6B6A86CB2');
        $this->addSql('DROP TABLE orders_product');
        $this->addSql('ALTER TABLE orders_order ADD cut_file_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders_order ADD print_file_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders_order ADD roll_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders_order ADD status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE orders_order ADD length NUMERIC(15, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE orders_order ADD lamination_type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE orders_order ADD film_type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE orders_order ADD has_priority BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE orders_order ADD sort_order INT DEFAULT 0');
        $this->addSql('ALTER TABLE orders_order ADD is_packed BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE orders_order ADD CONSTRAINT fk_b4833c399d3fdaa8 FOREIGN KEY (cut_file_id) REFERENCES media_files (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders_order ADD CONSTRAINT fk_b4833c39b6a86cb2 FOREIGN KEY (print_file_id) REFERENCES media_files (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders_order ADD CONSTRAINT fk_b4833c39ab0b6d26 FOREIGN KEY (roll_id) REFERENCES orders_roll (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_b4833c39ab0b6d26 ON orders_order (roll_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_b4833c39b6a86cb2 ON orders_order (print_file_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_b4833c399d3fdaa8 ON orders_order (cut_file_id)');
    }
}
