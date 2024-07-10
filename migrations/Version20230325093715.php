<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230325093715 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE message_topic (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, topic_id INT NOT NULL, message VARCHAR(255) NOT NULL, INDEX IDX_62E621F0A76ED395 (user_id), INDEX IDX_62E621F01F55203D (topic_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE topic (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE message_topic ADD CONSTRAINT FK_62E621F0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message_topic ADD CONSTRAINT FK_62E621F01F55203D FOREIGN KEY (topic_id) REFERENCES topic (id)');
        $this->addSql('ALTER TABLE tribunal ADD topic_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tribunal ADD CONSTRAINT FK_DC8C3AAF1F55203D FOREIGN KEY (topic_id) REFERENCES topic (id)');
        $this->addSql('CREATE INDEX IDX_DC8C3AAF1F55203D ON tribunal (topic_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message_topic DROP FOREIGN KEY FK_62E621F01F55203D');
        $this->addSql('ALTER TABLE tribunal DROP FOREIGN KEY FK_DC8C3AAF1F55203D');
        $this->addSql('DROP TABLE message_topic');
        $this->addSql('DROP TABLE topic');
        $this->addSql('DROP INDEX IDX_DC8C3AAF1F55203D ON tribunal');
        $this->addSql('ALTER TABLE tribunal DROP topic_id');
    }
}
