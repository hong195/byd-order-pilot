<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240809125833 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders_roll_printer DROP CONSTRAINT fk_8d8ba9c346ec494a');
        $this->addSql('ALTER TABLE orders_roll_printer DROP CONSTRAINT fk_8d8ba9c35d71c46d');
        $this->addSql('DROP TABLE orders_roll_printer');
        $this->addSql('ALTER TABLE orders_roll ADD printer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders_roll ADD CONSTRAINT FK_8060F36546EC494A FOREIGN KEY (printer_id) REFERENCES orders_printer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8060F36546EC494A ON orders_roll (printer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE orders_roll_printer (printer_id INT NOT NULL, order_roll_id INT NOT NULL, PRIMARY KEY(printer_id, order_roll_id))');
        $this->addSql('CREATE INDEX idx_8d8ba9c35d71c46d ON orders_roll_printer (order_roll_id)');
        $this->addSql('CREATE INDEX idx_8d8ba9c346ec494a ON orders_roll_printer (printer_id)');
        $this->addSql('ALTER TABLE orders_roll_printer ADD CONSTRAINT fk_8d8ba9c346ec494a FOREIGN KEY (printer_id) REFERENCES orders_roll (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders_roll_printer ADD CONSTRAINT fk_8d8ba9c35d71c46d FOREIGN KEY (order_roll_id) REFERENCES orders_printer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders_roll DROP CONSTRAINT FK_8060F36546EC494A');
        $this->addSql('DROP INDEX IDX_8060F36546EC494A');
        $this->addSql('ALTER TABLE orders_roll DROP printer_id');
    }
}
