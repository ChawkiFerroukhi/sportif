<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230515153207 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Payment (id INT AUTO_INCREMENT NOT NULL, adherantid INT DEFAULT NULL, createdAt DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updatedAt DATETIME NOT NULL, date DATETIME NOT NULL, total DOUBLE PRECISION NOT NULL, mode VARCHAR(191) NOT NULL, designation VARCHAR(191) NOT NULL, clubId INT DEFAULT NULL, INDEX IDX_A295BD9192F9C8F8 (adherantid), INDEX clubId (clubId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Payment ADD CONSTRAINT FK_A295BD911FB658F6 FOREIGN KEY (clubId) REFERENCES Club (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Payment ADD CONSTRAINT FK_A295BD9192F9C8F8 FOREIGN KEY (adherantid) REFERENCES Adherant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Blog CHANGE content content VARCHAR(5000) NOT NULL');
        $this->addSql('ALTER TABLE Section CHANGE description description VARCHAR(5000) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Payment DROP FOREIGN KEY FK_A295BD911FB658F6');
        $this->addSql('ALTER TABLE Payment DROP FOREIGN KEY FK_A295BD9192F9C8F8');
        $this->addSql('DROP TABLE Payment');
        $this->addSql('ALTER TABLE Blog CHANGE content content VARCHAR(5000) NOT NULL');
        $this->addSql('ALTER TABLE Section CHANGE description description VARCHAR(3000) NOT NULL');
    }
}
