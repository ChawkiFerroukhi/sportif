<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230711155524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Presence (id INT AUTO_INCREMENT NOT NULL, createdAt DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updatedAt DATETIME NOT NULL, date DATETIME NOT NULL, adherantId INT DEFAULT NULL, clubId INT DEFAULT NULL, INDEX clubId (clubId), INDEX adherantId (adherantId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Presence ADD CONSTRAINT FK_9001A5F377DEC5A FOREIGN KEY (adherantId) REFERENCES Adherant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Presence ADD CONSTRAINT FK_9001A5F31FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Presence DROP FOREIGN KEY FK_9001A5F377DEC5A');
        $this->addSql('ALTER TABLE Presence DROP FOREIGN KEY FK_9001A5F31FB658F6');
        $this->addSql('DROP TABLE Presence');
    }
}
