<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210427201537 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stock DROP CONSTRAINT fk_4b365660e636d3f5');
        $this->addSql('DROP INDEX uniq_4b3656608d9f6d38');
        $this->addSql('ALTER TABLE stock DROP order_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE stock ADD order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT fk_4b365660e636d3f5 FOREIGN KEY (order_id) REFERENCES orders (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_4b3656608d9f6d38 ON stock (order_id)');
    }
}
