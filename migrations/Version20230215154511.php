<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230215154511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Supervisor (id INT AUTO_INCREMENT NOT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, nom VARCHAR(191) NOT NULL, prenom VARCHAR(191) NOT NULL, num_tel VARCHAR(191) NOT NULL, CIN VARCHAR(191) NOT NULL, adresse VARCHAR(191) NOT NULL, clubId INT DEFAULT NULL, INDEX clubId (clubId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Supervisor ADD CONSTRAINT FK_2CC91281FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id)');
        $this->addSql('ALTER TABLE Parent DROP FOREIGN KEY parent_ibfk_1');
        $this->addSql('DROP TABLE Parent');
        $this->addSql('DROP INDEX parentId ON Adherant');
        $this->addSql('ALTER TABLE Adherant ADD supervisorId INT DEFAULT NULL, DROP parentId, CHANGE categrieId categrieId INT DEFAULT NULL, CHANGE Deme_categorieId Deme_categorieId INT DEFAULT NULL, CHANGE equipeId equipeId INT DEFAULT NULL, CHANGE clubId clubId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Adherant ADD CONSTRAINT FK_6EAC3AEA9663D2C8 FOREIGN KEY (categrieId) REFERENCES Categorie (id)');
        $this->addSql('ALTER TABLE Adherant ADD CONSTRAINT FK_6EAC3AEA36BCDB3D FOREIGN KEY (equipeId) REFERENCES Equipe (id)');
        $this->addSql('ALTER TABLE Adherant ADD CONSTRAINT FK_6EAC3AEA94F96677 FOREIGN KEY (Deme_categorieId) REFERENCES DemeCategorie (id)');
        $this->addSql('ALTER TABLE Adherant ADD CONSTRAINT FK_6EAC3AEA1FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id)');
        $this->addSql('ALTER TABLE Adherant ADD CONSTRAINT FK_6EAC3AEAE8D997BE FOREIGN KEY (supervisorId) REFERENCES Supervisor (id)');
        $this->addSql('CREATE INDEX supervisorId ON Adherant (supervisorId)');
        $this->addSql('ALTER TABLE Administrateur DROP FOREIGN KEY administrateur_ibfk_1');
        $this->addSql('ALTER TABLE Administrateur CHANGE clubId clubId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Administrateur ADD CONSTRAINT FK_FF8F2A301FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id)');
        $this->addSql('ALTER TABLE Categorie DROP FOREIGN KEY categorie_ibfk_1');
        $this->addSql('ALTER TABLE Categorie CHANGE clubId clubId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Categorie ADD CONSTRAINT FK_CB8C54971FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id)');
        $this->addSql('ALTER TABLE Coach DROP FOREIGN KEY coach_ibfk_1');
        $this->addSql('ALTER TABLE Coach CHANGE clubId clubId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Coach ADD CONSTRAINT FK_FE9842C81FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id)');
        $this->addSql('ALTER TABLE Cours DROP FOREIGN KEY cours_ibfk_2');
        $this->addSql('ALTER TABLE Cours DROP FOREIGN KEY cours_ibfk_1');
        $this->addSql('ALTER TABLE Cours CHANGE niveauId niveauId INT DEFAULT NULL, CHANGE clubId clubId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Cours ADD CONSTRAINT FK_3C0BA3981FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id)');
        $this->addSql('ALTER TABLE Cours ADD CONSTRAINT FK_3C0BA398880019E7 FOREIGN KEY (niveauId) REFERENCES Niveau (id)');
        $this->addSql('ALTER TABLE Cycle DROP FOREIGN KEY cycle_ibfk_1');
        $this->addSql('ALTER TABLE Cycle DROP FOREIGN KEY cycle_ibfk_2');
        $this->addSql('ALTER TABLE Cycle CHANGE coursId coursId INT DEFAULT NULL, CHANGE clubId clubId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Cycle ADD CONSTRAINT FK_7147FE971347B425 FOREIGN KEY (coursId) REFERENCES Cours (id)');
        $this->addSql('ALTER TABLE Cycle ADD CONSTRAINT FK_7147FE971FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id)');
        $this->addSql('ALTER TABLE DemeCategorie DROP FOREIGN KEY demecategorie_ibfk_1');
        $this->addSql('ALTER TABLE DemeCategorie CHANGE clubId clubId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE DemeCategorie ADD CONSTRAINT FK_43BAF2951FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id)');
        $this->addSql('ALTER TABLE Doctor DROP FOREIGN KEY doctor_ibfk_1');
        $this->addSql('ALTER TABLE Doctor CHANGE clubId clubId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Doctor ADD CONSTRAINT FK_186CF65C1FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id)');
        $this->addSql('ALTER TABLE DossierMedical CHANGE adherantId adherantId INT DEFAULT NULL, CHANGE clubId clubId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE DossierMedical ADD CONSTRAINT FK_6440F5B41FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id)');
        $this->addSql('ALTER TABLE DossierMedical ADD CONSTRAINT FK_6440F5B477DEC5A FOREIGN KEY (adherantId) REFERENCES Adherant (id)');
        $this->addSql('DROP INDEX dossiermedical.adherantid_unique ON DossierMedical');
        $this->addSql('CREATE UNIQUE INDEX DossierMedical_adherantId_unique ON DossierMedical (adherantId)');
        $this->addSql('ALTER TABLE DossierMedical ADD CONSTRAINT dossiermedical_ibfk_1 FOREIGN KEY (adherantId) REFERENCES Adherant (id) ON UPDATE CASCADE');
        $this->addSql('ALTER TABLE Equipe DROP FOREIGN KEY equipe_ibfk_3');
        $this->addSql('ALTER TABLE Equipe DROP FOREIGN KEY equipe_ibfk_1');
        $this->addSql('ALTER TABLE Equipe DROP FOREIGN KEY equipe_ibfk_4');
        $this->addSql('ALTER TABLE Equipe DROP FOREIGN KEY equipe_ibfk_2');
        $this->addSql('ALTER TABLE Equipe CHANGE doctorId doctorId INT DEFAULT NULL, CHANGE coachId coachId INT DEFAULT NULL, CHANGE niveauId niveauId INT DEFAULT NULL, CHANGE clubId clubId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Equipe ADD CONSTRAINT FK_23E5BF23880019E7 FOREIGN KEY (niveauId) REFERENCES Niveau (id)');
        $this->addSql('ALTER TABLE Equipe ADD CONSTRAINT FK_23E5BF23911B7CB9 FOREIGN KEY (doctorId) REFERENCES Doctor (id)');
        $this->addSql('ALTER TABLE Equipe ADD CONSTRAINT FK_23E5BF231FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id)');
        $this->addSql('ALTER TABLE Equipe ADD CONSTRAINT FK_23E5BF237EF1F90C FOREIGN KEY (coachId) REFERENCES Coach (id)');
        $this->addSql('ALTER TABLE Mesure DROP FOREIGN KEY mesure_ibfk_2');
        $this->addSql('ALTER TABLE Mesure DROP FOREIGN KEY mesure_ibfk_3');
        $this->addSql('ALTER TABLE Mesure DROP FOREIGN KEY mesure_ibfk_1');
        $this->addSql('ALTER TABLE Mesure CHANGE dossier_medicalId dossier_medicalId INT DEFAULT NULL, CHANGE doctorId doctorId INT DEFAULT NULL, CHANGE clubId clubId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Mesure ADD CONSTRAINT FK_58B76B46911B7CB9 FOREIGN KEY (doctorId) REFERENCES Doctor (id)');
        $this->addSql('ALTER TABLE Mesure ADD CONSTRAINT FK_58B76B461FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id)');
        $this->addSql('ALTER TABLE Mesure ADD CONSTRAINT FK_58B76B463AC4B029 FOREIGN KEY (dossier_medicalId) REFERENCES DossierMedical (id)');
        $this->addSql('ALTER TABLE Niveau DROP FOREIGN KEY niveau_ibfk_2');
        $this->addSql('ALTER TABLE Niveau DROP FOREIGN KEY niveau_ibfk_1');
        $this->addSql('ALTER TABLE Niveau CHANGE sectionId sectionId INT DEFAULT NULL, CHANGE clubId clubId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Niveau ADD CONSTRAINT FK_4C73F65D1FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id)');
        $this->addSql('ALTER TABLE Niveau ADD CONSTRAINT FK_4C73F65D438B1980 FOREIGN KEY (sectionId) REFERENCES Section (id)');
        $this->addSql('ALTER TABLE Note DROP FOREIGN KEY note_ibfk_3');
        $this->addSql('ALTER TABLE Note DROP FOREIGN KEY note_ibfk_1');
        $this->addSql('ALTER TABLE Note DROP FOREIGN KEY note_ibfk_4');
        $this->addSql('ALTER TABLE Note DROP FOREIGN KEY note_ibfk_2');
        $this->addSql('ALTER TABLE Note CHANGE testeId testeId INT DEFAULT NULL, CHANGE adherantId adherantId INT DEFAULT NULL, CHANGE objectifId objectifId INT DEFAULT NULL, CHANGE clubId clubId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Note ADD CONSTRAINT FK_6F8F552ACA742475 FOREIGN KEY (objectifId) REFERENCES Objectif (id)');
        $this->addSql('ALTER TABLE Note ADD CONSTRAINT FK_6F8F552AA22540BD FOREIGN KEY (testeId) REFERENCES Teste (id)');
        $this->addSql('ALTER TABLE Note ADD CONSTRAINT FK_6F8F552A1FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id)');
        $this->addSql('ALTER TABLE Note ADD CONSTRAINT FK_6F8F552A77DEC5A FOREIGN KEY (adherantId) REFERENCES Adherant (id)');
        $this->addSql('ALTER TABLE Objectif DROP FOREIGN KEY objectif_ibfk_1');
        $this->addSql('ALTER TABLE Objectif DROP FOREIGN KEY objectif_ibfk_2');
        $this->addSql('ALTER TABLE Objectif CHANGE cycleId cycleId INT DEFAULT NULL, CHANGE clubId clubId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Objectif ADD CONSTRAINT FK_1B8E0A078105C8EF FOREIGN KEY (cycleId) REFERENCES Cycle (id)');
        $this->addSql('ALTER TABLE Objectif ADD CONSTRAINT FK_1B8E0A071FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id)');
        $this->addSql('ALTER TABLE Seance DROP FOREIGN KEY seance_ibfk_1');
        $this->addSql('ALTER TABLE Seance DROP FOREIGN KEY seance_ibfk_2');
        $this->addSql('ALTER TABLE Seance DROP FOREIGN KEY seance_ibfk_3');
        $this->addSql('ALTER TABLE Seance CHANGE equipeId equipeId INT DEFAULT NULL, CHANGE cycleId cycleId INT DEFAULT NULL, CHANGE clubId clubId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Seance ADD CONSTRAINT FK_D8D1F83836BCDB3D FOREIGN KEY (equipeId) REFERENCES Equipe (id)');
        $this->addSql('ALTER TABLE Seance ADD CONSTRAINT FK_D8D1F8388105C8EF FOREIGN KEY (cycleId) REFERENCES Cycle (id)');
        $this->addSql('ALTER TABLE Seance ADD CONSTRAINT FK_D8D1F8381FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id)');
        $this->addSql('ALTER TABLE Section DROP FOREIGN KEY section_ibfk_1');
        $this->addSql('ALTER TABLE Section CHANGE clubId clubId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Section ADD CONSTRAINT FK_E2CE43731FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id)');
        $this->addSql('ALTER TABLE Teste DROP FOREIGN KEY teste_ibfk_1');
        $this->addSql('ALTER TABLE Teste DROP FOREIGN KEY teste_ibfk_2');
        $this->addSql('ALTER TABLE Teste CHANGE equipeId equipeId INT DEFAULT NULL, CHANGE clubId clubId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Teste ADD CONSTRAINT FK_2775660B36BCDB3D FOREIGN KEY (equipeId) REFERENCES Equipe (id)');
        $this->addSql('ALTER TABLE Teste ADD CONSTRAINT FK_2775660B1FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Adherant DROP FOREIGN KEY FK_6EAC3AEAE8D997BE');
        $this->addSql('CREATE TABLE Parent (id INT AUTO_INCREMENT NOT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, nom VARCHAR(191) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, prenom VARCHAR(191) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, num_tel VARCHAR(191) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CIN VARCHAR(191) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, adresse VARCHAR(191) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, clubId INT NOT NULL, INDEX clubId (clubId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE Parent ADD CONSTRAINT parent_ibfk_1 FOREIGN KEY (clubId) REFERENCES Club (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Supervisor DROP FOREIGN KEY FK_2CC91281FB658F6');
        $this->addSql('DROP TABLE Supervisor');
        $this->addSql('ALTER TABLE Adherant DROP FOREIGN KEY FK_6EAC3AEA9663D2C8');
        $this->addSql('ALTER TABLE Adherant DROP FOREIGN KEY FK_6EAC3AEA36BCDB3D');
        $this->addSql('ALTER TABLE Adherant DROP FOREIGN KEY FK_6EAC3AEA94F96677');
        $this->addSql('ALTER TABLE Adherant DROP FOREIGN KEY FK_6EAC3AEA1FB658F6');
        $this->addSql('DROP INDEX supervisorId ON Adherant');
        $this->addSql('ALTER TABLE Adherant ADD parentId INT NOT NULL, DROP supervisorId, CHANGE categrieId categrieId INT NOT NULL, CHANGE equipeId equipeId INT NOT NULL, CHANGE Deme_categorieId Deme_categorieId INT NOT NULL, CHANGE clubId clubId INT NOT NULL');
        $this->addSql('ALTER TABLE Adherant ADD CONSTRAINT adherant_ibfk_1 FOREIGN KEY (categrieId) REFERENCES Categorie (id) ON UPDATE CASCADE');
        $this->addSql('ALTER TABLE Adherant ADD CONSTRAINT adherant_ibfk_4 FOREIGN KEY (equipeId) REFERENCES Equipe (id) ON UPDATE CASCADE');
        $this->addSql('ALTER TABLE Adherant ADD CONSTRAINT adherant_ibfk_2 FOREIGN KEY (Deme_categorieId) REFERENCES DemeCategorie (id) ON UPDATE CASCADE');
        $this->addSql('ALTER TABLE Adherant ADD CONSTRAINT adherant_ibfk_5 FOREIGN KEY (clubId) REFERENCES Club (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Adherant ADD CONSTRAINT adherant_ibfk_3 FOREIGN KEY (parentId) REFERENCES Parent (id) ON UPDATE CASCADE');
        $this->addSql('CREATE INDEX parentId ON Adherant (parentId)');
        $this->addSql('ALTER TABLE Administrateur DROP FOREIGN KEY FK_FF8F2A301FB658F6');
        $this->addSql('ALTER TABLE Administrateur CHANGE clubId clubId INT NOT NULL');
        $this->addSql('ALTER TABLE Administrateur ADD CONSTRAINT administrateur_ibfk_1 FOREIGN KEY (clubId) REFERENCES Club (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Categorie DROP FOREIGN KEY FK_CB8C54971FB658F6');
        $this->addSql('ALTER TABLE Categorie CHANGE clubId clubId INT NOT NULL');
        $this->addSql('ALTER TABLE Categorie ADD CONSTRAINT categorie_ibfk_1 FOREIGN KEY (clubId) REFERENCES Club (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Coach DROP FOREIGN KEY FK_FE9842C81FB658F6');
        $this->addSql('ALTER TABLE Coach CHANGE clubId clubId INT NOT NULL');
        $this->addSql('ALTER TABLE Coach ADD CONSTRAINT coach_ibfk_1 FOREIGN KEY (clubId) REFERENCES Club (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Cours DROP FOREIGN KEY FK_3C0BA3981FB658F6');
        $this->addSql('ALTER TABLE Cours DROP FOREIGN KEY FK_3C0BA398880019E7');
        $this->addSql('ALTER TABLE Cours CHANGE clubId clubId INT NOT NULL, CHANGE niveauId niveauId INT NOT NULL');
        $this->addSql('ALTER TABLE Cours ADD CONSTRAINT cours_ibfk_2 FOREIGN KEY (clubId) REFERENCES Club (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Cours ADD CONSTRAINT cours_ibfk_1 FOREIGN KEY (niveauId) REFERENCES Niveau (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Cycle DROP FOREIGN KEY FK_7147FE971347B425');
        $this->addSql('ALTER TABLE Cycle DROP FOREIGN KEY FK_7147FE971FB658F6');
        $this->addSql('ALTER TABLE Cycle CHANGE coursId coursId INT NOT NULL, CHANGE clubId clubId INT NOT NULL');
        $this->addSql('ALTER TABLE Cycle ADD CONSTRAINT cycle_ibfk_1 FOREIGN KEY (coursId) REFERENCES Cours (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Cycle ADD CONSTRAINT cycle_ibfk_2 FOREIGN KEY (clubId) REFERENCES Club (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE DemeCategorie DROP FOREIGN KEY FK_43BAF2951FB658F6');
        $this->addSql('ALTER TABLE DemeCategorie CHANGE clubId clubId INT NOT NULL');
        $this->addSql('ALTER TABLE DemeCategorie ADD CONSTRAINT demecategorie_ibfk_1 FOREIGN KEY (clubId) REFERENCES Club (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Doctor DROP FOREIGN KEY FK_186CF65C1FB658F6');
        $this->addSql('ALTER TABLE Doctor CHANGE clubId clubId INT NOT NULL');
        $this->addSql('ALTER TABLE Doctor ADD CONSTRAINT doctor_ibfk_1 FOREIGN KEY (clubId) REFERENCES Club (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE DossierMedical DROP FOREIGN KEY FK_6440F5B41FB658F6');
        $this->addSql('ALTER TABLE DossierMedical DROP FOREIGN KEY FK_6440F5B477DEC5A');
        $this->addSql('ALTER TABLE DossierMedical DROP FOREIGN KEY FK_6440F5B477DEC5A');
        $this->addSql('ALTER TABLE DossierMedical CHANGE clubId clubId INT NOT NULL, CHANGE adherantId adherantId INT NOT NULL');
        $this->addSql('ALTER TABLE DossierMedical ADD CONSTRAINT dossiermedical_ibfk_2 FOREIGN KEY (clubId) REFERENCES Club (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE DossierMedical ADD CONSTRAINT dossiermedical_ibfk_1 FOREIGN KEY (adherantId) REFERENCES Adherant (id) ON UPDATE CASCADE');
        $this->addSql('DROP INDEX dossiermedical_adherantid_unique ON DossierMedical');
        $this->addSql('CREATE UNIQUE INDEX DossierMedical.adherantId_unique ON DossierMedical (adherantId)');
        $this->addSql('ALTER TABLE DossierMedical ADD CONSTRAINT FK_6440F5B477DEC5A FOREIGN KEY (adherantId) REFERENCES Adherant (id)');
        $this->addSql('ALTER TABLE Equipe DROP FOREIGN KEY FK_23E5BF23880019E7');
        $this->addSql('ALTER TABLE Equipe DROP FOREIGN KEY FK_23E5BF23911B7CB9');
        $this->addSql('ALTER TABLE Equipe DROP FOREIGN KEY FK_23E5BF231FB658F6');
        $this->addSql('ALTER TABLE Equipe DROP FOREIGN KEY FK_23E5BF237EF1F90C');
        $this->addSql('ALTER TABLE Equipe CHANGE niveauId niveauId INT NOT NULL, CHANGE doctorId doctorId INT NOT NULL, CHANGE clubId clubId INT NOT NULL, CHANGE coachId coachId INT NOT NULL');
        $this->addSql('ALTER TABLE Equipe ADD CONSTRAINT equipe_ibfk_3 FOREIGN KEY (niveauId) REFERENCES Niveau (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Equipe ADD CONSTRAINT equipe_ibfk_1 FOREIGN KEY (doctorId) REFERENCES Doctor (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Equipe ADD CONSTRAINT equipe_ibfk_4 FOREIGN KEY (clubId) REFERENCES Club (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Equipe ADD CONSTRAINT equipe_ibfk_2 FOREIGN KEY (coachId) REFERENCES Coach (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Mesure DROP FOREIGN KEY FK_58B76B46911B7CB9');
        $this->addSql('ALTER TABLE Mesure DROP FOREIGN KEY FK_58B76B461FB658F6');
        $this->addSql('ALTER TABLE Mesure DROP FOREIGN KEY FK_58B76B463AC4B029');
        $this->addSql('ALTER TABLE Mesure CHANGE doctorId doctorId INT NOT NULL, CHANGE clubId clubId INT NOT NULL, CHANGE dossier_medicalId dossier_medicalId INT NOT NULL');
        $this->addSql('ALTER TABLE Mesure ADD CONSTRAINT mesure_ibfk_2 FOREIGN KEY (doctorId) REFERENCES Doctor (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Mesure ADD CONSTRAINT mesure_ibfk_3 FOREIGN KEY (clubId) REFERENCES Club (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Mesure ADD CONSTRAINT mesure_ibfk_1 FOREIGN KEY (dossier_medicalId) REFERENCES DossierMedical (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Niveau DROP FOREIGN KEY FK_4C73F65D1FB658F6');
        $this->addSql('ALTER TABLE Niveau DROP FOREIGN KEY FK_4C73F65D438B1980');
        $this->addSql('ALTER TABLE Niveau CHANGE clubId clubId INT NOT NULL, CHANGE sectionId sectionId INT NOT NULL');
        $this->addSql('ALTER TABLE Niveau ADD CONSTRAINT niveau_ibfk_2 FOREIGN KEY (clubId) REFERENCES Club (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Niveau ADD CONSTRAINT niveau_ibfk_1 FOREIGN KEY (sectionId) REFERENCES Section (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Note DROP FOREIGN KEY FK_6F8F552ACA742475');
        $this->addSql('ALTER TABLE Note DROP FOREIGN KEY FK_6F8F552AA22540BD');
        $this->addSql('ALTER TABLE Note DROP FOREIGN KEY FK_6F8F552A1FB658F6');
        $this->addSql('ALTER TABLE Note DROP FOREIGN KEY FK_6F8F552A77DEC5A');
        $this->addSql('ALTER TABLE Note CHANGE objectifId objectifId INT NOT NULL, CHANGE testeId testeId INT NOT NULL, CHANGE clubId clubId INT NOT NULL, CHANGE adherantId adherantId INT NOT NULL');
        $this->addSql('ALTER TABLE Note ADD CONSTRAINT note_ibfk_3 FOREIGN KEY (objectifId) REFERENCES Objectif (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Note ADD CONSTRAINT note_ibfk_1 FOREIGN KEY (testeId) REFERENCES Teste (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Note ADD CONSTRAINT note_ibfk_4 FOREIGN KEY (clubId) REFERENCES Club (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Note ADD CONSTRAINT note_ibfk_2 FOREIGN KEY (adherantId) REFERENCES Adherant (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Objectif DROP FOREIGN KEY FK_1B8E0A078105C8EF');
        $this->addSql('ALTER TABLE Objectif DROP FOREIGN KEY FK_1B8E0A071FB658F6');
        $this->addSql('ALTER TABLE Objectif CHANGE cycleId cycleId INT NOT NULL, CHANGE clubId clubId INT NOT NULL');
        $this->addSql('ALTER TABLE Objectif ADD CONSTRAINT objectif_ibfk_1 FOREIGN KEY (cycleId) REFERENCES Cycle (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Objectif ADD CONSTRAINT objectif_ibfk_2 FOREIGN KEY (clubId) REFERENCES Club (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Seance DROP FOREIGN KEY FK_D8D1F83836BCDB3D');
        $this->addSql('ALTER TABLE Seance DROP FOREIGN KEY FK_D8D1F8388105C8EF');
        $this->addSql('ALTER TABLE Seance DROP FOREIGN KEY FK_D8D1F8381FB658F6');
        $this->addSql('ALTER TABLE Seance CHANGE equipeId equipeId INT NOT NULL, CHANGE cycleId cycleId INT NOT NULL, CHANGE clubId clubId INT NOT NULL');
        $this->addSql('ALTER TABLE Seance ADD CONSTRAINT seance_ibfk_1 FOREIGN KEY (equipeId) REFERENCES Equipe (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Seance ADD CONSTRAINT seance_ibfk_2 FOREIGN KEY (cycleId) REFERENCES Cycle (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Seance ADD CONSTRAINT seance_ibfk_3 FOREIGN KEY (clubId) REFERENCES Club (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Section DROP FOREIGN KEY FK_E2CE43731FB658F6');
        $this->addSql('ALTER TABLE Section CHANGE clubId clubId INT NOT NULL');
        $this->addSql('ALTER TABLE Section ADD CONSTRAINT section_ibfk_1 FOREIGN KEY (clubId) REFERENCES Club (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Teste DROP FOREIGN KEY FK_2775660B36BCDB3D');
        $this->addSql('ALTER TABLE Teste DROP FOREIGN KEY FK_2775660B1FB658F6');
        $this->addSql('ALTER TABLE Teste CHANGE equipeId equipeId INT NOT NULL, CHANGE clubId clubId INT NOT NULL');
        $this->addSql('ALTER TABLE Teste ADD CONSTRAINT teste_ibfk_1 FOREIGN KEY (equipeId) REFERENCES Equipe (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Teste ADD CONSTRAINT teste_ibfk_2 FOREIGN KEY (clubId) REFERENCES Club (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
