<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221102185313 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE companies (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, zipcode INT NOT NULL, creationDate DATETIME NOT NULL, updateDate DATETIME NOT NULL, INDEX IDX_8244AA3A9D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offers (id INT AUTO_INCREMENT NOT NULL, company_id_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, skills LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', end_date DATETIME NOT NULL, creationDate DATETIME NOT NULL, INDEX IDX_DA46042738B53C32 (company_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offers_users (offers_id INT NOT NULL, users_id INT NOT NULL, INDEX IDX_4FF36D3CA090B42E (offers_id), INDEX IDX_4FF36D3C67B3B43D (users_id), PRIMARY KEY(offers_id, users_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, age INT NOT NULL, zipcode INT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, skills LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', creationDate DATETIME NOT NULL, updateDate DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE companies ADD CONSTRAINT FK_8244AA3A9D86650F FOREIGN KEY (user_id_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE offers ADD CONSTRAINT FK_DA46042738B53C32 FOREIGN KEY (company_id_id) REFERENCES companies (id)');
        $this->addSql('ALTER TABLE offers_users ADD CONSTRAINT FK_4FF36D3CA090B42E FOREIGN KEY (offers_id) REFERENCES offers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offers_users ADD CONSTRAINT FK_4FF36D3C67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE companies DROP FOREIGN KEY FK_8244AA3A9D86650F');
        $this->addSql('ALTER TABLE offers DROP FOREIGN KEY FK_DA46042738B53C32');
        $this->addSql('ALTER TABLE offers_users DROP FOREIGN KEY FK_4FF36D3CA090B42E');
        $this->addSql('ALTER TABLE offers_users DROP FOREIGN KEY FK_4FF36D3C67B3B43D');
        $this->addSql('DROP TABLE companies');
        $this->addSql('DROP TABLE offers');
        $this->addSql('DROP TABLE offers_users');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
