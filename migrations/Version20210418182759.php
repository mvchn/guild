<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210418182759 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE verification (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__orders AS SELECT id, created_at, updated_at, name, email FROM orders');
        $this->addSql('DROP TABLE orders');
        $this->addSql('CREATE TABLE orders (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, name VARCHAR(128) NOT NULL, email VARCHAR(64) NOT NULL, status VARCHAR(32))');
        $this->addSql('INSERT INTO orders (id, created_at, updated_at, name, email) SELECT id, created_at, updated_at, name, email FROM __temp__orders');
        $this->addSql('DROP TABLE __temp__orders');
        $this->addSql('DROP INDEX IDX_2530ADE68D9F6D38');
        $this->addSql('DROP INDEX IDX_2530ADE64584665A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__order_product AS SELECT order_id, product_id FROM order_product');
        $this->addSql('DROP TABLE order_product');
        $this->addSql('CREATE TABLE order_product (order_id INTEGER NOT NULL, product_id INTEGER NOT NULL, PRIMARY KEY(order_id, product_id), CONSTRAINT FK_2530ADE68D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_2530ADE64584665A FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO order_product (order_id, product_id) SELECT order_id, product_id FROM __temp__order_product');
        $this->addSql('DROP TABLE __temp__order_product');
        $this->addSql('CREATE INDEX IDX_2530ADE68D9F6D38 ON order_product (order_id)');
        $this->addSql('CREATE INDEX IDX_2530ADE64584665A ON order_product (product_id)');
        $this->addSql('DROP INDEX IDX_B3BA5A5A61220EA6');
        $this->addSql('CREATE TEMPORARY TABLE __temp__products AS SELECT id, creator_id, title, created_at FROM products');
        $this->addSql('DROP TABLE products');
        $this->addSql('CREATE TABLE products (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, creator_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL COLLATE BINARY, created_at DATETIME NOT NULL, CONSTRAINT FK_B3BA5A5A61220EA6 FOREIGN KEY (creator_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO products (id, creator_id, title, created_at) SELECT id, creator_id, title, created_at FROM __temp__products');
        $this->addSql('DROP TABLE __temp__products');
        $this->addSql('CREATE INDEX IDX_B3BA5A5A61220EA6 ON products (creator_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE verification');
        $this->addSql('DROP INDEX IDX_2530ADE68D9F6D38');
        $this->addSql('DROP INDEX IDX_2530ADE64584665A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__order_product AS SELECT order_id, product_id FROM order_product');
        $this->addSql('DROP TABLE order_product');
        $this->addSql('CREATE TABLE order_product (order_id INTEGER NOT NULL, product_id INTEGER NOT NULL, PRIMARY KEY(order_id, product_id))');
        $this->addSql('INSERT INTO order_product (order_id, product_id) SELECT order_id, product_id FROM __temp__order_product');
        $this->addSql('DROP TABLE __temp__order_product');
        $this->addSql('CREATE INDEX IDX_2530ADE68D9F6D38 ON order_product (order_id)');
        $this->addSql('CREATE INDEX IDX_2530ADE64584665A ON order_product (product_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__orders AS SELECT id, created_at, updated_at, name, email FROM orders');
        $this->addSql('DROP TABLE orders');
        $this->addSql('CREATE TABLE orders (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, name VARCHAR(128) DEFAULT NULL COLLATE BINARY, email VARCHAR(64) DEFAULT NULL COLLATE BINARY)');
        $this->addSql('INSERT INTO orders (id, created_at, updated_at, name, email) SELECT id, created_at, updated_at, name, email FROM __temp__orders');
        $this->addSql('DROP TABLE __temp__orders');
        $this->addSql('DROP INDEX IDX_B3BA5A5A61220EA6');
        $this->addSql('CREATE TEMPORARY TABLE __temp__products AS SELECT id, creator_id, created_at, title FROM products');
        $this->addSql('DROP TABLE products');
        $this->addSql('CREATE TABLE products (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, creator_id INTEGER NOT NULL, created_at DATETIME NOT NULL, title VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO products (id, creator_id, created_at, title) SELECT id, creator_id, created_at, title FROM __temp__products');
        $this->addSql('DROP TABLE __temp__products');
        $this->addSql('CREATE INDEX IDX_B3BA5A5A61220EA6 ON products (creator_id)');
    }
}
