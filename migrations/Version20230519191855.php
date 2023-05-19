<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230519191855 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Adherant DROP FOREIGN KEY FK_6EAC3AEA9663D2C8');
        $this->addSql('ALTER TABLE Adherant DROP FOREIGN KEY FK_6EAC3AEA94F96677');
        $this->addSql('ALTER TABLE Categorie DROP FOREIGN KEY FK_CB8C54971FB658F6');
        $this->addSql('ALTER TABLE DemeCategorie DROP FOREIGN KEY FK_43BAF2951FB658F6');
        $this->addSql('DROP TABLE Categorie');
        $this->addSql('DROP TABLE DemeCategorie');
        $this->addSql('DROP INDEX categrieId ON Adherant');
        $this->addSql('DROP INDEX Deme_categorieId ON Adherant');
        $this->addSql('ALTER TABLE Adherant DROP categrieId, DROP Deme_categorieId');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Categorie (id INT AUTO_INCREMENT NOT NULL, createdAt DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updatedAt DATETIME NOT NULL, nom VARCHAR(191) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, clubId INT DEFAULT NULL, INDEX clubId (clubId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE DemeCategorie (id INT AUTO_INCREMENT NOT NULL, createdAt DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updatedAt DATETIME NOT NULL, nom VARCHAR(191) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, clubId INT DEFAULT NULL, INDEX clubId (clubId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE Categorie ADD CONSTRAINT FK_CB8C54971FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE DemeCategorie ADD CONSTRAINT FK_43BAF2951FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Adherant ADD categrieId INT DEFAULT NULL, ADD Deme_categorieId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Adherant ADD CONSTRAINT FK_6EAC3AEA9663D2C8 FOREIGN KEY (categrieId) REFERENCES Categorie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Adherant ADD CONSTRAINT FK_6EAC3AEA94F96677 FOREIGN KEY (Deme_categorieId) REFERENCES DemeCategorie (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX categrieId ON Adherant (categrieId)');
        $this->addSql('CREATE INDEX Deme_categorieId ON Adherant (Deme_categorieId)');
    }
}
