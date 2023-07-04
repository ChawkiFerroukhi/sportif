<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230704132602 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Income (id INT NOT NULL, userid INT DEFAULT NULL, INDEX IDX_380467E6F132696E (userid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Income ADD CONSTRAINT FK_380467E6F132696E FOREIGN KEY (userid) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Income ADD CONSTRAINT FK_380467E6BF396750 FOREIGN KEY (id) REFERENCES Decaissement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Decaissement ADD type VARCHAR(191) NOT NULL, ADD discr VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Income DROP FOREIGN KEY FK_380467E6F132696E');
        $this->addSql('ALTER TABLE Income DROP FOREIGN KEY FK_380467E6BF396750');
        $this->addSql('DROP TABLE Income');
        $this->addSql('ALTER TABLE Decaissement DROP type, DROP discr');
    }
}
