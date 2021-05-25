<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210522025924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ticker DROP INDEX UNIQ_7EC30896DCD6110, ADD INDEX IDX_7EC30896DCD6110 (stock_id)');
        $this->addSql('ALTER TABLE ticker DROP FOREIGN KEY FK_7EC30896DCD6110');
        $this->addSql('ALTER TABLE ticker ADD CONSTRAINT FK_7EC30896DCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ticker DROP INDEX IDX_7EC30896DCD6110, ADD UNIQUE INDEX UNIQ_7EC30896DCD6110 (stock_id)');
        $this->addSql('ALTER TABLE ticker DROP FOREIGN KEY FK_7EC30896DCD6110');
        $this->addSql('ALTER TABLE ticker ADD CONSTRAINT FK_7EC30896DCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id) ON DELETE CASCADE');
    }
}
