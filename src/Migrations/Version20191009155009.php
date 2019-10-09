<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191009155009 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_BFDD3168A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__articles AS SELECT id, user_id, title, content, created_at, link FROM articles');
        $this->addSql('DROP TABLE articles');
        $this->addSql('CREATE TABLE articles (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL COLLATE BINARY, content CLOB NOT NULL COLLATE BINARY, created_at DATETIME NOT NULL, link VARCHAR(255) NOT NULL COLLATE BINARY, image BLOB DEFAULT NULL, CONSTRAINT FK_BFDD3168A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO articles (id, user_id, title, content, created_at, link) SELECT id, user_id, title, content, created_at, link FROM __temp__articles');
        $this->addSql('DROP TABLE __temp__articles');
        $this->addSql('CREATE INDEX IDX_BFDD3168A76ED395 ON articles (user_id)');
        $this->addSql('DROP INDEX IDX_D76F110E1EBAF6CC');
        $this->addSql('DROP INDEX IDX_D76F110EA76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__articles_user AS SELECT articles_id, user_id FROM articles_user');
        $this->addSql('DROP TABLE articles_user');
        $this->addSql('CREATE TABLE articles_user (articles_id INTEGER NOT NULL, user_id INTEGER NOT NULL, PRIMARY KEY(articles_id, user_id), CONSTRAINT FK_D76F110E1EBAF6CC FOREIGN KEY (articles_id) REFERENCES articles (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D76F110EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO articles_user (articles_id, user_id) SELECT articles_id, user_id FROM __temp__articles_user');
        $this->addSql('DROP TABLE __temp__articles_user');
        $this->addSql('CREATE INDEX IDX_D76F110E1EBAF6CC ON articles_user (articles_id)');
        $this->addSql('CREATE INDEX IDX_D76F110EA76ED395 ON articles_user (user_id)');
        $this->addSql('DROP INDEX IDX_5F9E962AA76ED395');
        $this->addSql('DROP INDEX IDX_5F9E962A7294869C');
        $this->addSql('CREATE TEMPORARY TABLE __temp__comments AS SELECT id, article_id, user_id, content, added_at FROM comments');
        $this->addSql('DROP TABLE comments');
        $this->addSql('CREATE TABLE comments (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, article_id INTEGER NOT NULL, user_id INTEGER NOT NULL, content CLOB NOT NULL COLLATE BINARY, added_at DATETIME NOT NULL, CONSTRAINT FK_5F9E962A7294869C FOREIGN KEY (article_id) REFERENCES articles (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_5F9E962AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO comments (id, article_id, user_id, content, added_at) SELECT id, article_id, user_id, content, added_at FROM __temp__comments');
        $this->addSql('DROP TABLE __temp__comments');
        $this->addSql('CREATE INDEX IDX_5F9E962AA76ED395 ON comments (user_id)');
        $this->addSql('CREATE INDEX IDX_5F9E962A7294869C ON comments (article_id)');
        $this->addSql('DROP INDEX UNIQ_8D93D649BB1A0722');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, details_id, login, password, email, joined_at, is_disabled FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, details_id INTEGER DEFAULT NULL, login VARCHAR(15) NOT NULL COLLATE BINARY, password VARCHAR(20) NOT NULL COLLATE BINARY, email VARCHAR(125) NOT NULL COLLATE BINARY, joined_at DATETIME DEFAULT NULL, is_disabled BOOLEAN NOT NULL, CONSTRAINT FK_8D93D649BB1A0722 FOREIGN KEY (details_id) REFERENCES details (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO user (id, details_id, login, password, email, joined_at, is_disabled) SELECT id, details_id, login, password, email, joined_at, is_disabled FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649BB1A0722 ON user (details_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_BFDD3168A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__articles AS SELECT id, user_id, title, content, created_at, link FROM articles');
        $this->addSql('DROP TABLE articles');
        $this->addSql('CREATE TABLE articles (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL, content CLOB NOT NULL, created_at DATETIME NOT NULL, link VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO articles (id, user_id, title, content, created_at, link) SELECT id, user_id, title, content, created_at, link FROM __temp__articles');
        $this->addSql('DROP TABLE __temp__articles');
        $this->addSql('CREATE INDEX IDX_BFDD3168A76ED395 ON articles (user_id)');
        $this->addSql('DROP INDEX IDX_D76F110E1EBAF6CC');
        $this->addSql('DROP INDEX IDX_D76F110EA76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__articles_user AS SELECT articles_id, user_id FROM articles_user');
        $this->addSql('DROP TABLE articles_user');
        $this->addSql('CREATE TABLE articles_user (articles_id INTEGER NOT NULL, user_id INTEGER NOT NULL, PRIMARY KEY(articles_id, user_id))');
        $this->addSql('INSERT INTO articles_user (articles_id, user_id) SELECT articles_id, user_id FROM __temp__articles_user');
        $this->addSql('DROP TABLE __temp__articles_user');
        $this->addSql('CREATE INDEX IDX_D76F110E1EBAF6CC ON articles_user (articles_id)');
        $this->addSql('CREATE INDEX IDX_D76F110EA76ED395 ON articles_user (user_id)');
        $this->addSql('DROP INDEX IDX_5F9E962A7294869C');
        $this->addSql('DROP INDEX IDX_5F9E962AA76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__comments AS SELECT id, article_id, user_id, content, added_at FROM comments');
        $this->addSql('DROP TABLE comments');
        $this->addSql('CREATE TABLE comments (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, article_id INTEGER NOT NULL, user_id INTEGER NOT NULL, content CLOB NOT NULL, added_at DATETIME NOT NULL)');
        $this->addSql('INSERT INTO comments (id, article_id, user_id, content, added_at) SELECT id, article_id, user_id, content, added_at FROM __temp__comments');
        $this->addSql('DROP TABLE __temp__comments');
        $this->addSql('CREATE INDEX IDX_5F9E962A7294869C ON comments (article_id)');
        $this->addSql('CREATE INDEX IDX_5F9E962AA76ED395 ON comments (user_id)');
        $this->addSql('DROP INDEX UNIQ_8D93D649BB1A0722');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, details_id, login, password, email, joined_at, is_disabled FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, details_id INTEGER DEFAULT NULL, login VARCHAR(15) NOT NULL, password VARCHAR(20) NOT NULL, email VARCHAR(125) NOT NULL, joined_at DATETIME DEFAULT NULL, is_disabled BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO user (id, details_id, login, password, email, joined_at, is_disabled) SELECT id, details_id, login, password, email, joined_at, is_disabled FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649BB1A0722 ON user (details_id)');
    }
}
