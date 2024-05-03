<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240430203445 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fichier (id INT AUTO_INCREMENT NOT NULL, date_ajout DATE DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, path VARCHAR(255) DEFAULT NULL, idMember VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE follow_notification (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, follower_id INT NOT NULL, content VARCHAR(255) DEFAULT NULL, seen TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_361D9872A76ED395 (user_id), INDEX IDX_361D9872AC24F853 (follower_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matiere (id INT AUTO_INCREMENT NOT NULL, niveau INT DEFAULT NULL, matiere VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_token (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, token VARCHAR(255) DEFAULT NULL, expires_at DATETIME DEFAULT NULL, INDEX IDX_452C9EC5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tache (id INT AUTO_INCREMENT NOT NULL, idMember VARCHAR(255) NOT NULL, etat VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, date_ajout DATE DEFAULT NULL, dedline DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_follows (user_id INT NOT NULL, followed_user_id INT NOT NULL, INDEX IDX_136E9479A76ED395 (user_id), INDEX IDX_136E9479AF2612FD (followed_user_id), PRIMARY KEY(user_id, followed_user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_notification (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE validation_code (id INT AUTO_INCREMENT NOT NULL, reset_code INT DEFAULT NULL, expires_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE follow_notification ADD CONSTRAINT FK_361D9872A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE follow_notification ADD CONSTRAINT FK_361D9872AC24F853 FOREIGN KEY (follower_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reset_password_token ADD CONSTRAINT FK_452C9EC5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_follows ADD CONSTRAINT FK_136E9479A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_follows ADD CONSTRAINT FK_136E9479AF2612FD FOREIGN KEY (followed_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE password_reset_request DROP FOREIGN KEY FK_C5D0A95AA76ED395');
        $this->addSql('ALTER TABLE remember_me_token DROP FOREIGN KEY FK_89FEBAD0A76ED395');
        $this->addSql('DROP TABLE password_reset_request');
        $this->addSql('DROP TABLE remember_me_token');
        $this->addSql('ALTER TABLE project DROP start_date, DROP end_date');
        $this->addSql('ALTER TABLE user CHANGE date_naissance date_naissance DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE password_reset_request (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, reset_code INT DEFAULT NULL, expires_at DATETIME DEFAULT NULL, INDEX IDX_C5D0A95AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE remember_me_token (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, token VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, expires_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_89FEBAD0A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE password_reset_request ADD CONSTRAINT FK_C5D0A95AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE remember_me_token ADD CONSTRAINT FK_89FEBAD0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE follow_notification DROP FOREIGN KEY FK_361D9872A76ED395');
        $this->addSql('ALTER TABLE follow_notification DROP FOREIGN KEY FK_361D9872AC24F853');
        $this->addSql('ALTER TABLE reset_password_token DROP FOREIGN KEY FK_452C9EC5A76ED395');
        $this->addSql('ALTER TABLE user_follows DROP FOREIGN KEY FK_136E9479A76ED395');
        $this->addSql('ALTER TABLE user_follows DROP FOREIGN KEY FK_136E9479AF2612FD');
        $this->addSql('DROP TABLE fichier');
        $this->addSql('DROP TABLE follow_notification');
        $this->addSql('DROP TABLE matiere');
        $this->addSql('DROP TABLE reset_password_token');
        $this->addSql('DROP TABLE tache');
        $this->addSql('DROP TABLE user_follows');
        $this->addSql('DROP TABLE user_notification');
        $this->addSql('DROP TABLE validation_code');
        $this->addSql('ALTER TABLE project ADD start_date DATE NOT NULL, ADD end_date DATE NOT NULL');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
        $this->addSql('ALTER TABLE user CHANGE date_naissance date_naissance DATETIME DEFAULT NULL');
    }
}
