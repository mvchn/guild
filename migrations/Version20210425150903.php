<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210425150903 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_319B9E704584665A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__attributes AS SELECT id, product_id, name, type, label, help, required, verify FROM attributes');
        $this->addSql('DROP TABLE attributes');
        $this->addSql('CREATE TABLE attributes (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, product_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, type VARCHAR(64) NOT NULL COLLATE BINARY, label VARCHAR(255) NOT NULL COLLATE BINARY, help VARCHAR(255) DEFAULT NULL COLLATE BINARY, required BOOLEAN NOT NULL, verify BOOLEAN defaul NULL, CONSTRAINT FK_319B9E704584665A FOREIGN KEY (product_id) REFERENCES products (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO attributes (id, product_id, name, type, label, help, required, verify) SELECT id, product_id, name, type, label, help, required, verify FROM __temp__attributes');
        $this->addSql('DROP TABLE __temp__attributes');
        $this->addSql('CREATE INDEX IDX_319B9E704584665A ON attributes (product_id)');
        $this->addSql('DROP INDEX IDX_46FD9403B6E62EFA');
        $this->addSql('DROP INDEX IDX_46FD940392FCEA00');
        $this->addSql('CREATE TEMPORARY TABLE __temp__order_attribute AS SELECT id, ordr_id, attribute_id, value, is_verified FROM order_attribute');
        $this->addSql('DROP TABLE order_attribute');
        $this->addSql('CREATE TABLE order_attribute (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, ordr_id INTEGER DEFAULT NULL, attribute_id INTEGER DEFAULT NULL, value VARCHAR(255) NOT NULL COLLATE BINARY, is_verified BOOLEAN DEFAULT NULL, CONSTRAINT FK_46FD940392FCEA00 FOREIGN KEY (ordr_id) REFERENCES orders (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_46FD9403B6E62EFA FOREIGN KEY (attribute_id) REFERENCES attributes (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO order_attribute (id, ordr_id, attribute_id, value, is_verified) SELECT id, ordr_id, attribute_id, value, is_verified FROM __temp__order_attribute');
        $this->addSql('DROP TABLE __temp__order_attribute');
        $this->addSql('CREATE INDEX IDX_46FD9403B6E62EFA ON order_attribute (attribute_id)');
        $this->addSql('CREATE INDEX IDX_46FD940392FCEA00 ON order_attribute (ordr_id)');
        $this->addSql('DROP INDEX IDX_2530ADE64584665A');
        $this->addSql('DROP INDEX IDX_2530ADE68D9F6D38');
        $this->addSql('CREATE TEMPORARY TABLE __temp__order_product AS SELECT order_id, product_id FROM order_product');
        $this->addSql('DROP TABLE order_product');
        $this->addSql('CREATE TABLE order_product (order_id INTEGER NOT NULL, product_id INTEGER NOT NULL, PRIMARY KEY(order_id, product_id), CONSTRAINT FK_2530ADE68D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_2530ADE64584665A FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO order_product (order_id, product_id) SELECT order_id, product_id FROM __temp__order_product');
        $this->addSql('DROP TABLE __temp__order_product');
        $this->addSql('CREATE INDEX IDX_2530ADE64584665A ON order_product (product_id)');
        $this->addSql('CREATE INDEX IDX_2530ADE68D9F6D38 ON order_product (order_id)');
        $this->addSql('DROP INDEX IDX_B3BA5A5A61220EA6');
        $this->addSql('CREATE TEMPORARY TABLE __temp__products AS SELECT id, creator_id, created_at, title, destination_url, description FROM products');
        $this->addSql('DROP TABLE products');
        $this->addSql('CREATE TABLE products (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, creator_id INTEGER NOT NULL, created_at DATETIME NOT NULL, title VARCHAR(255) NOT NULL COLLATE BINARY, destination_url VARCHAR(255) DEFAULT NULL COLLATE BINARY, description VARCHAR(255) DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_B3BA5A5A61220EA6 FOREIGN KEY (creator_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO products (id, creator_id, created_at, title, destination_url, description) SELECT id, creator_id, created_at, title, destination_url, description FROM __temp__products');
        $this->addSql('DROP TABLE __temp__products');
        $this->addSql('CREATE INDEX IDX_B3BA5A5A61220EA6 ON products (creator_id)');
        $this->addSql('DROP INDEX IDX_4B3656604584665A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__stock AS SELECT id, product_id, created_at, type, amount FROM stock');
        $this->addSql('DROP TABLE stock');
        $this->addSql('CREATE TABLE stock (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, product_id INTEGER NOT NULL, created_at DATETIME NOT NULL, type VARCHAR(32) NOT NULL COLLATE BINARY, amount INTEGER NOT NULL, start_at DATETIME NOT NULL, finish_at DATETIME DEFAULT NULL, CONSTRAINT FK_4B3656604584665A FOREIGN KEY (product_id) REFERENCES products (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO stock (id, product_id, created_at, type, amount) SELECT id, product_id, created_at, type, amount FROM __temp__stock');
        $this->addSql('DROP TABLE __temp__stock');
        $this->addSql('CREATE INDEX IDX_4B3656604584665A ON stock (product_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_319B9E704584665A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__attributes AS SELECT id, product_id, name, type, label, help, required, verify FROM attributes');
        $this->addSql('DROP TABLE attributes');
        $this->addSql('CREATE TABLE attributes (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, product_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(64) NOT NULL, label VARCHAR(255) NOT NULL, help VARCHAR(255) DEFAULT NULL, required BOOLEAN NOT NULL, verify BOOLEAN DEFAULT NULL)');
        $this->addSql('INSERT INTO attributes (id, product_id, name, type, label, help, required, verify) SELECT id, product_id, name, type, label, help, required, verify FROM __temp__attributes');
        $this->addSql('DROP TABLE __temp__attributes');
        $this->addSql('CREATE INDEX IDX_319B9E704584665A ON attributes (product_id)');
        $this->addSql('DROP INDEX IDX_46FD940392FCEA00');
        $this->addSql('DROP INDEX IDX_46FD9403B6E62EFA');
        $this->addSql('CREATE TEMPORARY TABLE __temp__order_attribute AS SELECT id, ordr_id, attribute_id, value, is_verified FROM order_attribute');
        $this->addSql('DROP TABLE order_attribute');
        $this->addSql('CREATE TABLE order_attribute (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, ordr_id INTEGER DEFAULT NULL, attribute_id INTEGER DEFAULT NULL, value VARCHAR(255) NOT NULL, is_verified BOOLEAN DEFAULT NULL)');
        $this->addSql('INSERT INTO order_attribute (id, ordr_id, attribute_id, value, is_verified) SELECT id, ordr_id, attribute_id, value, is_verified FROM __temp__order_attribute');
        $this->addSql('DROP TABLE __temp__order_attribute');
        $this->addSql('CREATE INDEX IDX_46FD940392FCEA00 ON order_attribute (ordr_id)');
        $this->addSql('CREATE INDEX IDX_46FD9403B6E62EFA ON order_attribute (attribute_id)');
        $this->addSql('DROP INDEX IDX_2530ADE68D9F6D38');
        $this->addSql('DROP INDEX IDX_2530ADE64584665A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__order_product AS SELECT order_id, product_id FROM order_product');
        $this->addSql('DROP TABLE order_product');
        $this->addSql('CREATE TABLE order_product (order_id INTEGER NOT NULL, product_id INTEGER NOT NULL, PRIMARY KEY(order_id, product_id))');
        $this->addSql('INSERT INTO order_product (order_id, product_id) SELECT order_id, product_id FROM __temp__order_product');
        $this->addSql('DROP TABLE __temp__order_product');
        $this->addSql('CREATE INDEX IDX_2530ADE68D9F6D38 ON order_product (order_id)');
        $this->addSql('CREATE INDEX IDX_2530ADE64584665A ON order_product (product_id)');
        $this->addSql('DROP INDEX IDX_B3BA5A5A61220EA6');
        $this->addSql('CREATE TEMPORARY TABLE __temp__products AS SELECT id, creator_id, created_at, title, destination_url, description FROM products');
        $this->addSql('DROP TABLE products');
        $this->addSql('CREATE TABLE products (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, creator_id INTEGER NOT NULL, created_at DATETIME NOT NULL, title VARCHAR(255) NOT NULL, destination_url VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO products (id, creator_id, created_at, title, destination_url, description) SELECT id, creator_id, created_at, title, destination_url, description FROM __temp__products');
        $this->addSql('DROP TABLE __temp__products');
        $this->addSql('CREATE INDEX IDX_B3BA5A5A61220EA6 ON products (creator_id)');
        $this->addSql('DROP INDEX IDX_4B3656604584665A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__stock AS SELECT id, product_id, created_at, type, amount FROM stock');
        $this->addSql('DROP TABLE stock');
        $this->addSql('CREATE TABLE stock (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, product_id INTEGER NOT NULL, created_at DATETIME NOT NULL, type VARCHAR(32) NOT NULL, amount INTEGER NOT NULL)');
        $this->addSql('INSERT INTO stock (id, product_id, created_at, type, amount) SELECT id, product_id, created_at, type, amount FROM __temp__stock');
        $this->addSql('DROP TABLE __temp__stock');
        $this->addSql('CREATE INDEX IDX_4B3656604584665A ON stock (product_id)');
    }
}
