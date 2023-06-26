<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230626152538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Maladie (id INT AUTO_INCREMENT NOT NULL, createdAt DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updatedAt DATETIME NOT NULL, nom VARCHAR(191) NOT NULL, description VARCHAR(5000) DEFAULT NULL, clubId INT DEFAULT NULL, INDEX clubId (clubId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Maladie ADD CONSTRAINT FK_62793BD71FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Adherant CHANGE maladie maladie VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Maladie DROP FOREIGN KEY FK_62793BD71FB658F6');
        $this->addSql('DROP TABLE Maladie');
        $this->addSql('ALTER TABLE Adherant CHANGE maladie maladie VARCHAR(191) NOT NULL');
    }
}
