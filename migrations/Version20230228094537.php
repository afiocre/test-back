<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230228094537 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "comments_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "posts_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "users_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "comments" (id INT NOT NULL, created_by INT NOT NULL, post_id INT NOT NULL, content TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5F9E962ADE12AB56 ON "comments" (created_by)');
        $this->addSql('CREATE INDEX IDX_5F9E962A4B89032C ON "comments" (post_id)');
        $this->addSql('CREATE TABLE "posts" (id INT NOT NULL, created_by INT NOT NULL, title TEXT NOT NULL, content TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_885DBAFADE12AB56 ON "posts" (created_by)');
        $this->addSql('CREATE TABLE "users" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, firstname VARCHAR(255) NOT NULL, google_id VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON "users" (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E976F5C865 ON "users" (google_id)');
        $this->addSql('ALTER TABLE "comments" ADD CONSTRAINT FK_5F9E962ADE12AB56 FOREIGN KEY (created_by) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "comments" ADD CONSTRAINT FK_5F9E962A4B89032C FOREIGN KEY (post_id) REFERENCES "posts" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "posts" ADD CONSTRAINT FK_885DBAFADE12AB56 FOREIGN KEY (created_by) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "comments_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "posts_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "users_id_seq" CASCADE');
        $this->addSql('ALTER TABLE "comments" DROP CONSTRAINT FK_5F9E962ADE12AB56');
        $this->addSql('ALTER TABLE "comments" DROP CONSTRAINT FK_5F9E962A4B89032C');
        $this->addSql('ALTER TABLE "posts" DROP CONSTRAINT FK_885DBAFADE12AB56');
        $this->addSql('DROP TABLE "comments"');
        $this->addSql('DROP TABLE "posts"');
        $this->addSql('DROP TABLE "users"');
    }
}
