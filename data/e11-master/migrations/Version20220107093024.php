<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220107093024 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Establishment CHANGE address address VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE Participant CHANGE idReservation idReservation INT DEFAULT NULL, CHANGE idUser idUser INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Reservation CHANGE idRoom idRoom INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Room CHANGE name name VARCHAR(255) DEFAULT NULL, CHANGE maxTime maxTime VARCHAR(255) DEFAULT NULL, CHANGE idEstablishment idEstablishment INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Establishment CHANGE address address VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE Participant CHANGE idReservation idReservation INT DEFAULT NULL, CHANGE idUser idUser INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Reservation CHANGE idRoom idRoom INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Room CHANGE name name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE maxTime maxTime VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE idEstablishment idEstablishment INT DEFAULT NULL');
    }
}
