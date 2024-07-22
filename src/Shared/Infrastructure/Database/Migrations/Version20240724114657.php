<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240724114657 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE media_files_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE orders_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE printers_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE media_files (id INT NOT NULL, filename VARCHAR(255) NOT NULL, source VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, type VARCHAR(50) NOT NULL, owner_id INT NOT NULL, owner_type VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE orders (id INT NOT NULL, cut_file_id INT DEFAULT NULL, print_file_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, lamination_type VARCHAR(255) DEFAULT NULL, roll_type VARCHAR(255) DEFAULT NULL, priority VARCHAR(255) DEFAULT NULL, product_type VARCHAR(255) NOT NULL, date_added TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E52FFDEE9D3FDAA8 ON orders (cut_file_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E52FFDEEB6A86CB2 ON orders (print_file_id)');
        $this->addSql('CREATE TABLE printers (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE9D3FDAA8 FOREIGN KEY (cut_file_id) REFERENCES media_files (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEB6A86CB2 FOREIGN KEY (print_file_id) REFERENCES media_files (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE media_files_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE orders_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE printers_id_seq CASCADE');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT FK_E52FFDEE9D3FDAA8');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT FK_E52FFDEEB6A86CB2');
        $this->addSql('DROP TABLE media_files');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE printers');
    }
}
