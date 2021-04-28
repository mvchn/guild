<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210427195911 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT fk_e52ffdee7bea3ba2');
        $this->addSql('DROP INDEX uniq_e52ffdeedcd6110');
        $this->addSql('DROP INDEX idx_e52ffdee7bea3ba2');
        $this->addSql('ALTER TABLE orders DROP stck_id');
        $this->addSql('CREATE INDEX IDX_E52FFDEEDCD6110 ON orders (stock_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_E52FFDEEDCD6110');
        $this->addSql('ALTER TABLE orders ADD stck_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT fk_e52ffdee7bea3ba2 FOREIGN KEY (stck_id) REFERENCES stock (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_e52ffdeedcd6110 ON orders (stock_id)');
        $this->addSql('CREATE INDEX idx_e52ffdee7bea3ba2 ON orders (stck_id)');
    }
}
