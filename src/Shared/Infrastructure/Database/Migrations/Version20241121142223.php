<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241121142223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_files ALTER owner_type TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE prod_process_printed_product ADD photo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE prod_process_printed_product ADD CONSTRAINT FK_453DEB627E9E4C8C FOREIGN KEY (photo_id) REFERENCES media_files (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_453DEB627E9E4C8C ON prod_process_printed_product (photo_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE prod_process_printed_product DROP CONSTRAINT FK_453DEB627E9E4C8C');
        $this->addSql('DROP INDEX UNIQ_453DEB627E9E4C8C');
        $this->addSql('ALTER TABLE prod_process_printed_product DROP photo_id');
        $this->addSql('ALTER TABLE media_files ALTER owner_type TYPE VARCHAR(50)');
    }
}
