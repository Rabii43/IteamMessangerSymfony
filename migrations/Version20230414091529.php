<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230414091529 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, message LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message_users (message_id INT NOT NULL, users_id INT NOT NULL, INDEX IDX_EB255A02537A1329 (message_id), INDEX IDX_EB255A0267B3B43D (users_id), PRIMARY KEY(message_id, users_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `users` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, user_name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_message (users_id INT NOT NULL, message_id INT NOT NULL, INDEX IDX_46BD44B567B3B43D (users_id), INDEX IDX_46BD44B5537A1329 (message_id), PRIMARY KEY(users_id, message_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE message_users ADD CONSTRAINT FK_EB255A02537A1329 FOREIGN KEY (message_id) REFERENCES message (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message_users ADD CONSTRAINT FK_EB255A0267B3B43D FOREIGN KEY (users_id) REFERENCES `users` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_message ADD CONSTRAINT FK_46BD44B567B3B43D FOREIGN KEY (users_id) REFERENCES `users` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_message ADD CONSTRAINT FK_46BD44B5537A1329 FOREIGN KEY (message_id) REFERENCES message (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message_users DROP FOREIGN KEY FK_EB255A02537A1329');
        $this->addSql('ALTER TABLE message_users DROP FOREIGN KEY FK_EB255A0267B3B43D');
        $this->addSql('ALTER TABLE users_message DROP FOREIGN KEY FK_46BD44B567B3B43D');
        $this->addSql('ALTER TABLE users_message DROP FOREIGN KEY FK_46BD44B5537A1329');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE message_users');
        $this->addSql('DROP TABLE `users`');
        $this->addSql('DROP TABLE users_message');
    }
}
