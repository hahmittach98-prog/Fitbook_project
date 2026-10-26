<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261026153202 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avis (id INT AUTO_INCREMENT NOT NULL, note INT NOT NULL, commentaire LONGTEXT DEFAULT NULL, date_avis DATETIME NOT NULL, statut_moderation VARCHAR(50) NOT NULL, user_id INT NOT NULL, coach_id INT DEFAULT NULL, salle_id INT DEFAULT NULL, INDEX IDX_8F91ABF0A76ED395 (user_id), INDEX IDX_8F91ABF03C105691 (coach_id), INDEX IDX_8F91ABF0DC304035 (salle_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE coach (id INT AUTO_INCREMENT NOT NULL, specialite VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, tarif_horaire DOUBLE PRECISION DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, user_id INT NOT NULL, UNIQUE INDEX UNIQ_3F596DCCA76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE cours (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, description LONGTEXT NOT NULL, niveau VARCHAR(50) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE disponibilite (id INT AUTO_INCREMENT NOT NULL, jour_semaine VARCHAR(50) NOT NULL, heure_debut TIME NOT NULL, heure_fin TIME NOT NULL, coach_id INT DEFAULT NULL, salle_id INT DEFAULT NULL, INDEX IDX_2CBACE2F3C105691 (coach_id), INDEX IDX_2CBACE2FDC304035 (salle_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, date_reservation DATETIME NOT NULL, statut VARCHAR(50) NOT NULL, date_annulation DATETIME DEFAULT NULL, user_id INT NOT NULL, seance_id INT NOT NULL, INDEX IDX_42C84955A76ED395 (user_id), INDEX IDX_42C84955E3797A94 (seance_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE salle (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, capacite INT NOT NULL, telephone VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, gestionnaire_id INT NOT NULL, INDEX IDX_4E977E5C6885AC1B (gestionnaire_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE salle_cours (salle_id INT NOT NULL, cours_id INT NOT NULL, INDEX IDX_10CF8FF5DC304035 (salle_id), INDEX IDX_10CF8FF57ECF78B0 (cours_id), PRIMARY KEY (salle_id, cours_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE salle_coach (salle_id INT NOT NULL, coach_id INT NOT NULL, INDEX IDX_D25C6EA5DC304035 (salle_id), INDEX IDX_D25C6EA53C105691 (coach_id), PRIMARY KEY (salle_id, coach_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE seance (id INT AUTO_INCREMENT NOT NULL, date_seance DATE NOT NULL, heure_debut TIME NOT NULL, heure_fin TIME NOT NULL, capacite_max INT NOT NULL, prix DOUBLE PRECISION NOT NULL, statut VARCHAR(50) NOT NULL, coach_id INT NOT NULL, salle_id INT NOT NULL, INDEX IDX_DF7DFD0E3C105691 (coach_id), INDEX IDX_DF7DFD0EDC304035 (salle_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, telephone VARCHAR(50) DEFAULT NULL, is_verified TINYINT NOT NULL, date_inscription DATETIME NOT NULL, token_confirmation VARCHAR(100) DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user_coach (user_id INT NOT NULL, coach_id INT NOT NULL, INDEX IDX_DD9B9694A76ED395 (user_id), INDEX IDX_DD9B96943C105691 (coach_id), PRIMARY KEY (user_id, coach_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user_cours (user_id INT NOT NULL, cours_id INT NOT NULL, INDEX IDX_1F0877C4A76ED395 (user_id), INDEX IDX_1F0877C47ECF78B0 (cours_id), PRIMARY KEY (user_id, cours_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user_salle (user_id INT NOT NULL, salle_id INT NOT NULL, INDEX IDX_AC558504A76ED395 (user_id), INDEX IDX_AC558504DC304035 (salle_id), PRIMARY KEY (user_id, salle_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id))');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF03C105691 FOREIGN KEY (coach_id) REFERENCES coach (id)');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0DC304035 FOREIGN KEY (salle_id) REFERENCES salle (id)');
        $this->addSql('ALTER TABLE coach ADD CONSTRAINT FK_3F596DCCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE disponibilite ADD CONSTRAINT FK_2CBACE2F3C105691 FOREIGN KEY (coach_id) REFERENCES coach (id)');
        $this->addSql('ALTER TABLE disponibilite ADD CONSTRAINT FK_2CBACE2FDC304035 FOREIGN KEY (salle_id) REFERENCES salle (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955E3797A94 FOREIGN KEY (seance_id) REFERENCES seance (id)');
        $this->addSql('ALTER TABLE salle ADD CONSTRAINT FK_4E977E5C6885AC1B FOREIGN KEY (gestionnaire_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE salle_cours ADD CONSTRAINT FK_10CF8FF5DC304035 FOREIGN KEY (salle_id) REFERENCES salle (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE salle_cours ADD CONSTRAINT FK_10CF8FF57ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE salle_coach ADD CONSTRAINT FK_D25C6EA5DC304035 FOREIGN KEY (salle_id) REFERENCES salle (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE salle_coach ADD CONSTRAINT FK_D25C6EA53C105691 FOREIGN KEY (coach_id) REFERENCES coach (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE seance ADD CONSTRAINT FK_DF7DFD0E3C105691 FOREIGN KEY (coach_id) REFERENCES coach (id)');
        $this->addSql('ALTER TABLE seance ADD CONSTRAINT FK_DF7DFD0EDC304035 FOREIGN KEY (salle_id) REFERENCES salle (id)');
        $this->addSql('ALTER TABLE user_coach ADD CONSTRAINT FK_DD9B9694A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_coach ADD CONSTRAINT FK_DD9B96943C105691 FOREIGN KEY (coach_id) REFERENCES coach (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_cours ADD CONSTRAINT FK_1F0877C4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_cours ADD CONSTRAINT FK_1F0877C47ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_salle ADD CONSTRAINT FK_AC558504A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_salle ADD CONSTRAINT FK_AC558504DC304035 FOREIGN KEY (salle_id) REFERENCES salle (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0A76ED395');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF03C105691');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0DC304035');
        $this->addSql('ALTER TABLE coach DROP FOREIGN KEY FK_3F596DCCA76ED395');
        $this->addSql('ALTER TABLE disponibilite DROP FOREIGN KEY FK_2CBACE2F3C105691');
        $this->addSql('ALTER TABLE disponibilite DROP FOREIGN KEY FK_2CBACE2FDC304035');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A76ED395');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955E3797A94');
        $this->addSql('ALTER TABLE salle DROP FOREIGN KEY FK_4E977E5C6885AC1B');
        $this->addSql('ALTER TABLE salle_cours DROP FOREIGN KEY FK_10CF8FF5DC304035');
        $this->addSql('ALTER TABLE salle_cours DROP FOREIGN KEY FK_10CF8FF57ECF78B0');
        $this->addSql('ALTER TABLE salle_coach DROP FOREIGN KEY FK_D25C6EA5DC304035');
        $this->addSql('ALTER TABLE salle_coach DROP FOREIGN KEY FK_D25C6EA53C105691');
        $this->addSql('ALTER TABLE seance DROP FOREIGN KEY FK_DF7DFD0E3C105691');
        $this->addSql('ALTER TABLE seance DROP FOREIGN KEY FK_DF7DFD0EDC304035');
        $this->addSql('ALTER TABLE user_coach DROP FOREIGN KEY FK_DD9B9694A76ED395');
        $this->addSql('ALTER TABLE user_coach DROP FOREIGN KEY FK_DD9B96943C105691');
        $this->addSql('ALTER TABLE user_cours DROP FOREIGN KEY FK_1F0877C4A76ED395');
        $this->addSql('ALTER TABLE user_cours DROP FOREIGN KEY FK_1F0877C47ECF78B0');
        $this->addSql('ALTER TABLE user_salle DROP FOREIGN KEY FK_AC558504A76ED395');
        $this->addSql('ALTER TABLE user_salle DROP FOREIGN KEY FK_AC558504DC304035');
        $this->addSql('DROP TABLE avis');
        $this->addSql('DROP TABLE coach');
        $this->addSql('DROP TABLE cours');
        $this->addSql('DROP TABLE disponibilite');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE salle');
        $this->addSql('DROP TABLE salle_cours');
        $this->addSql('DROP TABLE salle_coach');
        $this->addSql('DROP TABLE seance');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_coach');
        $this->addSql('DROP TABLE user_cours');
        $this->addSql('DROP TABLE user_salle');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
