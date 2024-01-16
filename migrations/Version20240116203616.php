<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240116203616 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE coupon (id INT AUTO_INCREMENT NOT NULL, sorteo_id INT NOT NULL, owner_id INT NOT NULL, number VARCHAR(7) NOT NULL, state SMALLINT NOT NULL, INDEX IDX_64BF3F02663FD436 (sorteo_id), INDEX IDX_64BF3F027E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE coupon ADD CONSTRAINT FK_64BF3F02663FD436 FOREIGN KEY (sorteo_id) REFERENCES sorteo (id)');
        $this->addSql('ALTER TABLE coupon ADD CONSTRAINT FK_64BF3F027E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE coupon DROP FOREIGN KEY FK_64BF3F02663FD436');
        $this->addSql('ALTER TABLE coupon DROP FOREIGN KEY FK_64BF3F027E3C61F9');
        $this->addSql('DROP TABLE coupon');
    }
}
