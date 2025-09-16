<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250816122948 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE trophy_road (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, INDEX IDX_62F907E1166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE trophy_road ADD CONSTRAINT FK_62F907E1166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE trophy DROP FOREIGN KEY FK_112AFAE9166D1F9C');
        $this->addSql('DROP INDEX IDX_112AFAE9166D1F9C ON trophy');
        $this->addSql('ALTER TABLE trophy CHANGE project_id trophy_road_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trophy ADD CONSTRAINT FK_112AFAE94541A3BA FOREIGN KEY (trophy_road_id) REFERENCES trophy_road (id)');
        $this->addSql('CREATE INDEX IDX_112AFAE94541A3BA ON trophy (trophy_road_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trophy DROP FOREIGN KEY FK_112AFAE94541A3BA');
        $this->addSql('ALTER TABLE trophy_road DROP FOREIGN KEY FK_62F907E1166D1F9C');
        $this->addSql('DROP TABLE trophy_road');
        $this->addSql('DROP INDEX IDX_112AFAE94541A3BA ON trophy');
        $this->addSql('ALTER TABLE trophy CHANGE trophy_road_id project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trophy ADD CONSTRAINT FK_112AFAE9166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_112AFAE9166D1F9C ON trophy (project_id)');
    }
}
