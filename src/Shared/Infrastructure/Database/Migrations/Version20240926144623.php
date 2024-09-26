<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240926144623 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE prod_process_job_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE prod_process_printed_product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE prod_process_printed_product (id INT NOT NULL, roll_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, length NUMERIC(15, 2) DEFAULT NULL, lamination_type VARCHAR(255) DEFAULT NULL, film_type VARCHAR(255) DEFAULT NULL, has_priority BOOLEAN DEFAULT NULL, sort_order INT DEFAULT 0, date_added TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_453DEB62AB0B6D26 ON prod_process_printed_product (roll_id)');
        $this->addSql('COMMENT ON COLUMN prod_process_printed_product.date_added IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE prod_process_printed_product ADD CONSTRAINT FK_453DEB62AB0B6D26 FOREIGN KEY (roll_id) REFERENCES prod_process_roll (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prod_process_job DROP CONSTRAINT fk_6b76934c8d9f6d38');
        $this->addSql('DROP TABLE prod_process_job');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE prod_process_printed_product_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE prod_process_job_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE prod_process_job (id INT NOT NULL, order_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, length NUMERIC(15, 2) DEFAULT NULL, lamination_type VARCHAR(255) DEFAULT NULL, film_type VARCHAR(255) DEFAULT NULL, has_priority BOOLEAN DEFAULT NULL, sort_order INT DEFAULT 0, date_added TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_6b76934c8d9f6d38 ON prod_process_job (order_id)');
        $this->addSql('COMMENT ON COLUMN prod_process_job.date_added IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE prod_process_job ADD CONSTRAINT fk_6b76934c8d9f6d38 FOREIGN KEY (order_id) REFERENCES prod_process_roll (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prod_process_printed_product DROP CONSTRAINT FK_453DEB62AB0B6D26');
        $this->addSql('DROP TABLE prod_process_printed_product');
    }
}
