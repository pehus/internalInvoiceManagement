<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250314174240 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE invoice (id INT AUTO_INCREMENT NOT NULL, status_id INT NOT NULL, client_name VARCHAR(255) NOT NULL, total_amount NUMERIC(10, 2) NOT NULL, INDEX IDX_906517446BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invoice_item (id INT AUTO_INCREMENT NOT NULL, invoice_id INT NOT NULL, name VARCHAR(255) NOT NULL, quantity INT NOT NULL, unit_price NUMERIC(10, 2) NOT NULL, INDEX IDX_1DDE477B2989F1FD (invoice_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invoice_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_C036F84F5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, invoice_id INT NOT NULL, payment_method_id INT NOT NULL, date DATETIME NOT NULL, amount NUMERIC(10, 2) NOT NULL, INDEX IDX_6D28840D2989F1FD (invoice_id), INDEX IDX_6D28840D5AA1164F (payment_method_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_method (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_7B61A1F65E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_906517446BF700BD FOREIGN KEY (status_id) REFERENCES invoice_status (id)');
        $this->addSql('ALTER TABLE invoice_item ADD CONSTRAINT FK_1DDE477B2989F1FD FOREIGN KEY (invoice_id) REFERENCES invoice (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D2989F1FD FOREIGN KEY (invoice_id) REFERENCES invoice (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D5AA1164F FOREIGN KEY (payment_method_id) REFERENCES payment_method (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_906517446BF700BD');
        $this->addSql('ALTER TABLE invoice_item DROP FOREIGN KEY FK_1DDE477B2989F1FD');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D2989F1FD');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D5AA1164F');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('DROP TABLE invoice_item');
        $this->addSql('DROP TABLE invoice_status');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE payment_method');
    }
}
