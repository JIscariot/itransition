<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171015115828 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql(
            'CREATE TABLE products (
                  id int(10) unsigned NOT NULL AUTO_INCREMENT,
                  name varchar(50) NOT NULL,
                  description varchar(255) NOT NULL,
                  code varchar(10) NOT NULL,
                  price decimal(10, 2) NOT NULL,
                  quantity int(10) NOT NULL,                  
                  created_at datetime DEFAULT NULL,
                  updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                  discontinued_at datetime DEFAULT NULL,
                  PRIMARY KEY (id),
                  UNIQUE KEY (code)
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT=\'Stores product data\';'
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE products');
    }
}
