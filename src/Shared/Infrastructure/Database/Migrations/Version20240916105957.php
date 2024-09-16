<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240916105957 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE inventory_history_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE inventory_history (id INT NOT NULL, film_id INT NOT NULL, film_type VARCHAR(255) NOT NULL, event_type VARCHAR(255) NOT NULL, change_amount NUMERIC(15, 2) NOT NULL, remaining_size NUMERIC(6, 2) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN inventory_history.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE orders_order ALTER length TYPE NUMERIC(15, 2)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE inventory_history_id_seq CASCADE');
        $this->addSql('DROP TABLE inventory_history');
        $this->addSql('ALTER TABLE orders_order ALTER length TYPE BIGINT');
    }
}
