<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260606150425 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE certification (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE challenge (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, point INT NOT NULL, flag VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE course (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, point INT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE course_image (id INT AUTO_INCREMENT NOT NULL, image_url VARCHAR(255) NOT NULL, course_id INT DEFAULT NULL, INDEX IDX_2C9603B7591CC992 (course_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE module (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, course_id INT DEFAULT NULL, challenge_id INT DEFAULT NULL, categorie_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_C242628591CC992 (course_id), UNIQUE INDEX UNIQ_C24262898A21AC6 (challenge_id), INDEX IDX_C242628BCF5E72D (categorie_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, gamertag VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, point_earn INT NOT NULL, UNIQUE INDEX UNIQ_8D93D6494A8BD3A5 (gamertag), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user_certification (id INT AUTO_INCREMENT NOT NULL, certification_id INT DEFAULT NULL, usr_id INT DEFAULT NULL, INDEX IDX_82B2C025CB47068A (certification_id), INDEX IDX_82B2C025C69D3FB (usr_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user_module (id INT AUTO_INCREMENT NOT NULL, submitted_flag VARCHAR(255) DEFAULT NULL, solved TINYINT NOT NULL, module_id INT DEFAULT NULL, usr_id INT DEFAULT NULL, INDEX IDX_69763D15AFC2B591 (module_id), INDEX IDX_69763D15C69D3FB (usr_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE course_image ADD CONSTRAINT FK_2C9603B7591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('ALTER TABLE module ADD CONSTRAINT FK_C242628591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('ALTER TABLE module ADD CONSTRAINT FK_C24262898A21AC6 FOREIGN KEY (challenge_id) REFERENCES challenge (id)');
        $this->addSql('ALTER TABLE module ADD CONSTRAINT FK_C242628BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE user_certification ADD CONSTRAINT FK_82B2C025CB47068A FOREIGN KEY (certification_id) REFERENCES certification (id)');
        $this->addSql('ALTER TABLE user_certification ADD CONSTRAINT FK_82B2C025C69D3FB FOREIGN KEY (usr_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_module ADD CONSTRAINT FK_69763D15AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
        $this->addSql('ALTER TABLE user_module ADD CONSTRAINT FK_69763D15C69D3FB FOREIGN KEY (usr_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course_image DROP FOREIGN KEY FK_2C9603B7591CC992');
        $this->addSql('ALTER TABLE module DROP FOREIGN KEY FK_C242628591CC992');
        $this->addSql('ALTER TABLE module DROP FOREIGN KEY FK_C24262898A21AC6');
        $this->addSql('ALTER TABLE module DROP FOREIGN KEY FK_C242628BCF5E72D');
        $this->addSql('ALTER TABLE user_certification DROP FOREIGN KEY FK_82B2C025CB47068A');
        $this->addSql('ALTER TABLE user_certification DROP FOREIGN KEY FK_82B2C025C69D3FB');
        $this->addSql('ALTER TABLE user_module DROP FOREIGN KEY FK_69763D15AFC2B591');
        $this->addSql('ALTER TABLE user_module DROP FOREIGN KEY FK_69763D15C69D3FB');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE certification');
        $this->addSql('DROP TABLE challenge');
        $this->addSql('DROP TABLE course');
        $this->addSql('DROP TABLE course_image');
        $this->addSql('DROP TABLE module');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_certification');
        $this->addSql('DROP TABLE user_module');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
