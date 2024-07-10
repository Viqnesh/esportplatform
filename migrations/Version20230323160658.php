<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230323160658 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fixed_match ADD scoresheet_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fixed_match ADD CONSTRAINT FK_133CEDF39ED2C24C FOREIGN KEY (scoresheet_id) REFERENCES scoresheet (id)');
        $this->addSql('CREATE INDEX IDX_133CEDF39ED2C24C ON fixed_match (scoresheet_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fixed_match DROP FOREIGN KEY FK_133CEDF39ED2C24C');
        $this->addSql('DROP INDEX IDX_133CEDF39ED2C24C ON fixed_match');
        $this->addSql('ALTER TABLE fixed_match DROP scoresheet_id');
    }
}
