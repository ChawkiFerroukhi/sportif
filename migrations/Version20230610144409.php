<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230610144409 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Adherant ADD supervisor2Id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Adherant ADD CONSTRAINT FK_6EAC3AEACC62D08E FOREIGN KEY (supervisor2Id) REFERENCES Supervisor (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX supervisor2Id ON Adherant (supervisor2Id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Adherant DROP FOREIGN KEY FK_6EAC3AEACC62D08E');
        $this->addSql('DROP INDEX supervisor2Id ON Adherant');
        $this->addSql('ALTER TABLE Adherant DROP supervisor2Id');
    }
}
