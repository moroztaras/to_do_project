<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190125163525 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, api_token VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D6497BA2F5EB (api_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE label (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_list (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_8CF8BCE3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_list_label (item_list_id INT NOT NULL, label_id INT NOT NULL, INDEX IDX_6A85ECE136F330DF (item_list_id), INDEX IDX_6A85ECE133B92F39 (label_id), PRIMARY KEY(item_list_id, label_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, list_id INT NOT NULL, attachment_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, is_checked TINYINT(1) NOT NULL, expiration DATETIME DEFAULT NULL, INDEX IDX_1F1B251E3DAE168B (list_id), UNIQUE INDEX UNIQ_1F1B251E464E68B (attachment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE attachment (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item_list ADD CONSTRAINT FK_8CF8BCE3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE item_list_label ADD CONSTRAINT FK_6A85ECE136F330DF FOREIGN KEY (item_list_id) REFERENCES item_list (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_list_label ADD CONSTRAINT FK_6A85ECE133B92F39 FOREIGN KEY (label_id) REFERENCES label (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E3DAE168B FOREIGN KEY (list_id) REFERENCES item_list (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E464E68B FOREIGN KEY (attachment_id) REFERENCES attachment (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE item_list DROP FOREIGN KEY FK_8CF8BCE3A76ED395');
        $this->addSql('ALTER TABLE item_list_label DROP FOREIGN KEY FK_6A85ECE133B92F39');
        $this->addSql('ALTER TABLE item_list_label DROP FOREIGN KEY FK_6A85ECE136F330DF');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E3DAE168B');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E464E68B');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE label');
        $this->addSql('DROP TABLE item_list');
        $this->addSql('DROP TABLE item_list_label');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE attachment');
    }
}
