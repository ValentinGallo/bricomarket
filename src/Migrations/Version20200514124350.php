<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200514124350 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, tool_id INT DEFAULT NULL, workstation_id INT DEFAULT NULL, text VARCHAR(255) NOT NULL, INDEX IDX_9474526CA76ED395 (user_id), INDEX IDX_9474526C8F7B22CC (tool_id), INDEX IDX_9474526CE29BB7D (workstation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE department (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, sender_id INT NOT NULL, receiver_id INT NOT NULL, text VARCHAR(255) NOT NULL, date DATE NOT NULL, is_read TINYINT(1) NOT NULL, INDEX IDX_B6BD307FF624B39D (sender_id), INDEX IDX_B6BD307FCD53EDB6 (receiver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, workstation_id INT NOT NULL, date DATE NOT NULL, create_time DATE NOT NULL, INDEX IDX_42C84955A76ED395 (user_id), INDEX IDX_42C84955E29BB7D (workstation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tool (id INT AUTO_INCREMENT NOT NULL, creator_id INT NOT NULL, department_id INT NOT NULL, category_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, text VARCHAR(255) NOT NULL, picture VARCHAR(255) DEFAULT NULL, prices INT DEFAULT NULL, date DATE NOT NULL, address VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_20F33ED161220EA6 (creator_id), INDEX IDX_20F33ED1AE80F5DF (department_id), INDEX IDX_20F33ED112469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE workstation (id INT AUTO_INCREMENT NOT NULL, department_id INT NOT NULL, name VARCHAR(255) NOT NULL, text VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, INDEX IDX_CDF3D910AE80F5DF (department_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C8F7B22CC FOREIGN KEY (tool_id) REFERENCES tool (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CE29BB7D FOREIGN KEY (workstation_id) REFERENCES workstation (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FCD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955E29BB7D FOREIGN KEY (workstation_id) REFERENCES workstation (id)');
        $this->addSql('ALTER TABLE tool ADD CONSTRAINT FK_20F33ED161220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tool ADD CONSTRAINT FK_20F33ED1AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE tool ADD CONSTRAINT FK_20F33ED112469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE workstation ADD CONSTRAINT FK_CDF3D910AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tool DROP FOREIGN KEY FK_20F33ED112469DE2');
        $this->addSql('ALTER TABLE tool DROP FOREIGN KEY FK_20F33ED1AE80F5DF');
        $this->addSql('ALTER TABLE workstation DROP FOREIGN KEY FK_CDF3D910AE80F5DF');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C8F7B22CC');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CE29BB7D');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955E29BB7D');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE department');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE tool');
        $this->addSql('DROP TABLE workstation');
    }
}
