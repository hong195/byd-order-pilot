<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240812154307 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE orders_roll_order (roll_id INT NOT NULL, order_id INT NOT NULL, PRIMARY KEY(roll_id, order_id))');
        $this->addSql('CREATE INDEX IDX_F2AF27E3AB0B6D26 ON orders_roll_order (roll_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F2AF27E38D9F6D38 ON orders_roll_order (order_id)');
        $this->addSql('ALTER TABLE orders_roll_order ADD CONSTRAINT FK_F2AF27E3AB0B6D26 FOREIGN KEY (roll_id) REFERENCES orders_roll (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders_roll_order ADD CONSTRAINT FK_F2AF27E38D9F6D38 FOREIGN KEY (order_id) REFERENCES orders_order (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders_order DROP CONSTRAINT fk_b4833c39ab0b6d26');
        $this->addSql('DROP INDEX idx_b4833c39ab0b6d26');
        $this->addSql('ALTER TABLE orders_order DROP roll_id');
        $this->addSql('ALTER TABLE orders_roll ALTER status DROP NOT NULL');
        $this->addSql('ALTER TABLE orders_roll ALTER film_id SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE orders_roll_order DROP CONSTRAINT FK_F2AF27E3AB0B6D26');
        $this->addSql('ALTER TABLE orders_roll_order DROP CONSTRAINT FK_F2AF27E38D9F6D38');
        $this->addSql('DROP TABLE orders_roll_order');
        $this->addSql('ALTER TABLE orders_roll ALTER film_id DROP NOT NULL');
        $this->addSql('ALTER TABLE orders_roll ALTER status SET NOT NULL');
        $this->addSql('ALTER TABLE orders_order ADD roll_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders_order ADD CONSTRAINT fk_b4833c39ab0b6d26 FOREIGN KEY (roll_id) REFERENCES orders_roll (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_b4833c39ab0b6d26 ON orders_order (roll_id)');
    }
}
