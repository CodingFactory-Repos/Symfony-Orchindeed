<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221102194338 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE companies ADD creation_date DATETIME NOT NULL, ADD update_date DATETIME NOT NULL, DROP creationDate, DROP updateDate');
        $this->addSql('ALTER TABLE offers ADD update_date DATETIME DEFAULT NULL, CHANGE creationDate creation_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE users ADD creation_date DATETIME NOT NULL, ADD update_date DATETIME NOT NULL, ADD roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', DROP creationDate, DROP updateDate');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE companies ADD creationDate DATETIME NOT NULL, ADD updateDate DATETIME NOT NULL, DROP creation_date, DROP update_date');
        $this->addSql('ALTER TABLE offers DROP update_date, CHANGE creation_date creationDate DATETIME NOT NULL');
        $this->addSql('ALTER TABLE users ADD creationDate DATETIME NOT NULL, ADD updateDate DATETIME NOT NULL, DROP creation_date, DROP update_date, DROP roles');
    }
}
