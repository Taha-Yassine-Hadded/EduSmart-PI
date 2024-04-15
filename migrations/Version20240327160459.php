<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240327160459 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        /* this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evaluation_user DROP FOREIGN KEY fk_evaluation');
     $this->addSql('ALTER TABLE evaluation_user DROP FOREIGN KEY fk_use');
        $this->addSql('ALTER TABLE option_user DROP FOREIGN KEY fk_option');
        $this->addSql('ALTER TABLE option_user DROP FOREIGN KEY fk_user');
        $this->addSql('ALTER TABLE remember_me_token DROP FOREIGN KEY remember_me_token_ibfk_1');
        $this->addSql('DROP TABLE evaluation_user');
        $this->addSql('DROP TABLE option_user');
        $this->addSql('DROP TABLE remember_me_token');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9C41807E1D');*/
        $this->addSql('ALTER TABLE cours ADD cours_urlpdf VARCHAR(255) DEFAULT NULL, DROP coursURLpdf');
       /* $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9C41807E1D FOREIGN KEY (teacher_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE evaluation DROP INDEX FK_1323A5757ECF78B0, ADD UNIQUE INDEX UNIQ_1323A5757ECF78B0 (cours_id)');
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A5757ECF78B0');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A5757ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id)');
        $this->addSql('ALTER TABLE event_comments DROP FOREIGN KEY FK_19727FFAA76ED395');
        $this->addSql('ALTER TABLE event_comments DROP FOREIGN KEY FK_19727FFA71F7E88B');
        $this->addSql('ALTER TABLE event_comments ADD CONSTRAINT FK_19727FFAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_comments ADD CONSTRAINT FK_19727FFA71F7E88B FOREIGN KEY (event_id) REFERENCES events (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_reactions DROP FOREIGN KEY FK_BEE6418E71F7E88B');
        $this->addSql('ALTER TABLE event_reactions DROP FOREIGN KEY FK_BEE6418EA76ED395');
        $this->addSql('ALTER TABLE event_reactions ADD CONSTRAINT FK_BEE6418E71F7E88B FOREIGN KEY (event_id) REFERENCES events (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_reactions ADD CONSTRAINT FK_BEE6418EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE events DROP FOREIGN KEY FK_5387574A642B8210');
        $this->addSql('ALTER TABLE events ADD CONSTRAINT FK_5387574A642B8210 FOREIGN KEY (admin_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `option` DROP FOREIGN KEY FK_5A8600B01E27F6BF');
        $this->addSql('ALTER TABLE `option` ADD CONSTRAINT FK_5A8600B01E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE password_reset_request DROP created_at, DROP is_valid');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E456C5646');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E456C5646 FOREIGN KEY (evaluation_id) REFERENCES evaluation (id)');
        $this->addSql('ALTER TABLE user CHANGE date_naissance date_naissance DATETIME DEFAULT NULL');*/
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
      /*  $this->addSql('CREATE TABLE evaluation_user (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, evaluation_id INT NOT NULL, INDEX fk_use (user_id), INDEX fk_evaluation (evaluation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE option_user (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, option_id INT NOT NULL, INDEX fk_user (user_id), INDEX fk_option (option_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE remember_me_token (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, token VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, expires_at DATETIME DEFAULT NULL, INDEX user_id (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE evaluation_user ADD CONSTRAINT fk_evaluation FOREIGN KEY (evaluation_id) REFERENCES evaluation (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evaluation_user ADD CONSTRAINT fk_use FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE option_user ADD CONSTRAINT fk_option FOREIGN KEY (option_id) REFERENCES `option` (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE option_user ADD CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE remember_me_token ADD CONSTRAINT remember_me_token_ibfk_1 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9C41807E1D');*/
        $this->addSql('ALTER TABLE cours ADD coursURLpdf VARCHAR(255) NOT NULL, DROP cours_urlpdf');
       /* $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9C41807E1D FOREIGN KEY (teacher_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evaluation DROP INDEX UNIQ_1323A5757ECF78B0, ADD INDEX FK_1323A5757ECF78B0 (cours_id)');
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A5757ECF78B0');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A5757ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE events DROP FOREIGN KEY FK_5387574A642B8210');
        $this->addSql('ALTER TABLE events ADD CONSTRAINT FK_5387574A642B8210 FOREIGN KEY (admin_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE event_comments DROP FOREIGN KEY FK_19727FFAA76ED395');
        $this->addSql('ALTER TABLE event_comments DROP FOREIGN KEY FK_19727FFA71F7E88B');
        $this->addSql('ALTER TABLE event_comments ADD CONSTRAINT FK_19727FFAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE event_comments ADD CONSTRAINT FK_19727FFA71F7E88B FOREIGN KEY (event_id) REFERENCES events (id)');
        $this->addSql('ALTER TABLE event_reactions DROP FOREIGN KEY FK_BEE6418EA76ED395');
        $this->addSql('ALTER TABLE event_reactions DROP FOREIGN KEY FK_BEE6418E71F7E88B');
        $this->addSql('ALTER TABLE event_reactions ADD CONSTRAINT FK_BEE6418EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE event_reactions ADD CONSTRAINT FK_BEE6418E71F7E88B FOREIGN KEY (event_id) REFERENCES events (id)');
        $this->addSql('ALTER TABLE `option` DROP FOREIGN KEY FK_5A8600B01E27F6BF');
        $this->addSql('ALTER TABLE `option` ADD CONSTRAINT FK_5A8600B01E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE password_reset_request ADD created_at DATETIME DEFAULT NULL, ADD is_valid TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E456C5646');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E456C5646 FOREIGN KEY (evaluation_id) REFERENCES evaluation (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user CHANGE date_naissance date_naissance DATE DEFAULT NULL');*/
    }
}
