<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190212144805 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE item_list (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, title VARCHAR(255) NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_8CF8BCE3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, item_list_id INT NOT NULL, title VARCHAR(255) NOT NULL, expiration_date DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, is_checked TINYINT(1) NOT NULL, INDEX IDX_1F1B251E36F330DF (item_list_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, api_token VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE label (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE label_item_list (label_id INT NOT NULL, item_list_id INT NOT NULL, INDEX IDX_67B484C533B92F39 (label_id), INDEX IDX_67B484C536F330DF (item_list_id), PRIMARY KEY(label_id, item_list_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item_list ADD CONSTRAINT FK_8CF8BCE3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E36F330DF FOREIGN KEY (item_list_id) REFERENCES item_list (id)');
        $this->addSql('ALTER TABLE label_item_list ADD CONSTRAINT FK_67B484C533B92F39 FOREIGN KEY (label_id) REFERENCES label (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE label_item_list ADD CONSTRAINT FK_67B484C536F330DF FOREIGN KEY (item_list_id) REFERENCES item_list (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E36F330DF');
        $this->addSql('ALTER TABLE label_item_list DROP FOREIGN KEY FK_67B484C536F330DF');
        $this->addSql('ALTER TABLE item_list DROP FOREIGN KEY FK_8CF8BCE3A76ED395');
        $this->addSql('ALTER TABLE label_item_list DROP FOREIGN KEY FK_67B484C533B92F39');
        $this->addSql('DROP TABLE item_list');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE label');
        $this->addSql('DROP TABLE label_item_list');
    }
}
