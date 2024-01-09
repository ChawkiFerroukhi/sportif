<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240109133106 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Cycle DROP FOREIGN KEY FK_7147FE971347B425');
        $this->addSql('ALTER TABLE Cours DROP FOREIGN KEY FK_3C0BA398880019E7');
        $this->addSql('ALTER TABLE Cours DROP FOREIGN KEY FK_3C0BA3981FB658F6');
        $this->addSql('DROP TABLE Cours');
        $this->addSql('DROP INDEX coursId ON Cycle');
        $this->addSql('ALTER TABLE Cycle CHANGE coursId niveauId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Cycle ADD CONSTRAINT FK_7147FE97880019E7 FOREIGN KEY (niveauId) REFERENCES Niveau (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX niveauId ON Cycle (niveauId)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Cours (id INT AUTO_INCREMENT NOT NULL, createdAt DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updatedAt DATETIME NOT NULL, nom VARCHAR(191) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(5000) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, clubId INT DEFAULT NULL, niveauId INT DEFAULT NULL, INDEX niveauId (niveauId), INDEX clubId (clubId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE Cours ADD CONSTRAINT FK_3C0BA398880019E7 FOREIGN KEY (niveauId) REFERENCES Niveau (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Cours ADD CONSTRAINT FK_3C0BA3981FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Cycle DROP FOREIGN KEY FK_7147FE97880019E7');
        $this->addSql('DROP INDEX niveauId ON Cycle');
        $this->addSql('ALTER TABLE Cycle CHANGE niveauId coursId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Cycle ADD CONSTRAINT FK_7147FE971347B425 FOREIGN KEY (coursId) REFERENCES Cours (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX coursId ON Cycle (coursId)');
    }
}
