<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210427155059 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_attribute DROP CONSTRAINT FK_46FD9403B6E62EFA');
        $this->addSql('ALTER TABLE order_attribute ADD CONSTRAINT FK_46FD9403B6E62EFA FOREIGN KEY (attribute_id) REFERENCES attributes (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_attribute DROP CONSTRAINT fk_46fd9403b6e62efa');
        $this->addSql('ALTER TABLE order_attribute ADD CONSTRAINT fk_46fd9403b6e62efa FOREIGN KEY (attribute_id) REFERENCES attributes (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
