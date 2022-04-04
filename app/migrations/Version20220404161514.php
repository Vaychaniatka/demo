<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220404161514 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE client_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE notification_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql(
            'CREATE TABLE client (
                id INT NOT NULL,
                first_name VARCHAR(32) NOT NULL,
                last_name VARCHAR(32) NOT NULL,
                email VARCHAR(100) NOT NULL,
                phone_number VARCHAR(255) NOT NULL,
                created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                PRIMARY KEY(id)
            )'
        );
        $this->addSql('COMMENT ON COLUMN client.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN client.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql(
            'CREATE TABLE notification (
                id INT NOT NULL,
                client_id INT NOT NULL,
                channel VARCHAR(5) NOT NULL,
                content TEXT NOT NULL,
                created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                is_sent BOOLEAN NOT NULL,
                PRIMARY KEY(id)
            )'
        );
        $this->addSql('CREATE INDEX IDX_BF5476CA19EB6921 ON notification (client_id)');
        $this->addSql('COMMENT ON COLUMN notification.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN notification.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql(
            'CREATE TABLE "user" (
                id INT NOT NULL,
                username VARCHAR(180) NOT NULL,
                roles JSON NOT NULL,
                password VARCHAR(255) NOT NULL,
                token VARCHAR(30) DEFAULT NULL,
                PRIMARY KEY(id)
            )'
        );
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
        $this->addSql(
            'ALTER TABLE notification 
            ADD CONSTRAINT FK_BF5476CA19EB6921
                FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE notification DROP CONSTRAINT FK_BF5476CA19EB6921');
        $this->addSql('DROP SEQUENCE client_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE notification_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE "user"');
    }
}
