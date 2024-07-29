<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240729095201 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders ADD cut_file_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD print_file_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE9D3FDAA8 FOREIGN KEY (cut_file_id) REFERENCES media_files (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEB6A86CB2 FOREIGN KEY (print_file_id) REFERENCES media_files (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E52FFDEE9D3FDAA8 ON orders (cut_file_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E52FFDEEB6A86CB2 ON orders (print_file_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT FK_E52FFDEE9D3FDAA8');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT FK_E52FFDEEB6A86CB2');
        $this->addSql('DROP INDEX UNIQ_E52FFDEE9D3FDAA8');
        $this->addSql('DROP INDEX UNIQ_E52FFDEEB6A86CB2');
        $this->addSql('ALTER TABLE orders DROP cut_file_id');
        $this->addSql('ALTER TABLE orders DROP print_file_id');
    }
}
