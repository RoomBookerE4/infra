<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220107135415 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE User ADD establishment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE User ADD CONSTRAINT FK_2DA179778565851 FOREIGN KEY (establishment_id) REFERENCES Establishment (id)');
        $this->addSql('CREATE INDEX IDX_2DA179778565851 ON User (establishment_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE User DROP FOREIGN KEY FK_2DA179778565851');
        $this->addSql('DROP INDEX IDX_2DA179778565851 ON User');
        $this->addSql('ALTER TABLE User DROP establishment_id');
    }
}
