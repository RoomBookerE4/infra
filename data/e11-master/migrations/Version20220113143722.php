<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220113143722 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE Coordinate');
        $this->addSql('ALTER TABLE Participant CHANGE idReservation idReservation INT NOT NULL');
        $this->addSql('ALTER TABLE Room DROP floor, CHANGE timeOpen timeOpen DATETIME NOT NULL, CHANGE timeClose timeClose DATETIME NOT NULL, CHANGE maxTime maxTime VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE User ADD password_forgotten_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Coordinate (id INT NOT NULL, idRoom INT NOT NULL, x INT NOT NULL, y INT NOT NULL, `order` INT NOT NULL, INDEX idRoom (idRoom), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE Participant CHANGE idReservation idReservation INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Room ADD floor INT NOT NULL, CHANGE timeOpen timeOpen TIME DEFAULT NULL, CHANGE timeClose timeClose TIME DEFAULT NULL, CHANGE maxTime maxTime TIME DEFAULT NULL');
        $this->addSql('ALTER TABLE User DROP password_forgotten_at');
    }
}
