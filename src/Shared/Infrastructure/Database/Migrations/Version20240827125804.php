<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240827125804 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE orders_product_id_seq CASCADE');
        $this->addSql('ALTER TABLE orders_product DROP CONSTRAINT fk_223f76d68d9f6d38');
        $this->addSql('DROP TABLE orders_product');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE orders_product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE orders_product (id INT NOT NULL, order_id INT DEFAULT NULL, lamination_type VARCHAR(255) DEFAULT NULL, film_type VARCHAR(255) DEFAULT NULL, has_priority BOOLEAN DEFAULT NULL, date_added TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_packed BOOLEAN DEFAULT NULL, is_sent BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_223f76d68d9f6d38 ON orders_product (order_id)');
        $this->addSql('COMMENT ON COLUMN orders_product.date_added IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE orders_product ADD CONSTRAINT fk_223f76d68d9f6d38 FOREIGN KEY (order_id) REFERENCES orders_order (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
