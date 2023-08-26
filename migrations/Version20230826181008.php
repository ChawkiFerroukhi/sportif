<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230826181008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Acteur (id INT AUTO_INCREMENT NOT NULL, poste INT DEFAULT NULL, createdAt DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updatedAt DATETIME NOT NULL, nom VARCHAR(191) NOT NULL, prenom VARCHAR(191) NOT NULL, clubId INT DEFAULT NULL, INDEX IDX_ED56D6547C890FAB (poste), INDEX IDX_ED56D6541FB658F6 (clubId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Acteur ADD CONSTRAINT FK_ED56D6547C890FAB FOREIGN KEY (poste) REFERENCES Poste (id)');
        $this->addSql('ALTER TABLE Acteur ADD CONSTRAINT FK_ED56D6541FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Administrateur DROP FOREIGN KEY FK_FF8F2A307C890FAB');
        $this->addSql('DROP INDEX IDX_FF8F2A307C890FAB ON Administrateur');
        $this->addSql('ALTER TABLE Administrateur DROP poste');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Acteur DROP FOREIGN KEY FK_ED56D6547C890FAB');
        $this->addSql('ALTER TABLE Acteur DROP FOREIGN KEY FK_ED56D6541FB658F6');
        $this->addSql('DROP TABLE Acteur');
        $this->addSql('ALTER TABLE Administrateur ADD poste INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Administrateur ADD CONSTRAINT FK_FF8F2A307C890FAB FOREIGN KEY (poste) REFERENCES Poste (id)');
        $this->addSql('CREATE INDEX IDX_FF8F2A307C890FAB ON Administrateur (poste)');
    }
}
