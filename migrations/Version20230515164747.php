<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230515164747 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Blog CHANGE content content VARCHAR(191) NOT NULL');
        $this->addSql('ALTER TABLE Payment ADD ref VARCHAR(191) NOT NULL');
        $this->addSql('ALTER TABLE Section CHANGE description description VARCHAR(555) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Blog CHANGE content content VARCHAR(5000) NOT NULL');
        $this->addSql('ALTER TABLE Payment DROP ref');
        $this->addSql('ALTER TABLE Section CHANGE description description VARCHAR(3000) NOT NULL');
    }
}
