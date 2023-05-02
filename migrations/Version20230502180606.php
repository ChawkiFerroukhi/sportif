<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230502180606 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Teste ADD cycleId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Teste ADD CONSTRAINT FK_2775660B8105C8EF FOREIGN KEY (cycleId) REFERENCES Cycle (id)');
        $this->addSql('CREATE INDEX IDX_2775660B8105C8EF ON Teste (cycleId)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Teste DROP FOREIGN KEY FK_2775660B8105C8EF');
        $this->addSql('DROP INDEX IDX_2775660B8105C8EF ON Teste');
        $this->addSql('ALTER TABLE Teste DROP cycleId');
    }
}
