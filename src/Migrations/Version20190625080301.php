<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190625080301 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE articles (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL, content CLOB NOT NULL, created_at DATETIME NOT NULL)');
        $this->addSql('CREATE INDEX IDX_BFDD3168A76ED395 ON articles (user_id)');
        $this->addSql('CREATE TABLE comments (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, article_id INTEGER NOT NULL, user_id INTEGER NOT NULL, content CLOB NOT NULL, added_at DATETIME NOT NULL)');
        $this->addSql('CREATE INDEX IDX_5F9E962A7294869C ON comments (article_id)');
        $this->addSql('CREATE INDEX IDX_5F9E962AA76ED395 ON comments (user_id)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, login VARCHAR(15) NOT NULL, password VARCHAR(20) NOT NULL, email VARCHAR(125) NOT NULL)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE articles');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE user');
    }
}
