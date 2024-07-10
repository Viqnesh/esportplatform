<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230326152258 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classement ADD league_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE classement ADD CONSTRAINT FK_55EE9D6D58AFC4DE FOREIGN KEY (league_id) REFERENCES league (id)');
        $this->addSql('CREATE INDEX IDX_55EE9D6D58AFC4DE ON classement (league_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classement DROP FOREIGN KEY FK_55EE9D6D58AFC4DE');
        $this->addSql('DROP INDEX IDX_55EE9D6D58AFC4DE ON classement');
        $this->addSql('ALTER TABLE classement DROP league_id');
    }
}
