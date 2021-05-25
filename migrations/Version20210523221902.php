<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210523221902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE news (id INT AUTO_INCREMENT NOT NULL, source_name VARCHAR(255) DEFAULT NULL, author VARCHAR(255) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, url_to_image VARCHAR(255) DEFAULT NULL, published_at DATETIME DEFAULT NULL, content VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ticker CHANGE date date DATE NOT NULL, CHANGE open open DOUBLE PRECISION NOT NULL, CHANGE hight hight DOUBLE PRECISION NOT NULL, CHANGE low low DOUBLE PRECISION NOT NULL, CHANGE close close DOUBLE PRECISION NOT NULL, CHANGE adj_close adj_close DOUBLE PRECISION NOT NULL, CHANGE volume volume BIGINT NOT NULL, CHANGE symbol symbol VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE news');
        $this->addSql('ALTER TABLE ticker CHANGE date date DATE DEFAULT NULL, CHANGE open open DOUBLE PRECISION DEFAULT NULL, CHANGE hight hight DOUBLE PRECISION DEFAULT NULL, CHANGE low low DOUBLE PRECISION DEFAULT NULL, CHANGE close close DOUBLE PRECISION DEFAULT NULL, CHANGE adj_close adj_close DOUBLE PRECISION DEFAULT NULL, CHANGE volume volume BIGINT DEFAULT NULL, CHANGE symbol symbol VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
