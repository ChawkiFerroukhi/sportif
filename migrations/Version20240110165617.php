<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240110165617 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE equipe_staff (equipeid INT NOT NULL, staffid INT NOT NULL, INDEX IDX_18626518A338FF9F (equipeid), INDEX IDX_18626518D8FA6E00 (staffid), PRIMARY KEY(equipeid, staffid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Staff (id INT NOT NULL, createdAt DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updatedAt DATETIME NOT NULL, nom VARCHAR(191) NOT NULL, prenom VARCHAR(191) NOT NULL, num_tel VARCHAR(191) NOT NULL, CIN VARCHAR(191) NOT NULL, adresse VARCHAR(191) NOT NULL, poste VARCHAR(191) NOT NULL, sectionId INT DEFAULT NULL, INDEX IDX_83AFDC96438B1980 (sectionId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE equipe_staff ADD CONSTRAINT FK_18626518A338FF9F FOREIGN KEY (equipeid) REFERENCES Equipe (id)');
        $this->addSql('ALTER TABLE equipe_staff ADD CONSTRAINT FK_18626518D8FA6E00 FOREIGN KEY (staffid) REFERENCES Staff (id)');
        $this->addSql('ALTER TABLE Staff ADD CONSTRAINT FK_83AFDC96438B1980 FOREIGN KEY (sectionId) REFERENCES Section (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Staff ADD CONSTRAINT FK_83AFDC96BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipe_staff DROP FOREIGN KEY FK_18626518A338FF9F');
        $this->addSql('ALTER TABLE equipe_staff DROP FOREIGN KEY FK_18626518D8FA6E00');
        $this->addSql('ALTER TABLE Staff DROP FOREIGN KEY FK_83AFDC96438B1980');
        $this->addSql('ALTER TABLE Staff DROP FOREIGN KEY FK_83AFDC96BF396750');
        $this->addSql('DROP TABLE equipe_staff');
        $this->addSql('DROP TABLE Staff');
    }
}
