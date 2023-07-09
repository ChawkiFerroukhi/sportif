<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230709215051 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Picture DROP FOREIGN KEY FK_D966761592F9C8F8');
        $this->addSql('ALTER TABLE Picture ADD CONSTRAINT FK_D966761592F9C8F8 FOREIGN KEY (adherantid) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Picture DROP FOREIGN KEY FK_D966761592F9C8F8');
        $this->addSql('ALTER TABLE Picture ADD CONSTRAINT FK_D966761592F9C8F8 FOREIGN KEY (adherantid) REFERENCES Adherant (id) ON DELETE CASCADE');
    }
}
