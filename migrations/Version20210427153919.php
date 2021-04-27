<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210427153919 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_319B9E705E237E064584665A ON attributes (name, product_id)');
        $this->addSql('ALTER INDEX uniq_4b365660e636d3f5 RENAME TO UNIQ_4B3656608D9F6D38');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER INDEX uniq_4b3656608d9f6d38 RENAME TO uniq_4b365660e636d3f5');
        $this->addSql('DROP INDEX UNIQ_319B9E705E237E064584665A');
    }
}
