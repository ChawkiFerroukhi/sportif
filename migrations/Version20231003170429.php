<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231003170429 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Coach ADD sectionId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Coach ADD CONSTRAINT FK_FE9842C8438B1980 FOREIGN KEY (sectionId) REFERENCES Section (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_FE9842C8438B1980 ON Coach (sectionId)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Coach DROP FOREIGN KEY FK_FE9842C8438B1980');
        $this->addSql('DROP INDEX IDX_FE9842C8438B1980 ON Coach');
        $this->addSql('ALTER TABLE Coach DROP sectionId');
    }
}
