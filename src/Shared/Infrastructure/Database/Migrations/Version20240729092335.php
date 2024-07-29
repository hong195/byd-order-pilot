<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240729092335 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE order_stacks_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE order_stacks (id INT NOT NULL, name VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, lamination_type VARCHAR(255) DEFAULT NULL, roll_type VARCHAR(255) DEFAULT NULL, priority INT NOT NULL, date_added TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT fk_e52ffdee9d3fdaa8');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT fk_e52ffdeeb6a86cb2');
        $this->addSql('DROP INDEX uniq_e52ffdeeb6a86cb2');
        $this->addSql('DROP INDEX uniq_e52ffdee9d3fdaa8');
        $this->addSql('ALTER TABLE orders DROP cut_file_id');
        $this->addSql('ALTER TABLE orders DROP print_file_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE order_stacks_id_seq CASCADE');
        $this->addSql('DROP TABLE order_stacks');
        $this->addSql('ALTER TABLE orders ADD cut_file_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD print_file_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT fk_e52ffdee9d3fdaa8 FOREIGN KEY (cut_file_id) REFERENCES media_files (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT fk_e52ffdeeb6a86cb2 FOREIGN KEY (print_file_id) REFERENCES media_files (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_e52ffdeeb6a86cb2 ON orders (print_file_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_e52ffdee9d3fdaa8 ON orders (cut_file_id)');
    }
}
