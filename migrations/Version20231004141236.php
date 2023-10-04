<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231004141236 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Document (id INT AUTO_INCREMENT NOT NULL, dossiermedicalid INT DEFAULT NULL, createdAt DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updatedAt DATETIME NOT NULL, nom VARCHAR(191) NOT NULL, document VARCHAR(255) NOT NULL, clubId INT DEFAULT NULL, INDEX IDX_211FE8201FB658F6 (clubId), INDEX dossiermedicalId (dossiermedicalId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Document ADD CONSTRAINT FK_211FE820678997E1 FOREIGN KEY (dossiermedicalid) REFERENCES DossierMedical (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Document ADD CONSTRAINT FK_211FE8201FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Document DROP FOREIGN KEY FK_211FE820678997E1');
        $this->addSql('ALTER TABLE Document DROP FOREIGN KEY FK_211FE8201FB658F6');
        $this->addSql('DROP TABLE Document');
    }
}
