<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170800144517_size extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $table = $schema->createTable('sizes');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);        
        $table->addColumn('size', 'text');
        $table->addColumn('date_created', 'datetime');
        $table->setPrimaryKey(['id']);
        $table->addOption('engine', 'InnoDB');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $schema->dropTable('sizes');
    }
}
