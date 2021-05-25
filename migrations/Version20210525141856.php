<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210525141856 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create default admin user';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('insert into user (email,is_verified,password,roles) value (\'admin@admin.com\',1,\'$argon2i$v=19$m=65536,t=4,p=1$bDEveDc1RkI4aG9vUVExUQ$cf3x5/Q4LYMtr1SfwPMK3cPUjLYjkzxiVqbbPha5q5E\',\'[]\')');

    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE from user WHERE email=\'admin@admin.com\';');

    }
}
