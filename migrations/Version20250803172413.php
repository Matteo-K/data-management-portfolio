<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250803172413 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE school (id INT AUTO_INCREMENT NOT NULL, statut VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, link_web VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, logo_name VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project ADD school_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEC32A47EE FOREIGN KEY (school_id) REFERENCES school (id)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EEC32A47EE ON project (school_id)');
        $this->addSql('ALTER TABLE technology ADD illustration_name VARCHAR(255) DEFAULT NULL, DROP illustration');
        $this->addSql('ALTER TABLE trophy CHANGE illustration illustration_name VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EEC32A47EE');
        $this->addSql('DROP TABLE school');
        $this->addSql('DROP INDEX IDX_2FB3D0EEC32A47EE ON project');
        $this->addSql('ALTER TABLE project DROP school_id');
        $this->addSql('ALTER TABLE technology ADD illustration VARCHAR(255) NOT NULL, DROP illustration_name');
        $this->addSql('ALTER TABLE trophy CHANGE illustration_name illustration VARCHAR(255) DEFAULT NULL');
    }
}
