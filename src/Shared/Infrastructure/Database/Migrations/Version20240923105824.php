<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240923105824 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders_product ADD order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders_product ADD CONSTRAINT FK_223F76D68D9F6D38 FOREIGN KEY (order_id) REFERENCES orders_order (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders_product ADD CONSTRAINT FK_223F76D6AB0B6D26 FOREIGN KEY (roll_id) REFERENCES orders_roll (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_223F76D68D9F6D38 ON orders_product (order_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE orders_product DROP CONSTRAINT FK_223F76D68D9F6D38');
        $this->addSql('ALTER TABLE orders_product DROP CONSTRAINT FK_223F76D6AB0B6D26');
        $this->addSql('DROP INDEX IDX_223F76D68D9F6D38');
        $this->addSql('ALTER TABLE orders_product DROP order_id');
    }
}
