<?php

declare(strict_types=1);

namespace Cbr\Collector\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230708183110 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rate_stat (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', rate NUMERIC(16, 3) NOT NULL, rateDayChange NUMERIC(16, 3) NOT NULL, currency VARCHAR(5) NOT NULL, baseCurrency VARCHAR(5) DEFAULT NULL, date DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE rate_stat');
    }
}
