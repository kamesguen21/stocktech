<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210519213938 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE description (id INT AUTO_INCREMENT NOT NULL, stock_id INT NOT NULL, logo VARCHAR(255) DEFAULT NULL, listdate VARCHAR(255) DEFAULT NULL, cik VARCHAR(255) DEFAULT NULL, bloomberg VARCHAR(255) DEFAULT NULL, figi VARCHAR(255) DEFAULT NULL, lei VARCHAR(255) DEFAULT NULL, sic INT DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, industry VARCHAR(255) DEFAULT NULL, sector VARCHAR(255) DEFAULT NULL, marketcap BIGINT DEFAULT NULL, employees INT DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, ceo VARCHAR(255) DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, description VARCHAR(3000) DEFAULT NULL, exchange VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, symbol VARCHAR(255) DEFAULT NULL, exchange_symbol VARCHAR(255) DEFAULT NULL, hq_address VARCHAR(255) DEFAULT NULL, hq_state VARCHAR(255) DEFAULT NULL, hq_country VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, updated DATE DEFAULT NULL, tags VARCHAR(255) DEFAULT NULL, similar VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_6DE44026DCD6110 (stock_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE description ADD CONSTRAINT FK_6DE44026DCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id)');
        $this->addSql('ALTER TABLE ticker ADD stock_id INT NOT NULL');
        $this->addSql('ALTER TABLE ticker ADD CONSTRAINT FK_7EC30896DCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7EC30896DCD6110 ON ticker (stock_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE description');
        $this->addSql('ALTER TABLE ticker DROP FOREIGN KEY FK_7EC30896DCD6110');
        $this->addSql('DROP INDEX UNIQ_7EC30896DCD6110 ON ticker');
        $this->addSql('ALTER TABLE ticker DROP stock_id');
    }
}
