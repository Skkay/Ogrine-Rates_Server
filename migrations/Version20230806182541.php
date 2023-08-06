<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230806182541 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE discord_webhook ADD last_response_status INT DEFAULT NULL');
        $this->addSql('ALTER TABLE discord_webhook ADD last_response TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE discord_webhook ADD datetime_last_successful_response TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE discord_webhook DROP last_response_status');
        $this->addSql('ALTER TABLE discord_webhook DROP last_response');
        $this->addSql('ALTER TABLE discord_webhook DROP datetime_last_successful_response');
    }
}
