<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250314214133 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice ADD payment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_906517444C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_906517444C3A3BB ON invoice (payment_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_906517444C3A3BB');
        $this->addSql('DROP INDEX UNIQ_906517444C3A3BB ON invoice');
        $this->addSql('ALTER TABLE invoice DROP payment_id');
    }
}
