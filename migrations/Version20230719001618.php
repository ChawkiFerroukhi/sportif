<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230719001618 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Administrateur CHANGE poste poste INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Administrateur ADD CONSTRAINT FK_FF8F2A307C890FAB FOREIGN KEY (poste) REFERENCES Poste (id)');
        $this->addSql('CREATE INDEX IDX_FF8F2A307C890FAB ON Administrateur (poste)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Administrateur DROP FOREIGN KEY FK_FF8F2A307C890FAB');
        $this->addSql('DROP INDEX IDX_FF8F2A307C890FAB ON Administrateur');
        $this->addSql('ALTER TABLE Administrateur CHANGE poste poste VARCHAR(191) DEFAULT NULL');
    }
}
