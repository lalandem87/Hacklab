<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260701161156 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_task (id INT AUTO_INCREMENT NOT NULL, solved TINYINT NOT NULL, usr_id INT DEFAULT NULL, task_id INT DEFAULT NULL, INDEX IDX_28FF97ECC69D3FB (usr_id), INDEX IDX_28FF97EC8DB60186 (task_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user_task_question (id INT AUTO_INCREMENT NOT NULL, solved TINYINT NOT NULL, usr_id INT DEFAULT NULL, question_id INT DEFAULT NULL, INDEX IDX_18B90E52C69D3FB (usr_id), INDEX IDX_18B90E521E27F6BF (question_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE user_task ADD CONSTRAINT FK_28FF97ECC69D3FB FOREIGN KEY (usr_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_task ADD CONSTRAINT FK_28FF97EC8DB60186 FOREIGN KEY (task_id) REFERENCES task (id)');
        $this->addSql('ALTER TABLE user_task_question ADD CONSTRAINT FK_18B90E52C69D3FB FOREIGN KEY (usr_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_task_question ADD CONSTRAINT FK_18B90E521E27F6BF FOREIGN KEY (question_id) REFERENCES task_question (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_task DROP FOREIGN KEY FK_28FF97ECC69D3FB');
        $this->addSql('ALTER TABLE user_task DROP FOREIGN KEY FK_28FF97EC8DB60186');
        $this->addSql('ALTER TABLE user_task_question DROP FOREIGN KEY FK_18B90E52C69D3FB');
        $this->addSql('ALTER TABLE user_task_question DROP FOREIGN KEY FK_18B90E521E27F6BF');
        $this->addSql('DROP TABLE user_task');
        $this->addSql('DROP TABLE user_task_question');
    }
}
