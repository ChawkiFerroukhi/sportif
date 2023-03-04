<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230302150845 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Adherant DROP FOREIGN KEY FK_6EAC3AEA70FC31E0');
        $this->addSql('DROP INDEX fk_6eac3aea70fc31e0 ON Adherant');
        $this->addSql('CREATE INDEX IDX_6EAC3AEA3AC4B029 ON Adherant (dossier_medicalId)');
        $this->addSql('ALTER TABLE Adherant ADD CONSTRAINT FK_6EAC3AEA70FC31E0 FOREIGN KEY (dossier_medicalId) REFERENCES DossierMedical (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Adherant DROP FOREIGN KEY FK_6EAC3AEA3AC4B029');
        $this->addSql('ALTER TABLE Adherant CHANGE createdAt createdAt DATETIME NOT NULL, CHANGE maladie maladie VARCHAR(191) DEFAULT NULL');
        $this->addSql('DROP INDEX idx_6eac3aea3ac4b029 ON Adherant');
        $this->addSql('CREATE INDEX FK_6EAC3AEA70FC31E0 ON Adherant (dossier_medicalId)');
        $this->addSql('ALTER TABLE Adherant ADD CONSTRAINT FK_6EAC3AEA3AC4B029 FOREIGN KEY (dossier_medicalId) REFERENCES DossierMedical (id)');
        $this->addSql('ALTER TABLE Administrateur CHANGE createdAt createdAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE Categorie CHANGE createdAt createdAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE Club CHANGE createdAt createdAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE Coach CHANGE createdAt createdAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE Cours CHANGE createdAt createdAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE Cycle CHANGE createdAt createdAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE DemeCategorie CHANGE createdAt createdAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE Doctor CHANGE createdAt createdAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE DossierMedical CHANGE createdAt createdAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE Equipe CHANGE createdAt createdAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE Master CHANGE createdAt createdAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE Mesure CHANGE createdAt createdAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE Niveau CHANGE createdAt createdAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE Note CHANGE createdAt createdAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE Objectif CHANGE createdAt createdAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE Seance CHANGE createdAt createdAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE Section CHANGE createdAt createdAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE Supervisor CHANGE createdAt createdAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE Teste CHANGE createdAt createdAt DATETIME NOT NULL');
    }
}
