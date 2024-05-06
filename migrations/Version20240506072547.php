<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240506072547 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activite (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE candidature (id INT AUTO_INCREMENT NOT NULL, offre_id INT DEFAULT NULL, user_id INT DEFAULT NULL, date DATE NOT NULL, status VARCHAR(255) NOT NULL, competences VARCHAR(255) NOT NULL, cv VARCHAR(255) NOT NULL, INDEX IDX_E33BD3B84CC8505A (offre_id), INDEX IDX_E33BD3B8A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cours (id INT AUTO_INCREMENT NOT NULL, teacher_id INT DEFAULT NULL, nom_cours VARCHAR(255) DEFAULT NULL, nom_module VARCHAR(255) DEFAULT NULL, niveau INT DEFAULT NULL, coursURLpdf VARCHAR(255) DEFAULT NULL, INDEX IDX_FDCA8C9C41807E1D (teacher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evaluation (id INT AUTO_INCREMENT NOT NULL, cours_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1323A5757ECF78B0 (cours_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_comments (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, event_id INT DEFAULT NULL, comment_text LONGTEXT DEFAULT NULL, created_at DATE NOT NULL, INDEX IDX_19727FFAA76ED395 (user_id), INDEX IDX_19727FFA71F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_reactions (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, event_id INT DEFAULT NULL, reaction_type VARCHAR(255) DEFAULT NULL, created_at DATE NOT NULL, INDEX IDX_BEE6418EA76ED395 (user_id), INDEX IDX_BEE6418E71F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE events (id INT AUTO_INCREMENT NOT NULL, admin_id INT DEFAULT NULL, event_name VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, event_date DATE DEFAULT NULL, created_at DATE DEFAULT NULL, event_photo VARCHAR(255) NOT NULL, INDEX IDX_5387574A642B8210 (admin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE events_user (events_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_E1C3D2339D6A1065 (events_id), INDEX IDX_E1C3D233A76ED395 (user_id), PRIMARY KEY(events_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fichier (id INT AUTO_INCREMENT NOT NULL, date_ajout DATE DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, path VARCHAR(255) DEFAULT NULL, idMember INT DEFAULT NULL, INDEX IDX_9B76551F13F552E2 (idMember), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE file (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, uploaded_by_id INT DEFAULT NULL, file_name VARCHAR(255) NOT NULL, file_path VARCHAR(255) NOT NULL, uploaded_at DATE NOT NULL, INDEX IDX_8C9F3610166D1F9C (project_id), INDEX IDX_8C9F3610A2B28FE8 (uploaded_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE follow_notification (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, follower_id INT NOT NULL, content VARCHAR(255) DEFAULT NULL, seen TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_361D9872A76ED395 (user_id), INDEX IDX_361D9872AC24F853 (follower_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inscription (id INT AUTO_INCREMENT NOT NULL, etudiant_id INT DEFAULT NULL, activite_id INT DEFAULT NULL, date DATE DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, num_tel VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, INDEX IDX_5E90F6D6DDEAB1A3 (etudiant_id), INDEX IDX_5E90F6D69B0F88B1 (activite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matiere (id INT AUTO_INCREMENT NOT NULL, niveau INT DEFAULT NULL, matiere VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, sender_id INT DEFAULT NULL, content LONGTEXT NOT NULL, created_at DATE DEFAULT NULL, INDEX IDX_B6BD307F166D1F9C (project_id), INDEX IDX_B6BD307FF624B39D (sender_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notifications (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, event_id INT DEFAULT NULL, notification_text LONGTEXT NOT NULL, notification_time DATE NOT NULL, INDEX IDX_6000B0D3A76ED395 (user_id), INDEX IDX_6000B0D371F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre (id INT AUTO_INCREMENT NOT NULL, entreprise_id INT NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, competences VARCHAR(255) NOT NULL, nbr VARCHAR(255) NOT NULL, date DATE NOT NULL, INDEX IDX_AF86866FA4AEAFEA (entreprise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `option` (id INT AUTO_INCREMENT NOT NULL, question_id INT DEFAULT NULL, valeur LONGTEXT NOT NULL, is_correct TINYINT(1) DEFAULT NULL, INDEX IDX_5A8600B01E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE option_user (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, option_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paiement (id INT AUTO_INCREMENT NOT NULL, inscription_id INT DEFAULT NULL, montant DOUBLE PRECISION DEFAULT NULL, date DATE DEFAULT NULL, UNIQUE INDEX UNIQ_B1DC7A1E5DAC5993 (inscription_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, teacher_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, created_at DATE NOT NULL, updated_at DATE NOT NULL, classe VARCHAR(255) NOT NULL, matiere VARCHAR(255) NOT NULL, INDEX IDX_2FB3D0EE41807E1D (teacher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_members (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, joined_at DATE NOT NULL, INDEX IDX_D3BEDE9A166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_members_user (project_members_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_E85E128FA5A78EDF (project_members_id), INDEX IDX_E85E128FA76ED395 (user_id), PRIMARY KEY(project_members_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publication (id INT AUTO_INCREMENT NOT NULL, club_rh_id INT DEFAULT NULL, date DATE DEFAULT NULL, contenu LONGTEXT DEFAULT NULL, INDEX IDX_AF3C677982CCADCE (club_rh_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, evaluation_id INT DEFAULT NULL, valeur LONGTEXT DEFAULT NULL, INDEX IDX_B6F7494E456C5646 (evaluation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_token (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, token VARCHAR(255) DEFAULT NULL, expires_at DATETIME DEFAULT NULL, INDEX IDX_452C9EC5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tache (id INT AUTO_INCREMENT NOT NULL, etat VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, date_ajout DATE DEFAULT NULL, dedline DATE DEFAULT NULL, idMember INT DEFAULT NULL, INDEX IDX_9387207513F552E2 (idMember), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, role VARCHAR(255) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, classe VARCHAR(255) DEFAULT NULL, pays VARCHAR(255) DEFAULT NULL, localisation VARCHAR(255) DEFAULT NULL, cin VARCHAR(255) DEFAULT NULL, niveau INT DEFAULT NULL, genre VARCHAR(255) DEFAULT NULL, date_naissance DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', profil_picture VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, is_enabled TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_follows (user_id INT NOT NULL, followed_user_id INT NOT NULL, INDEX IDX_136E9479A76ED395 (user_id), INDEX IDX_136E9479AF2612FD (followed_user_id), PRIMARY KEY(user_id, followed_user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_notification (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE validation_code (id INT AUTO_INCREMENT NOT NULL, reset_code INT DEFAULT NULL, expires_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B84CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id)');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9C41807E1D FOREIGN KEY (teacher_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A5757ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id)');
        $this->addSql('ALTER TABLE event_comments ADD CONSTRAINT FK_19727FFAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_comments ADD CONSTRAINT FK_19727FFA71F7E88B FOREIGN KEY (event_id) REFERENCES events (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_reactions ADD CONSTRAINT FK_BEE6418EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_reactions ADD CONSTRAINT FK_BEE6418E71F7E88B FOREIGN KEY (event_id) REFERENCES events (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE events ADD CONSTRAINT FK_5387574A642B8210 FOREIGN KEY (admin_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE events_user ADD CONSTRAINT FK_E1C3D2339D6A1065 FOREIGN KEY (events_id) REFERENCES events (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE events_user ADD CONSTRAINT FK_E1C3D233A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fichier ADD CONSTRAINT FK_9B76551F13F552E2 FOREIGN KEY (idMember) REFERENCES project_members (id)');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F3610166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F3610A2B28FE8 FOREIGN KEY (uploaded_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE follow_notification ADD CONSTRAINT FK_361D9872A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE follow_notification ADD CONSTRAINT FK_361D9872AC24F853 FOREIGN KEY (follower_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D6DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D69B0F88B1 FOREIGN KEY (activite_id) REFERENCES activite (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notifications ADD CONSTRAINT FK_6000B0D3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notifications ADD CONSTRAINT FK_6000B0D371F7E88B FOREIGN KEY (event_id) REFERENCES events (id)');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866FA4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `option` ADD CONSTRAINT FK_5A8600B01E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1E5DAC5993 FOREIGN KEY (inscription_id) REFERENCES inscription (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE41807E1D FOREIGN KEY (teacher_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE project_members ADD CONSTRAINT FK_D3BEDE9A166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE project_members_user ADD CONSTRAINT FK_E85E128FA5A78EDF FOREIGN KEY (project_members_id) REFERENCES project_members (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_members_user ADD CONSTRAINT FK_E85E128FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C677982CCADCE FOREIGN KEY (club_rh_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E456C5646 FOREIGN KEY (evaluation_id) REFERENCES evaluation (id)');
        $this->addSql('ALTER TABLE reset_password_token ADD CONSTRAINT FK_452C9EC5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tache ADD CONSTRAINT FK_9387207513F552E2 FOREIGN KEY (idMember) REFERENCES project_members (id)');
        $this->addSql('ALTER TABLE user_follows ADD CONSTRAINT FK_136E9479A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_follows ADD CONSTRAINT FK_136E9479AF2612FD FOREIGN KEY (followed_user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B84CC8505A');
        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B8A76ED395');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9C41807E1D');
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A5757ECF78B0');
        $this->addSql('ALTER TABLE event_comments DROP FOREIGN KEY FK_19727FFAA76ED395');
        $this->addSql('ALTER TABLE event_comments DROP FOREIGN KEY FK_19727FFA71F7E88B');
        $this->addSql('ALTER TABLE event_reactions DROP FOREIGN KEY FK_BEE6418EA76ED395');
        $this->addSql('ALTER TABLE event_reactions DROP FOREIGN KEY FK_BEE6418E71F7E88B');
        $this->addSql('ALTER TABLE events DROP FOREIGN KEY FK_5387574A642B8210');
        $this->addSql('ALTER TABLE events_user DROP FOREIGN KEY FK_E1C3D2339D6A1065');
        $this->addSql('ALTER TABLE events_user DROP FOREIGN KEY FK_E1C3D233A76ED395');
        $this->addSql('ALTER TABLE fichier DROP FOREIGN KEY FK_9B76551F13F552E2');
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F3610166D1F9C');
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F3610A2B28FE8');
        $this->addSql('ALTER TABLE follow_notification DROP FOREIGN KEY FK_361D9872A76ED395');
        $this->addSql('ALTER TABLE follow_notification DROP FOREIGN KEY FK_361D9872AC24F853');
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY FK_5E90F6D6DDEAB1A3');
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY FK_5E90F6D69B0F88B1');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F166D1F9C');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF624B39D');
        $this->addSql('ALTER TABLE notifications DROP FOREIGN KEY FK_6000B0D3A76ED395');
        $this->addSql('ALTER TABLE notifications DROP FOREIGN KEY FK_6000B0D371F7E88B');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866FA4AEAFEA');
        $this->addSql('ALTER TABLE `option` DROP FOREIGN KEY FK_5A8600B01E27F6BF');
        $this->addSql('ALTER TABLE paiement DROP FOREIGN KEY FK_B1DC7A1E5DAC5993');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE41807E1D');
        $this->addSql('ALTER TABLE project_members DROP FOREIGN KEY FK_D3BEDE9A166D1F9C');
        $this->addSql('ALTER TABLE project_members_user DROP FOREIGN KEY FK_E85E128FA5A78EDF');
        $this->addSql('ALTER TABLE project_members_user DROP FOREIGN KEY FK_E85E128FA76ED395');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C677982CCADCE');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E456C5646');
        $this->addSql('ALTER TABLE reset_password_token DROP FOREIGN KEY FK_452C9EC5A76ED395');
        $this->addSql('ALTER TABLE tache DROP FOREIGN KEY FK_9387207513F552E2');
        $this->addSql('ALTER TABLE user_follows DROP FOREIGN KEY FK_136E9479A76ED395');
        $this->addSql('ALTER TABLE user_follows DROP FOREIGN KEY FK_136E9479AF2612FD');
        $this->addSql('DROP TABLE activite');
        $this->addSql('DROP TABLE candidature');
        $this->addSql('DROP TABLE cours');
        $this->addSql('DROP TABLE evaluation');
        $this->addSql('DROP TABLE event_comments');
        $this->addSql('DROP TABLE event_reactions');
        $this->addSql('DROP TABLE events');
        $this->addSql('DROP TABLE events_user');
        $this->addSql('DROP TABLE fichier');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP TABLE follow_notification');
        $this->addSql('DROP TABLE inscription');
        $this->addSql('DROP TABLE matiere');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE notifications');
        $this->addSql('DROP TABLE offre');
        $this->addSql('DROP TABLE `option`');
        $this->addSql('DROP TABLE option_user');
        $this->addSql('DROP TABLE paiement');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE project_members');
        $this->addSql('DROP TABLE project_members_user');
        $this->addSql('DROP TABLE publication');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE reset_password_token');
        $this->addSql('DROP TABLE tache');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_follows');
        $this->addSql('DROP TABLE user_notification');
        $this->addSql('DROP TABLE validation_code');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
