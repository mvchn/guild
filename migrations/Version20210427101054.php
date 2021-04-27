<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210427101054 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_attribute DROP CONSTRAINT fk_46fd940392fcea00');
        $this->addSql('DROP INDEX idx_46fd940392fcea00');
        $this->addSql('ALTER TABLE order_attribute RENAME COLUMN ordr_id TO order_id');
        $this->addSql('ALTER TABLE order_attribute ADD CONSTRAINT FK_46FD94038D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_46FD94038D9F6D38 ON order_attribute (order_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE order_attribute DROP CONSTRAINT FK_46FD94038D9F6D38');
        $this->addSql('DROP INDEX IDX_46FD94038D9F6D38');
        $this->addSql('ALTER TABLE order_attribute RENAME COLUMN order_id TO ordr_id');
        $this->addSql('ALTER TABLE order_attribute ADD CONSTRAINT fk_46fd940392fcea00 FOREIGN KEY (ordr_id) REFERENCES orders (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_46fd940392fcea00 ON order_attribute (ordr_id)');
    }
}
