<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230626152737 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Adherant CHANGE maladie maladie INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Adherant ADD CONSTRAINT FK_6EAC3AEAADC4024B FOREIGN KEY (maladie) REFERENCES Maladie (id)');
        $this->addSql('CREATE INDEX IDX_6EAC3AEAADC4024B ON Adherant (maladie)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Adherant DROP FOREIGN KEY FK_6EAC3AEAADC4024B');
        $this->addSql('DROP INDEX IDX_6EAC3AEAADC4024B ON Adherant');
        $this->addSql('ALTER TABLE Adherant CHANGE maladie maladie VARCHAR(255) DEFAULT NULL');
    }
}
