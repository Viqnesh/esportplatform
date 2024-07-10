<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230326151617 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE league ADD classement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE league ADD CONSTRAINT FK_3EB4C318A513A63E FOREIGN KEY (classement_id) REFERENCES classement (id)');
        $this->addSql('CREATE INDEX IDX_3EB4C318A513A63E ON league (classement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE league DROP FOREIGN KEY FK_3EB4C318A513A63E');
        $this->addSql('DROP INDEX IDX_3EB4C318A513A63E ON league');
        $this->addSql('ALTER TABLE league DROP classement_id');
    }
}
