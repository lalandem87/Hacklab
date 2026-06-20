<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260620110327 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE task_question (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(80) NOT NULL, answer VARCHAR(80) NOT NULL, question_order INT NOT NULL, task_id INT DEFAULT NULL, INDEX IDX_F637A3028DB60186 (task_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE task_question ADD CONSTRAINT FK_F637A3028DB60186 FOREIGN KEY (task_id) REFERENCES task (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task_question DROP FOREIGN KEY FK_F637A3028DB60186');
        $this->addSql('DROP TABLE task_question');
    }
}
