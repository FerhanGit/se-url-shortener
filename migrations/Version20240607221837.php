<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240607221837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__url_shortener AS SELECT id, long_url, short_url, created_at FROM url_shortener');
        $this->addSql('DROP TABLE url_shortener');
        $this->addSql('CREATE TABLE url_shortener (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, long_url VARCHAR(255) NOT NULL, short_url VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO url_shortener (id, long_url, short_url, created_at) SELECT id, long_url, short_url, created_at FROM __temp__url_shortener');
        $this->addSql('DROP TABLE __temp__url_shortener');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6759B37A1E2D10AE ON url_shortener (long_url)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__url_shortener AS SELECT id, long_url, short_url, created_at FROM url_shortener');
        $this->addSql('DROP TABLE url_shortener');
        $this->addSql('CREATE TABLE url_shortener (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, long_url VARCHAR(255) NOT NULL, short_url VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO url_shortener (id, long_url, short_url, created_at) SELECT id, long_url, short_url, created_at FROM __temp__url_shortener');
        $this->addSql('DROP TABLE __temp__url_shortener');
    }
}
