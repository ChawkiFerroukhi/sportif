<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230422161421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Adherant (id INT AUTO_INCREMENT NOT NULL, createdAt DATETIME DEFAULT \'current_timestamp(3)\' NOT NULL, updatedAt DATETIME NOT NULL, nom VARCHAR(191) NOT NULL, prenom VARCHAR(191) NOT NULL, birthDate DATE NOT NULL, birthPlace VARCHAR(191) NOT NULL, niveau_scolaire VARCHAR(191) NOT NULL, ecole VARCHAR(191) NOT NULL, num_tel VARCHAR(191) NOT NULL, licence VARCHAR(191) NOT NULL, sexe VARCHAR(191) NOT NULL, maladie VARCHAR(191) NOT NULL, dossier_medicalId INT DEFAULT NULL, categrieId INT DEFAULT NULL, equipeId INT DEFAULT NULL, Deme_categorieId INT DEFAULT NULL, clubId INT DEFAULT NULL, supervisorId INT DEFAULT NULL, INDEX IDX_6EAC3AEA3AC4B029 (dossier_medicalId), INDEX equipeId (equipeId), INDEX categrieId (categrieId), INDEX clubId (clubId), INDEX Deme_categorieId (Deme_categorieId), INDEX supervisorId (supervisorId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Administrateur (id INT AUTO_INCREMENT NOT NULL, createdAt DATETIME DEFAULT \'current_timestamp(3)\' NOT NULL, updatedAt DATETIME NOT NULL, nom VARCHAR(191) NOT NULL, prenom VARCHAR(191) NOT NULL, num_tel VARCHAR(191) NOT NULL, CIN VARCHAR(191) NOT NULL, adresse VARCHAR(191) NOT NULL, clubId INT DEFAULT NULL, INDEX clubId (clubId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Categorie (id INT AUTO_INCREMENT NOT NULL, createdAt DATETIME DEFAULT \'current_timestamp(3)\' NOT NULL, updatedAt DATETIME NOT NULL, nom VARCHAR(191) NOT NULL, clubId INT DEFAULT NULL, INDEX clubId (clubId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Club (id INT AUTO_INCREMENT NOT NULL, createdAt DATETIME DEFAULT \'current_timestamp(3)\' NOT NULL, updatedAt DATETIME NOT NULL, nom VARCHAR(191) NOT NULL, adresse VARCHAR(191) NOT NULL, num_tel VARCHAR(191) NOT NULL, date_fondation DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Coach (id INT AUTO_INCREMENT NOT NULL, createdAt DATETIME DEFAULT \'current_timestamp(3)\' NOT NULL, updatedAt DATETIME NOT NULL, nom VARCHAR(191) NOT NULL, prenom VARCHAR(191) NOT NULL, num_tel VARCHAR(191) NOT NULL, CIN VARCHAR(191) NOT NULL, adresse VARCHAR(191) NOT NULL, clubId INT DEFAULT NULL, INDEX clubId (clubId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Cours (id INT AUTO_INCREMENT NOT NULL, createdAt DATETIME DEFAULT \'current_timestamp(3)\' NOT NULL, updatedAt DATETIME NOT NULL, nom VARCHAR(191) NOT NULL, clubId INT DEFAULT NULL, niveauId INT DEFAULT NULL, INDEX niveauId (niveauId), INDEX clubId (clubId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Cycle (id INT AUTO_INCREMENT NOT NULL, createdAt DATETIME DEFAULT \'current_timestamp(3)\' NOT NULL, updatedAt DATETIME NOT NULL, nom VARCHAR(191) NOT NULL, coursId INT DEFAULT NULL, clubId INT DEFAULT NULL, INDEX coursId (coursId), INDEX clubId (clubId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE DemeCategorie (id INT AUTO_INCREMENT NOT NULL, createdAt DATETIME DEFAULT \'current_timestamp(3)\' NOT NULL, updatedAt DATETIME NOT NULL, nom VARCHAR(191) NOT NULL, clubId INT DEFAULT NULL, INDEX clubId (clubId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Doctor (id INT AUTO_INCREMENT NOT NULL, createdAt DATETIME DEFAULT \'current_timestamp(3)\' NOT NULL, updatedAt DATETIME NOT NULL, nom VARCHAR(191) NOT NULL, prenom VARCHAR(191) NOT NULL, num_tel VARCHAR(191) NOT NULL, CIN VARCHAR(191) NOT NULL, adresse VARCHAR(191) NOT NULL, clubId INT DEFAULT NULL, INDEX clubId (clubId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE DossierMedical (id INT AUTO_INCREMENT NOT NULL, createdAt DATETIME DEFAULT \'current_timestamp(3)\' NOT NULL, updatedAt DATETIME NOT NULL, clubId INT DEFAULT NULL, adherantId INT DEFAULT NULL, INDEX clubId (clubId), UNIQUE INDEX DossierMedical_adherantId_unique (adherantId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Equipe (id INT AUTO_INCREMENT NOT NULL, createdAt DATETIME DEFAULT \'current_timestamp(3)\' NOT NULL, updatedAt DATETIME NOT NULL, nom VARCHAR(191) NOT NULL, niveauId INT DEFAULT NULL, doctorId INT DEFAULT NULL, clubId INT DEFAULT NULL, coachId INT DEFAULT NULL, INDEX niveauId (niveauId), INDEX clubId (clubId), INDEX doctorId (doctorId), INDEX coachId (coachId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Master (id INT AUTO_INCREMENT NOT NULL, createdAt DATETIME DEFAULT \'current_timestamp(3)\' NOT NULL, updatedAt DATETIME NOT NULL, nom VARCHAR(191) NOT NULL, prenom VARCHAR(191) NOT NULL, num_tel VARCHAR(191) NOT NULL, CIN VARCHAR(191) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Mesure (id INT AUTO_INCREMENT NOT NULL, createdAt DATETIME DEFAULT \'current_timestamp(3)\' NOT NULL, updatedAt DATETIME NOT NULL, date DATETIME NOT NULL, poids DOUBLE PRECISION NOT NULL, taille DOUBLE PRECISION NOT NULL, poitrine DOUBLE PRECISION NOT NULL, cuisse DOUBLE PRECISION NOT NULL, biceps DOUBLE PRECISION NOT NULL, age INT NOT NULL, imc DOUBLE PRECISION NOT NULL, diagnostic VARCHAR(191) NOT NULL, doctorId INT DEFAULT NULL, clubId INT DEFAULT NULL, dossier_medicalId INT DEFAULT NULL, INDEX dossier_medicalId (dossier_medicalId), INDEX doctorId (doctorId), INDEX clubId (clubId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Niveau (id INT AUTO_INCREMENT NOT NULL, createdAt DATETIME DEFAULT \'current_timestamp(3)\' NOT NULL, updatedAt DATETIME NOT NULL, nom VARCHAR(191) NOT NULL, clubId INT DEFAULT NULL, sectionId INT DEFAULT NULL, INDEX sectionId (sectionId), INDEX clubId (clubId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Note (id INT AUTO_INCREMENT NOT NULL, createdAt DATETIME DEFAULT \'current_timestamp(3)\' NOT NULL, updatedAt DATETIME NOT NULL, note DOUBLE PRECISION NOT NULL, objectifId INT DEFAULT NULL, testeId INT DEFAULT NULL, clubId INT DEFAULT NULL, adherantId INT DEFAULT NULL, INDEX objectifId (objectifId), INDEX clubId (clubId), INDEX testeId (testeId), INDEX adherantId (adherantId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Objectif (id INT AUTO_INCREMENT NOT NULL, createdAt DATETIME DEFAULT \'current_timestamp(3)\' NOT NULL, updatedAt DATETIME NOT NULL, nom VARCHAR(191) NOT NULL, cycleId INT DEFAULT NULL, clubId INT DEFAULT NULL, INDEX cycleId (cycleId), INDEX clubId (clubId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Seance (id INT AUTO_INCREMENT NOT NULL, createdAt DATETIME DEFAULT \'current_timestamp(3)\' NOT NULL, updatedAt DATETIME NOT NULL, date DATETIME NOT NULL, equipeId INT DEFAULT NULL, cycleId INT DEFAULT NULL, clubId INT DEFAULT NULL, INDEX clubId (clubId), INDEX equipeId (equipeId), INDEX cycleId (cycleId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Section (id INT AUTO_INCREMENT NOT NULL, createdAt DATETIME DEFAULT \'current_timestamp(3)\' NOT NULL, updatedAt DATETIME NOT NULL, nom VARCHAR(191) NOT NULL, clubId INT DEFAULT NULL, INDEX clubId (clubId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Supervisor (id INT AUTO_INCREMENT NOT NULL, createdAt DATETIME DEFAULT \'current_timestamp(3)\' NOT NULL, updatedAt DATETIME NOT NULL, nom VARCHAR(191) NOT NULL, prenom VARCHAR(191) NOT NULL, num_tel VARCHAR(191) NOT NULL, CIN VARCHAR(191) NOT NULL, adresse VARCHAR(191) NOT NULL, clubId INT DEFAULT NULL, INDEX clubId (clubId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Teste (id INT AUTO_INCREMENT NOT NULL, createdAt DATETIME DEFAULT \'current_timestamp(3)\' NOT NULL, updatedAt DATETIME NOT NULL, date DATETIME NOT NULL, nom VARCHAR(191) NOT NULL, equipeId INT DEFAULT NULL, clubId INT DEFAULT NULL, INDEX equipeId (equipeId), INDEX clubId (clubId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Adherant ADD CONSTRAINT FK_6EAC3AEA3AC4B029 FOREIGN KEY (dossier_medicalId) REFERENCES DossierMedical (id)');
        $this->addSql('ALTER TABLE Adherant ADD CONSTRAINT FK_6EAC3AEA9663D2C8 FOREIGN KEY (categrieId) REFERENCES Categorie (id)');
        $this->addSql('ALTER TABLE Adherant ADD CONSTRAINT FK_6EAC3AEA36BCDB3D FOREIGN KEY (equipeId) REFERENCES Equipe (id)');
        $this->addSql('ALTER TABLE Adherant ADD CONSTRAINT FK_6EAC3AEA94F96677 FOREIGN KEY (Deme_categorieId) REFERENCES DemeCategorie (id)');
        $this->addSql('ALTER TABLE Adherant ADD CONSTRAINT FK_6EAC3AEA1FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id)');
        $this->addSql('ALTER TABLE Adherant ADD CONSTRAINT FK_6EAC3AEAE8D997BE FOREIGN KEY (supervisorId) REFERENCES Supervisor (id)');
        $this->addSql('ALTER TABLE Administrateur ADD CONSTRAINT FK_FF8F2A301FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id)');
        $this->addSql('ALTER TABLE Categorie ADD CONSTRAINT FK_CB8C54971FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id)');
        $this->addSql('ALTER TABLE Coach ADD CONSTRAINT FK_FE9842C81FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id)');
        $this->addSql('ALTER TABLE Cours ADD CONSTRAINT FK_3C0BA3981FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id)');
        $this->addSql('ALTER TABLE Cours ADD CONSTRAINT FK_3C0BA398880019E7 FOREIGN KEY (niveauId) REFERENCES Niveau (id)');
        $this->addSql('ALTER TABLE Cycle ADD CONSTRAINT FK_7147FE971347B425 FOREIGN KEY (coursId) REFERENCES Cours (id)');
        $this->addSql('ALTER TABLE Cycle ADD CONSTRAINT FK_7147FE971FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id)');
        $this->addSql('ALTER TABLE DemeCategorie ADD CONSTRAINT FK_43BAF2951FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id)');
        $this->addSql('ALTER TABLE Doctor ADD CONSTRAINT FK_186CF65C1FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id)');
        $this->addSql('ALTER TABLE DossierMedical ADD CONSTRAINT FK_6440F5B41FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id)');
        $this->addSql('ALTER TABLE DossierMedical ADD CONSTRAINT FK_6440F5B477DEC5A FOREIGN KEY (adherantId) REFERENCES Adherant (id)');
        $this->addSql('ALTER TABLE Equipe ADD CONSTRAINT FK_23E5BF23880019E7 FOREIGN KEY (niveauId) REFERENCES Niveau (id)');
        $this->addSql('ALTER TABLE Equipe ADD CONSTRAINT FK_23E5BF23911B7CB9 FOREIGN KEY (doctorId) REFERENCES Doctor (id)');
        $this->addSql('ALTER TABLE Equipe ADD CONSTRAINT FK_23E5BF231FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id)');
        $this->addSql('ALTER TABLE Equipe ADD CONSTRAINT FK_23E5BF237EF1F90C FOREIGN KEY (coachId) REFERENCES Coach (id)');
        $this->addSql('ALTER TABLE Mesure ADD CONSTRAINT FK_58B76B46911B7CB9 FOREIGN KEY (doctorId) REFERENCES Doctor (id)');
        $this->addSql('ALTER TABLE Mesure ADD CONSTRAINT FK_58B76B461FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id)');
        $this->addSql('ALTER TABLE Mesure ADD CONSTRAINT FK_58B76B463AC4B029 FOREIGN KEY (dossier_medicalId) REFERENCES DossierMedical (id)');
        $this->addSql('ALTER TABLE Niveau ADD CONSTRAINT FK_4C73F65D1FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id)');
        $this->addSql('ALTER TABLE Niveau ADD CONSTRAINT FK_4C73F65D438B1980 FOREIGN KEY (sectionId) REFERENCES Section (id)');
        $this->addSql('ALTER TABLE Note ADD CONSTRAINT FK_6F8F552ACA742475 FOREIGN KEY (objectifId) REFERENCES Objectif (id)');
        $this->addSql('ALTER TABLE Note ADD CONSTRAINT FK_6F8F552AA22540BD FOREIGN KEY (testeId) REFERENCES Teste (id)');
        $this->addSql('ALTER TABLE Note ADD CONSTRAINT FK_6F8F552A1FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id)');
        $this->addSql('ALTER TABLE Note ADD CONSTRAINT FK_6F8F552A77DEC5A FOREIGN KEY (adherantId) REFERENCES Adherant (id)');
        $this->addSql('ALTER TABLE Objectif ADD CONSTRAINT FK_1B8E0A078105C8EF FOREIGN KEY (cycleId) REFERENCES Cycle (id)');
        $this->addSql('ALTER TABLE Objectif ADD CONSTRAINT FK_1B8E0A071FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id)');
        $this->addSql('ALTER TABLE Seance ADD CONSTRAINT FK_D8D1F83836BCDB3D FOREIGN KEY (equipeId) REFERENCES Equipe (id)');
        $this->addSql('ALTER TABLE Seance ADD CONSTRAINT FK_D8D1F8388105C8EF FOREIGN KEY (cycleId) REFERENCES Cycle (id)');
        $this->addSql('ALTER TABLE Seance ADD CONSTRAINT FK_D8D1F8381FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id)');
        $this->addSql('ALTER TABLE Section ADD CONSTRAINT FK_E2CE43731FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id)');
        $this->addSql('ALTER TABLE Supervisor ADD CONSTRAINT FK_2CC91281FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id)');
        $this->addSql('ALTER TABLE Teste ADD CONSTRAINT FK_2775660B36BCDB3D FOREIGN KEY (equipeId) REFERENCES Equipe (id)');
        $this->addSql('ALTER TABLE Teste ADD CONSTRAINT FK_2775660B1FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Adherant DROP FOREIGN KEY FK_6EAC3AEA3AC4B029');
        $this->addSql('ALTER TABLE Adherant DROP FOREIGN KEY FK_6EAC3AEA9663D2C8');
        $this->addSql('ALTER TABLE Adherant DROP FOREIGN KEY FK_6EAC3AEA36BCDB3D');
        $this->addSql('ALTER TABLE Adherant DROP FOREIGN KEY FK_6EAC3AEA94F96677');
        $this->addSql('ALTER TABLE Adherant DROP FOREIGN KEY FK_6EAC3AEA1FB658F6');
        $this->addSql('ALTER TABLE Adherant DROP FOREIGN KEY FK_6EAC3AEAE8D997BE');
        $this->addSql('ALTER TABLE Administrateur DROP FOREIGN KEY FK_FF8F2A301FB658F6');
        $this->addSql('ALTER TABLE Categorie DROP FOREIGN KEY FK_CB8C54971FB658F6');
        $this->addSql('ALTER TABLE Coach DROP FOREIGN KEY FK_FE9842C81FB658F6');
        $this->addSql('ALTER TABLE Cours DROP FOREIGN KEY FK_3C0BA3981FB658F6');
        $this->addSql('ALTER TABLE Cours DROP FOREIGN KEY FK_3C0BA398880019E7');
        $this->addSql('ALTER TABLE Cycle DROP FOREIGN KEY FK_7147FE971347B425');
        $this->addSql('ALTER TABLE Cycle DROP FOREIGN KEY FK_7147FE971FB658F6');
        $this->addSql('ALTER TABLE DemeCategorie DROP FOREIGN KEY FK_43BAF2951FB658F6');
        $this->addSql('ALTER TABLE Doctor DROP FOREIGN KEY FK_186CF65C1FB658F6');
        $this->addSql('ALTER TABLE DossierMedical DROP FOREIGN KEY FK_6440F5B41FB658F6');
        $this->addSql('ALTER TABLE DossierMedical DROP FOREIGN KEY FK_6440F5B477DEC5A');
        $this->addSql('ALTER TABLE Equipe DROP FOREIGN KEY FK_23E5BF23880019E7');
        $this->addSql('ALTER TABLE Equipe DROP FOREIGN KEY FK_23E5BF23911B7CB9');
        $this->addSql('ALTER TABLE Equipe DROP FOREIGN KEY FK_23E5BF231FB658F6');
        $this->addSql('ALTER TABLE Equipe DROP FOREIGN KEY FK_23E5BF237EF1F90C');
        $this->addSql('ALTER TABLE Mesure DROP FOREIGN KEY FK_58B76B46911B7CB9');
        $this->addSql('ALTER TABLE Mesure DROP FOREIGN KEY FK_58B76B461FB658F6');
        $this->addSql('ALTER TABLE Mesure DROP FOREIGN KEY FK_58B76B463AC4B029');
        $this->addSql('ALTER TABLE Niveau DROP FOREIGN KEY FK_4C73F65D1FB658F6');
        $this->addSql('ALTER TABLE Niveau DROP FOREIGN KEY FK_4C73F65D438B1980');
        $this->addSql('ALTER TABLE Note DROP FOREIGN KEY FK_6F8F552ACA742475');
        $this->addSql('ALTER TABLE Note DROP FOREIGN KEY FK_6F8F552AA22540BD');
        $this->addSql('ALTER TABLE Note DROP FOREIGN KEY FK_6F8F552A1FB658F6');
        $this->addSql('ALTER TABLE Note DROP FOREIGN KEY FK_6F8F552A77DEC5A');
        $this->addSql('ALTER TABLE Objectif DROP FOREIGN KEY FK_1B8E0A078105C8EF');
        $this->addSql('ALTER TABLE Objectif DROP FOREIGN KEY FK_1B8E0A071FB658F6');
        $this->addSql('ALTER TABLE Seance DROP FOREIGN KEY FK_D8D1F83836BCDB3D');
        $this->addSql('ALTER TABLE Seance DROP FOREIGN KEY FK_D8D1F8388105C8EF');
        $this->addSql('ALTER TABLE Seance DROP FOREIGN KEY FK_D8D1F8381FB658F6');
        $this->addSql('ALTER TABLE Section DROP FOREIGN KEY FK_E2CE43731FB658F6');
        $this->addSql('ALTER TABLE Supervisor DROP FOREIGN KEY FK_2CC91281FB658F6');
        $this->addSql('ALTER TABLE Teste DROP FOREIGN KEY FK_2775660B36BCDB3D');
        $this->addSql('ALTER TABLE Teste DROP FOREIGN KEY FK_2775660B1FB658F6');
        $this->addSql('DROP TABLE Adherant');
        $this->addSql('DROP TABLE Administrateur');
        $this->addSql('DROP TABLE Categorie');
        $this->addSql('DROP TABLE Club');
        $this->addSql('DROP TABLE Coach');
        $this->addSql('DROP TABLE Cours');
        $this->addSql('DROP TABLE Cycle');
        $this->addSql('DROP TABLE DemeCategorie');
        $this->addSql('DROP TABLE Doctor');
        $this->addSql('DROP TABLE DossierMedical');
        $this->addSql('DROP TABLE Equipe');
        $this->addSql('DROP TABLE Master');
        $this->addSql('DROP TABLE Mesure');
        $this->addSql('DROP TABLE Niveau');
        $this->addSql('DROP TABLE Note');
        $this->addSql('DROP TABLE Objectif');
        $this->addSql('DROP TABLE Seance');
        $this->addSql('DROP TABLE Section');
        $this->addSql('DROP TABLE Supervisor');
        $this->addSql('DROP TABLE Teste');
    }
}
