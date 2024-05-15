<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240506082753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fichier CHANGE idMember idMember INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fichier ADD CONSTRAINT FK_9B76551F13F552E2 FOREIGN KEY (idMember) REFERENCES project_members (id)');
        $this->addSql('CREATE INDEX IDX_9B76551F13F552E2 ON fichier (idMember)');
        $this->addSql('ALTER TABLE inscription CHANGE num_tel num_tel VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE project ADD classe VARCHAR(255) NOT NULL, ADD matiere VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE project_members DROP is_owner');
        $this->addSql('ALTER TABLE tache CHANGE idMember idMember INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tache ADD CONSTRAINT FK_9387207513F552E2 FOREIGN KEY (idMember) REFERENCES project_members (id)');
        $this->addSql('CREATE INDEX IDX_9387207513F552E2 ON tache (idMember)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fichier DROP FOREIGN KEY FK_9B76551F13F552E2');
        $this->addSql('DROP INDEX IDX_9B76551F13F552E2 ON fichier');
        $this->addSql('ALTER TABLE fichier CHANGE idMember idMember VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE inscription CHANGE num_tel num_tel VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE project DROP classe, DROP matiere');
        $this->addSql('ALTER TABLE project_members ADD is_owner TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE tache DROP FOREIGN KEY FK_9387207513F552E2');
        $this->addSql('DROP INDEX IDX_9387207513F552E2 ON tache');
        $this->addSql('ALTER TABLE tache CHANGE idMember idMember VARCHAR(255) NOT NULL');
    }
}
