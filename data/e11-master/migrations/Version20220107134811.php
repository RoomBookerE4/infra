<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220107134811 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Establishment CHANGE timeOpen timeOpen DATETIME NOT NULL, CHANGE timeClose timeClose DATETIME NOT NULL');
        $this->addSql('ALTER TABLE Participant CHANGE idReservation idReservation INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Establishment CHANGE timeOpen timeOpen TIME NOT NULL, CHANGE timeClose timeClose TIME NOT NULL');
        $this->addSql('ALTER TABLE Participant CHANGE idReservation idReservation INT NOT NULL');
    }
}
