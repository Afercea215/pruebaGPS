<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230214073612 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE banda (id INT AUTO_INCREMENT NOT NULL, distancia INT NOT NULL, rango_min INT NOT NULL, rango_max INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mensaje (id INT AUTO_INCREMENT NOT NULL, banda_id INT NOT NULL, modo_id INT NOT NULL, emisor_id INT NOT NULL, receptor_id INT NOT NULL, fecha DATETIME NOT NULL, valido TINYINT(1) NOT NULL, INDEX IDX_9B631D019EFB0C1D (banda_id), INDEX IDX_9B631D011858652E (modo_id), INDEX IDX_9B631D016BDF87DF (emisor_id), INDEX IDX_9B631D01386D8D01 (receptor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE modo (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mensaje ADD CONSTRAINT FK_9B631D019EFB0C1D FOREIGN KEY (banda_id) REFERENCES banda (id)');
        $this->addSql('ALTER TABLE mensaje ADD CONSTRAINT FK_9B631D011858652E FOREIGN KEY (modo_id) REFERENCES modo (id)');
        $this->addSql('ALTER TABLE mensaje ADD CONSTRAINT FK_9B631D016BDF87DF FOREIGN KEY (emisor_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE mensaje ADD CONSTRAINT FK_9B631D01386D8D01 FOREIGN KEY (receptor_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mensaje DROP FOREIGN KEY FK_9B631D019EFB0C1D');
        $this->addSql('ALTER TABLE mensaje DROP FOREIGN KEY FK_9B631D011858652E');
        $this->addSql('ALTER TABLE mensaje DROP FOREIGN KEY FK_9B631D016BDF87DF');
        $this->addSql('ALTER TABLE mensaje DROP FOREIGN KEY FK_9B631D01386D8D01');
        $this->addSql('DROP TABLE banda');
        $this->addSql('DROP TABLE mensaje');
        $this->addSql('DROP TABLE modo');
        $this->addSql('DROP TABLE `user`');
    }
}
