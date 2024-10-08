<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241008173018 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE prod_process_error_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE prod_process_error (id INT NOT NULL, printed_product_id INT NOT NULL, process VARCHAR(255) NOT NULL, responsible_employee_id INT NOT NULL, noticer_id INT NOT NULL, reason TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX printed_product_id_index ON prod_process_error (printed_product_id)');
        $this->addSql('CREATE INDEX process_index ON prod_process_error (process)');
        $this->addSql('CREATE INDEX noticer_id_index ON prod_process_error (noticer_id)');
        $this->addSql('CREATE INDEX createdAt_index ON prod_process_error (created_at)');
        $this->addSql('COMMENT ON COLUMN prod_process_error.created_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE prod_process_error_id_seq CASCADE');
        $this->addSql('DROP TABLE prod_process_error');
    }
}
