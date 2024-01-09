<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240109123402 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX unique_section_nom_clubid ON Section (nom, clubId)');
        $this->addSql('ALTER TABLE user CHANGE ref ref VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX uniq_8d93d649 ON user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649146F3EA3 ON user (ref)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX unique_section_nom_clubid ON Section');
        $this->addSql('ALTER TABLE user CHANGE ref ref VARCHAR(171) NOT NULL');
        $this->addSql('DROP INDEX uniq_8d93d649146f3ea3 ON user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649 ON user (ref)');
    }
}
