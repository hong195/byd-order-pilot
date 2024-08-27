<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240827100122 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE orders_extra_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE orders_extra (id INT NOT NULL, order_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, order_number VARCHAR(255) NOT NULL, is_checked BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C95A2C48D9F6D38 ON orders_extra (order_id)');
        $this->addSql('ALTER TABLE orders_extra ADD CONSTRAINT FK_C95A2C48D9F6D38 FOREIGN KEY (order_id) REFERENCES orders_order (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE orders_extra_id_seq CASCADE');
        $this->addSql('ALTER TABLE orders_extra DROP CONSTRAINT FK_C95A2C48D9F6D38');
        $this->addSql('DROP TABLE orders_extra');
    }
}
