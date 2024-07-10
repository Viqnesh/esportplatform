<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230328194946 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE scoresheet ADD team_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE scoresheet ADD CONSTRAINT FK_F001608F296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('CREATE INDEX IDX_F001608F296CD8AE ON scoresheet (team_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE scoresheet DROP FOREIGN KEY FK_F001608F296CD8AE');
        $this->addSql('DROP INDEX IDX_F001608F296CD8AE ON scoresheet');
        $this->addSql('ALTER TABLE scoresheet DROP team_id');
    }
}
