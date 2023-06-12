<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230612214432 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Dossier (id INT AUTO_INCREMENT NOT NULL, adherantid INT DEFAULT NULL, createdAt DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updatedAt DATETIME NOT NULL, nom VARCHAR(191) NOT NULL, document VARCHAR(255) NOT NULL, clubId INT DEFAULT NULL, INDEX IDX_F2F5D9AB1FB658F6 (clubId), INDEX adherantId (adherantId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Dossier ADD CONSTRAINT FK_F2F5D9AB92F9C8F8 FOREIGN KEY (adherantid) REFERENCES Adherant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Dossier ADD CONSTRAINT FK_F2F5D9AB1FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Dossier DROP FOREIGN KEY FK_F2F5D9AB92F9C8F8');
        $this->addSql('ALTER TABLE Dossier DROP FOREIGN KEY FK_F2F5D9AB1FB658F6');
        $this->addSql('DROP TABLE Dossier');
    }
}
