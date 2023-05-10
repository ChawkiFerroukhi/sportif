<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230508214805 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Blog ADD sectionId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Blog ADD CONSTRAINT FK_6027FE7D438B1980 FOREIGN KEY (sectionId) REFERENCES Section (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_6027FE7D438B1980 ON Blog (sectionId)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Blog DROP FOREIGN KEY FK_6027FE7D438B1980');
        $this->addSql('DROP INDEX IDX_6027FE7D438B1980 ON Blog');
        $this->addSql('ALTER TABLE Blog DROP sectionId');
    }
}
