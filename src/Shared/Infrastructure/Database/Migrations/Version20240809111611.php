<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240809111611 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE orders_roll_printer (printer_id INT NOT NULL, order_roll_id INT NOT NULL, PRIMARY KEY(printer_id, order_roll_id))');
        $this->addSql('CREATE INDEX IDX_8D8BA9C346EC494A ON orders_roll_printer (printer_id)');
        $this->addSql('CREATE INDEX IDX_8D8BA9C35D71C46D ON orders_roll_printer (order_roll_id)');
        $this->addSql('ALTER TABLE orders_roll_printer ADD CONSTRAINT FK_8D8BA9C346EC494A FOREIGN KEY (printer_id) REFERENCES orders_roll (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders_roll_printer ADD CONSTRAINT FK_8D8BA9C35D71C46D FOREIGN KEY (order_roll_id) REFERENCES orders_printer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders_order ADD roll_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders_order ADD CONSTRAINT FK_B4833C39AB0B6D26 FOREIGN KEY (roll_id) REFERENCES orders_roll (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B4833C39AB0B6D26 ON orders_order (roll_id)');
        $this->addSql('ALTER TABLE orders_roll ADD roll_type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE orders_roll ADD lamination_types JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE orders_roll_printer DROP CONSTRAINT FK_8D8BA9C346EC494A');
        $this->addSql('ALTER TABLE orders_roll_printer DROP CONSTRAINT FK_8D8BA9C35D71C46D');
        $this->addSql('DROP TABLE orders_roll_printer');
        $this->addSql('ALTER TABLE orders_roll DROP roll_type');
        $this->addSql('ALTER TABLE orders_roll DROP lamination_types');
        $this->addSql('ALTER TABLE orders_order DROP CONSTRAINT FK_B4833C39AB0B6D26');
        $this->addSql('DROP INDEX IDX_B4833C39AB0B6D26');
        $this->addSql('ALTER TABLE orders_order DROP roll_id');
    }
}
