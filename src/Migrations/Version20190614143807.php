<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190614143807 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE livres (id INT AUTO_INCREMENT NOT NULL, categorie_id INT NOT NULL, user_id INT NOT NULL, titre VARCHAR(255) NOT NULL, photo VARCHAR(255) NOT NULL, extract LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_927187A4BCF5E72D (categorie_id), INDEX IDX_927187A4A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, alt VARCHAR(255) DEFAULT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('CREATE TABLE imagesAdmin (id INT AUTO_INCREMENT NOT NULL, editeur_id INT DEFAULT NULL, alt VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, interView_id INT DEFAULT NULL, INDEX IDX_2EE463963375BD21 (editeur_id), INDEX IDX_2EE463969A6BA409 (interView_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('CREATE TABLE articles (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, text LONGTEXT NOT NULL, file_image VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE private_message (id INT AUTO_INCREMENT NOT NULL, user_sender_id INT DEFAULT NULL, user_receiver_id INT NOT NULL, msg LONGTEXT NOT NULL, is_head TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL, is_view TINYINT(1) DEFAULT NULL, INDEX IDX_4744FC9BF6C43E79 (user_sender_id), INDEX IDX_4744FC9B64482423 (user_receiver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_paypal (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, transaction_id VARCHAR(255) DEFAULT NULL, payment_amount DOUBLE PRECISION DEFAULT NULL, payment_status VARCHAR(255) NOT NULL, invoice_id VARCHAR(255) DEFAULT NULL, payment_date_at DATETIME NOT NULL, INDEX IDX_41AE99DAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE child_comment (id INT AUTO_INCREMENT NOT NULL, comment_id INT NOT NULL, user_id INT NOT NULL, content LONGTEXT NOT NULL, create_at DATETIME NOT NULL, INDEX IDX_F6C01A77F8697D13 (comment_id), INDEX IDX_F6C01A77A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE interView (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, bibliographyOne LONGTEXT NOT NULL, bibliographyTwo LONGTEXT NOT NULL, image_file VARCHAR(255) NOT NULL, url VARCHAR(100) DEFAULT NULL, create_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('CREATE TABLE forum_reply (id INT AUTO_INCREMENT NOT NULL, forum_id INT DEFAULT NULL, user_post_id INT DEFAULT NULL, message LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_E5DC603729CCBAD0 (forum_id), INDEX IDX_E5DC603713841D26 (user_post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, date_registration DATETIME NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', reset_token VARCHAR(255) DEFAULT NULL, is_livre VARCHAR(255) DEFAULT NULL, subscription_end DATE DEFAULT NULL, payer_id VARCHAR(255) DEFAULT NULL, paypal_profil_id VARCHAR(255) DEFAULT NULL, payer_status VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, categorie VARCHAR(255) NOT NULL, photo VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE editeur (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, title_editor VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, url VARCHAR(255) DEFAULT NULL, plug LONGTEXT NOT NULL, file_image_logo VARCHAR(255) NOT NULL, file_image_profil VARCHAR(255) NOT NULL, create_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, livre_id INT NOT NULL, user_id INT NOT NULL, content LONGTEXT NOT NULL, rate INT NOT NULL, createAt DATETIME NOT NULL, rapport TINYINT(1) DEFAULT NULL, INDEX IDX_9474526C37D925CB (livre_id), INDEX IDX_9474526CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('CREATE TABLE forum (id INT AUTO_INCREMENT NOT NULL, forums_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_852BBECD618BA34B (forums_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, editeur_id INT DEFAULT NULL, title VARCHAR(191) DEFAULT NULL, question VARCHAR(255) NOT NULL, response LONGTEXT NOT NULL, interView_id INT DEFAULT NULL, INDEX IDX_B6F7494E3375BD21 (editeur_id), INDEX IDX_B6F7494E9A6BA409 (interView_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('CREATE TABLE profil_user (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, pseudo VARCHAR(255) NOT NULL, presentation LONGTEXT NOT NULL, age INT NOT NULL, photo VARCHAR(255) DEFAULT NULL, adress VARCHAR(255) DEFAULT NULL, code_postale INT DEFAULT NULL, ville VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, telephone INT DEFAULT NULL, UNIQUE INDEX UNIQ_85CBC6ABA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE appointment (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, date_at DATETIME NOT NULL, content LONGTEXT NOT NULL, photo VARCHAR(255) NOT NULL, adress VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quote (id INT AUTO_INCREMENT NOT NULL, quote VARCHAR(150) NOT NULL, auteur VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE livres ADD CONSTRAINT FK_927187A4BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE livres ADD CONSTRAINT FK_927187A4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE imagesAdmin ADD CONSTRAINT FK_2EE463963375BD21 FOREIGN KEY (editeur_id) REFERENCES editeur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE imagesAdmin ADD CONSTRAINT FK_2EE463969A6BA409 FOREIGN KEY (interView_id) REFERENCES interView (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE private_message ADD CONSTRAINT FK_4744FC9BF6C43E79 FOREIGN KEY (user_sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE private_message ADD CONSTRAINT FK_4744FC9B64482423 FOREIGN KEY (user_receiver_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE payment_paypal ADD CONSTRAINT FK_41AE99DAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE child_comment ADD CONSTRAINT FK_F6C01A77F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id)');
        $this->addSql('ALTER TABLE child_comment ADD CONSTRAINT FK_F6C01A77A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE forum_reply ADD CONSTRAINT FK_E5DC603729CCBAD0 FOREIGN KEY (forum_id) REFERENCES forum (id)');
        $this->addSql('ALTER TABLE forum_reply ADD CONSTRAINT FK_E5DC603713841D26 FOREIGN KEY (user_post_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C37D925CB FOREIGN KEY (livre_id) REFERENCES livres (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE forum ADD CONSTRAINT FK_852BBECD618BA34B FOREIGN KEY (forums_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E3375BD21 FOREIGN KEY (editeur_id) REFERENCES editeur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E9A6BA409 FOREIGN KEY (interView_id) REFERENCES interView (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE profil_user ADD CONSTRAINT FK_85CBC6ABA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C37D925CB');
        $this->addSql('ALTER TABLE imagesAdmin DROP FOREIGN KEY FK_2EE463969A6BA409');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E9A6BA409');
        $this->addSql('ALTER TABLE livres DROP FOREIGN KEY FK_927187A4A76ED395');
        $this->addSql('ALTER TABLE private_message DROP FOREIGN KEY FK_4744FC9BF6C43E79');
        $this->addSql('ALTER TABLE private_message DROP FOREIGN KEY FK_4744FC9B64482423');
        $this->addSql('ALTER TABLE payment_paypal DROP FOREIGN KEY FK_41AE99DAA76ED395');
        $this->addSql('ALTER TABLE child_comment DROP FOREIGN KEY FK_F6C01A77A76ED395');
        $this->addSql('ALTER TABLE forum_reply DROP FOREIGN KEY FK_E5DC603713841D26');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE forum DROP FOREIGN KEY FK_852BBECD618BA34B');
        $this->addSql('ALTER TABLE profil_user DROP FOREIGN KEY FK_85CBC6ABA76ED395');
        $this->addSql('ALTER TABLE livres DROP FOREIGN KEY FK_927187A4BCF5E72D');
        $this->addSql('ALTER TABLE imagesAdmin DROP FOREIGN KEY FK_2EE463963375BD21');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E3375BD21');
        $this->addSql('ALTER TABLE child_comment DROP FOREIGN KEY FK_F6C01A77F8697D13');
        $this->addSql('ALTER TABLE forum_reply DROP FOREIGN KEY FK_E5DC603729CCBAD0');
        $this->addSql('DROP TABLE livres');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE imagesAdmin');
        $this->addSql('DROP TABLE articles');
        $this->addSql('DROP TABLE private_message');
        $this->addSql('DROP TABLE payment_paypal');
        $this->addSql('DROP TABLE child_comment');
        $this->addSql('DROP TABLE interView');
        $this->addSql('DROP TABLE forum_reply');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE editeur');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE forum');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE profil_user');
        $this->addSql('DROP TABLE appointment');
        $this->addSql('DROP TABLE quote');
    }
}
