<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221103101502 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE skills (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE skills_users (skills_id INT NOT NULL, users_id INT NOT NULL, INDEX IDX_B9EC4E897FF61858 (skills_id), INDEX IDX_B9EC4E8967B3B43D (users_id), PRIMARY KEY(skills_id, users_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE skills_offers (skills_id INT NOT NULL, offers_id INT NOT NULL, INDEX IDX_97590A947FF61858 (skills_id), INDEX IDX_97590A94A090B42E (offers_id), PRIMARY KEY(skills_id, offers_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE skills_users ADD CONSTRAINT FK_B9EC4E897FF61858 FOREIGN KEY (skills_id) REFERENCES skills (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE skills_users ADD CONSTRAINT FK_B9EC4E8967B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE skills_offers ADD CONSTRAINT FK_97590A947FF61858 FOREIGN KEY (skills_id) REFERENCES skills (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE skills_offers ADD CONSTRAINT FK_97590A94A090B42E FOREIGN KEY (offers_id) REFERENCES offers (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE skills_users DROP FOREIGN KEY FK_B9EC4E897FF61858');
        $this->addSql('ALTER TABLE skills_users DROP FOREIGN KEY FK_B9EC4E8967B3B43D');
        $this->addSql('ALTER TABLE skills_offers DROP FOREIGN KEY FK_97590A947FF61858');
        $this->addSql('ALTER TABLE skills_offers DROP FOREIGN KEY FK_97590A94A090B42E');
        $this->addSql('DROP TABLE skills');
        $this->addSql('DROP TABLE skills_users');
        $this->addSql('DROP TABLE skills_offers');
    }
}
