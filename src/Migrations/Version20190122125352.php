<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190122125352 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, api_token VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE label (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE label_check_list (label_id INT NOT NULL, check_list_id INT NOT NULL, INDEX IDX_73AE455C33B92F39 (label_id), INDEX IDX_73AE455C7BB2580B (check_list_id), PRIMARY KEY(label_id, check_list_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, check_list_id INT DEFAULT NULL, attachment_id INT DEFAULT NULL, checked TINYINT(1) NOT NULL, INDEX IDX_1F1B251E7BB2580B (check_list_id), UNIQUE INDEX UNIQ_1F1B251E464E68B (attachment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE attachment (id INT AUTO_INCREMENT NOT NULL, text VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE check_list (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, expire VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_A1488C995E237E06 (name), INDEX IDX_A1488C99A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE label_check_list ADD CONSTRAINT FK_73AE455C33B92F39 FOREIGN KEY (label_id) REFERENCES label (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE label_check_list ADD CONSTRAINT FK_73AE455C7BB2580B FOREIGN KEY (check_list_id) REFERENCES check_list (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E7BB2580B FOREIGN KEY (check_list_id) REFERENCES check_list (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E464E68B FOREIGN KEY (attachment_id) REFERENCES attachment (id)');
        $this->addSql('ALTER TABLE check_list ADD CONSTRAINT FK_A1488C99A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE check_list DROP FOREIGN KEY FK_A1488C99A76ED395');
        $this->addSql('ALTER TABLE label_check_list DROP FOREIGN KEY FK_73AE455C33B92F39');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E464E68B');
        $this->addSql('ALTER TABLE label_check_list DROP FOREIGN KEY FK_73AE455C7BB2580B');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E7BB2580B');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE label');
        $this->addSql('DROP TABLE label_check_list');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE attachment');
        $this->addSql('DROP TABLE check_list');
    }
}
