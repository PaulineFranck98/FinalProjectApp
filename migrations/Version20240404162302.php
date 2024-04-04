<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240404162302 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commentary (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, post_id INT NOT NULL, content LONGTEXT NOT NULL, creation_date DATETIME NOT NULL, INDEX IDX_1CAC12CAA76ED395 (user_id), INDEX IDX_1CAC12CA4B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE companion (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE companion_place (companion_id INT NOT NULL, place_id INT NOT NULL, INDEX IDX_5DFDEF8227E3FD (companion_id), INDEX IDX_5DFDEFDA6A219 (place_id), PRIMARY KEY(companion_id, place_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, place_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_C53D045FDA6A219 (place_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE place (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, zipcode VARCHAR(255) NOT NULL, opening_hours LONGTEXT NOT NULL, website VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(20) DEFAULT NULL, description LONGTEXT DEFAULT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, is_verified TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, place_id INT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, image VARCHAR(255) DEFAULT NULL, creation_date DATETIME NOT NULL, is_closed TINYINT(1) NOT NULL, INDEX IDX_5A8A6C8DA76ED395 (user_id), INDEX IDX_5A8A6C8DDA6A219 (place_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rating (id INT AUTO_INCREMENT NOT NULL, place_id INT NOT NULL, user_id INT NOT NULL, rating INT NOT NULL, comment LONGTEXT DEFAULT NULL, rating_date DATETIME NOT NULL, INDEX IDX_D8892622DA6A219 (place_id), INDEX IDX_D8892622A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE theme (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE theme_place (theme_id INT NOT NULL, place_id INT NOT NULL, INDEX IDX_2C3FD82859027487 (theme_id), INDEX IDX_2C3FD828DA6A219 (place_id), PRIMARY KEY(theme_id, place_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_place (type_id INT NOT NULL, place_id INT NOT NULL, INDEX IDX_7A11D43AC54C8C93 (type_id), INDEX IDX_7A11D43ADA6A219 (place_id), PRIMARY KEY(type_id, place_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, username VARCHAR(255) NOT NULL, profile_picture VARCHAR(255) NOT NULL, is_banned TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_place (user_id INT NOT NULL, place_id INT NOT NULL, INDEX IDX_96DFA895A76ED395 (user_id), INDEX IDX_96DFA895DA6A219 (place_id), PRIMARY KEY(user_id, place_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentary ADD CONSTRAINT FK_1CAC12CAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentary ADD CONSTRAINT FK_1CAC12CA4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE companion_place ADD CONSTRAINT FK_5DFDEF8227E3FD FOREIGN KEY (companion_id) REFERENCES companion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE companion_place ADD CONSTRAINT FK_5DFDEFDA6A219 FOREIGN KEY (place_id) REFERENCES place (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FDA6A219 FOREIGN KEY (place_id) REFERENCES place (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DDA6A219 FOREIGN KEY (place_id) REFERENCES place (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D8892622DA6A219 FOREIGN KEY (place_id) REFERENCES place (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D8892622A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE theme_place ADD CONSTRAINT FK_2C3FD82859027487 FOREIGN KEY (theme_id) REFERENCES theme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE theme_place ADD CONSTRAINT FK_2C3FD828DA6A219 FOREIGN KEY (place_id) REFERENCES place (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE type_place ADD CONSTRAINT FK_7A11D43AC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE type_place ADD CONSTRAINT FK_7A11D43ADA6A219 FOREIGN KEY (place_id) REFERENCES place (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_place ADD CONSTRAINT FK_96DFA895A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_place ADD CONSTRAINT FK_96DFA895DA6A219 FOREIGN KEY (place_id) REFERENCES place (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentary DROP FOREIGN KEY FK_1CAC12CAA76ED395');
        $this->addSql('ALTER TABLE commentary DROP FOREIGN KEY FK_1CAC12CA4B89032C');
        $this->addSql('ALTER TABLE companion_place DROP FOREIGN KEY FK_5DFDEF8227E3FD');
        $this->addSql('ALTER TABLE companion_place DROP FOREIGN KEY FK_5DFDEFDA6A219');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FDA6A219');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA76ED395');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DDA6A219');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D8892622DA6A219');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D8892622A76ED395');
        $this->addSql('ALTER TABLE theme_place DROP FOREIGN KEY FK_2C3FD82859027487');
        $this->addSql('ALTER TABLE theme_place DROP FOREIGN KEY FK_2C3FD828DA6A219');
        $this->addSql('ALTER TABLE type_place DROP FOREIGN KEY FK_7A11D43AC54C8C93');
        $this->addSql('ALTER TABLE type_place DROP FOREIGN KEY FK_7A11D43ADA6A219');
        $this->addSql('ALTER TABLE user_place DROP FOREIGN KEY FK_96DFA895A76ED395');
        $this->addSql('ALTER TABLE user_place DROP FOREIGN KEY FK_96DFA895DA6A219');
        $this->addSql('DROP TABLE commentary');
        $this->addSql('DROP TABLE companion');
        $this->addSql('DROP TABLE companion_place');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE place');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE rating');
        $this->addSql('DROP TABLE theme');
        $this->addSql('DROP TABLE theme_place');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE type_place');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_place');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
