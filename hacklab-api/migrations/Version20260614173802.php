<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260614173802 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie CHANGE name name VARCHAR(20) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_497DD6345E237E06 ON categorie (name)');
        $this->addSql('ALTER TABLE certification CHANGE name name VARCHAR(20) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6C3C6D755E237E06 ON certification (name)');
        $this->addSql('ALTER TABLE challenge CHANGE name name VARCHAR(40) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D70989515E237E06 ON challenge (name)');
        $this->addSql('ALTER TABLE course CHANGE name name VARCHAR(40) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_169E6FB95E237E06 ON course (name)');
        $this->addSql('ALTER TABLE module CHANGE name name VARCHAR(60) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C2426285E237E06 ON module (name)');
        $this->addSql('ALTER TABLE user CHANGE gamertag gamertag VARCHAR(25) NOT NULL');
        $this->addSql('ALTER TABLE user_module CHANGE submitted_flag submitted_flag VARCHAR(100) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_497DD6345E237E06 ON categorie');
        $this->addSql('ALTER TABLE categorie CHANGE name name VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX UNIQ_6C3C6D755E237E06 ON certification');
        $this->addSql('ALTER TABLE certification CHANGE name name VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX UNIQ_D70989515E237E06 ON challenge');
        $this->addSql('ALTER TABLE challenge CHANGE name name VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX UNIQ_169E6FB95E237E06 ON course');
        $this->addSql('ALTER TABLE course CHANGE name name VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX UNIQ_C2426285E237E06 ON module');
        $this->addSql('ALTER TABLE module CHANGE name name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE gamertag gamertag VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user_module CHANGE submitted_flag submitted_flag VARCHAR(255) DEFAULT NULL');
    }
}
