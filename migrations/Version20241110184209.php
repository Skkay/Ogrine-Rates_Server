<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241110184209 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE real_time_ogrine_rate_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE real_time_ogrine_rate (id INT NOT NULL, datetime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, rate INT NOT NULL, number_of_ogrines INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN real_time_ogrine_rate.datetime IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE real_time_ogrine_rate_id_seq CASCADE');
        $this->addSql('DROP TABLE real_time_ogrine_rate');
    }
}
