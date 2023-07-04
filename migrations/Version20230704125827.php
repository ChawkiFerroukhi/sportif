<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230704125827 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Payment DROP FOREIGN KEY FK_A295BD9192F9C8F8');
        $this->addSql('DROP INDEX IDX_A295BD9192F9C8F8 ON Payment');
        $this->addSql('ALTER TABLE Payment CHANGE adherantid userid INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Payment ADD CONSTRAINT FK_A295BD91F132696E FOREIGN KEY (userid) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_A295BD91F132696E ON Payment (userid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Payment DROP FOREIGN KEY FK_A295BD91F132696E');
        $this->addSql('DROP INDEX IDX_A295BD91F132696E ON Payment');
        $this->addSql('ALTER TABLE Payment CHANGE userid adherantid INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Payment ADD CONSTRAINT FK_A295BD9192F9C8F8 FOREIGN KEY (adherantid) REFERENCES Adherant (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_A295BD9192F9C8F8 ON Payment (adherantid)');
    }
}
