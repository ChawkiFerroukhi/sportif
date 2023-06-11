<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230611212243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Adherant ADD equipe2Id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Adherant ADD CONSTRAINT FK_6EAC3AEAB80D6758 FOREIGN KEY (equipe2Id) REFERENCES Equipe (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_6EAC3AEAB80D6758 ON Adherant (equipe2Id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Adherant DROP FOREIGN KEY FK_6EAC3AEAB80D6758');
        $this->addSql('DROP INDEX IDX_6EAC3AEAB80D6758 ON Adherant');
        $this->addSql('ALTER TABLE Adherant DROP equipe2Id');
    }
}
