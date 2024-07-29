<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240729124242 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_stacks_orders (order_id INT NOT NULL, order_stack_id INT NOT NULL, PRIMARY KEY(order_id, order_stack_id))');
        $this->addSql('CREATE INDEX IDX_54761E038D9F6D38 ON order_stacks_orders (order_id)');
        $this->addSql('CREATE INDEX IDX_54761E03D63FE2D1 ON order_stacks_orders (order_stack_id)');
        $this->addSql('ALTER TABLE order_stacks_orders ADD CONSTRAINT FK_54761E038D9F6D38 FOREIGN KEY (order_id) REFERENCES order_stacks (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_stacks_orders ADD CONSTRAINT FK_54761E03D63FE2D1 FOREIGN KEY (order_stack_id) REFERENCES orders (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE order_stacks_orders DROP CONSTRAINT FK_54761E038D9F6D38');
        $this->addSql('ALTER TABLE order_stacks_orders DROP CONSTRAINT FK_54761E03D63FE2D1');
        $this->addSql('DROP TABLE order_stacks_orders');
    }
}
