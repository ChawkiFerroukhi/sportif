<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230615010034 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Encaissement (id INT AUTO_INCREMENT NOT NULL, createdAt DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updatedAt DATETIME NOT NULL, date DATETIME NOT NULL, total DOUBLE PRECISION NOT NULL, mode VARCHAR(191) NOT NULL, status VARCHAR(191) NOT NULL, designation VARCHAR(191) NOT NULL, ref VARCHAR(191) NOT NULL, clubId INT DEFAULT NULL, discr VARCHAR(255) NOT NULL, INDEX clubId (clubId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Encaissement ADD CONSTRAINT FK_4579B2481FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Payment DROP FOREIGN KEY FK_A295BD911FB658F6');
        $this->addSql('DROP INDEX clubId ON Payment');
        $this->addSql('ALTER TABLE Payment DROP createdAt, DROP updatedAt, DROP date, DROP total, DROP mode, DROP status, DROP designation, DROP ref, DROP clubId, CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE Payment ADD CONSTRAINT FK_A295BD91BF396750 FOREIGN KEY (id) REFERENCES Encaissement (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Payment DROP FOREIGN KEY FK_A295BD91BF396750');
        $this->addSql('ALTER TABLE Encaissement DROP FOREIGN KEY FK_4579B2481FB658F6');
        $this->addSql('DROP TABLE Encaissement');
        $this->addSql('ALTER TABLE Payment ADD createdAt DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, ADD updatedAt DATETIME NOT NULL, ADD date DATETIME NOT NULL, ADD total DOUBLE PRECISION NOT NULL, ADD mode VARCHAR(191) NOT NULL, ADD status VARCHAR(191) NOT NULL, ADD designation VARCHAR(191) NOT NULL, ADD ref VARCHAR(191) NOT NULL, ADD clubId INT DEFAULT NULL, CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE Payment ADD CONSTRAINT FK_A295BD911FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX clubId ON Payment (clubId)');
    }
}
