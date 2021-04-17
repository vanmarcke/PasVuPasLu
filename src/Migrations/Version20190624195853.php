<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190624195853 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE editeur ADD color_name VARCHAR(255) DEFAULT NULL, ADD color_desc VARCHAR(255) DEFAULT NULL, CHANGE url url VARCHAR(255) DEFAULT NULL, CHANGE create_at create_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE comment CHANGE rapport rapport TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE interView CHANGE url url VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE reset_token reset_token VARCHAR(255) DEFAULT NULL, CHANGE is_livre is_livre VARCHAR(255) DEFAULT NULL, CHANGE subscription_end subscription_end DATE DEFAULT NULL, CHANGE payer_id payer_id VARCHAR(255) DEFAULT NULL, CHANGE paypal_profil_id paypal_profil_id VARCHAR(255) DEFAULT NULL, CHANGE payer_status payer_status VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_reply CHANGE forum_id forum_id INT DEFAULT NULL, CHANGE user_post_id user_post_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE forum CHANGE forums_id forums_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE profil_user CHANGE photo photo VARCHAR(255) DEFAULT NULL, CHANGE adress adress VARCHAR(255) DEFAULT NULL, CHANGE code_postale code_postale INT DEFAULT NULL, CHANGE ville ville VARCHAR(255) DEFAULT NULL, CHANGE country country VARCHAR(255) DEFAULT NULL, CHANGE telephone telephone INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image CHANGE alt alt VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE imagesAdmin CHANGE editeur_id editeur_id INT DEFAULT NULL, CHANGE alt alt VARCHAR(255) DEFAULT NULL, CHANGE deleted_at deleted_at DATETIME DEFAULT NULL, CHANGE description description VARCHAR(255) DEFAULT NULL, CHANGE interView_id interView_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE private_message CHANGE user_sender_id user_sender_id INT DEFAULT NULL, CHANGE is_head is_head TINYINT(1) DEFAULT NULL, CHANGE is_view is_view TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE payment_paypal CHANGE user_id user_id INT DEFAULT NULL, CHANGE transaction_id transaction_id VARCHAR(255) DEFAULT NULL, CHANGE payment_amount payment_amount DOUBLE PRECISION DEFAULT NULL, CHANGE invoice_id invoice_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE question CHANGE editeur_id editeur_id INT DEFAULT NULL, CHANGE title title VARCHAR(191) DEFAULT NULL, CHANGE interView_id interView_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment CHANGE rapport rapport TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE editeur DROP color_name, DROP color_desc, CHANGE url url VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE create_at create_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE forum CHANGE forums_id forums_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_reply CHANGE forum_id forum_id INT DEFAULT NULL, CHANGE user_post_id user_post_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image CHANGE alt alt VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE imagesAdmin CHANGE editeur_id editeur_id INT DEFAULT NULL, CHANGE alt alt VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE deleted_at deleted_at DATETIME DEFAULT \'NULL\', CHANGE description description VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE interView_id interView_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE interView CHANGE url url VARCHAR(100) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE payment_paypal CHANGE user_id user_id INT DEFAULT NULL, CHANGE transaction_id transaction_id VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE payment_amount payment_amount DOUBLE PRECISION DEFAULT \'NULL\', CHANGE invoice_id invoice_id VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE private_message CHANGE user_sender_id user_sender_id INT DEFAULT NULL, CHANGE is_head is_head TINYINT(1) DEFAULT \'NULL\', CHANGE is_view is_view TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE profil_user CHANGE photo photo VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE adress adress VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE code_postale code_postale INT DEFAULT NULL, CHANGE ville ville VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE country country VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE telephone telephone INT DEFAULT NULL');
        $this->addSql('ALTER TABLE question CHANGE editeur_id editeur_id INT DEFAULT NULL, CHANGE title title VARCHAR(191) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE interView_id interView_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE reset_token reset_token VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE is_livre is_livre VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE subscription_end subscription_end DATE DEFAULT \'NULL\', CHANGE payer_id payer_id VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE paypal_profil_id paypal_profil_id VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE payer_status payer_status VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
