<?php

namespace ProcessManagerBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Pimcore\Migrations\Migration\AbstractPimcoreMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180222140525 extends AbstractPimcoreMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $execTable = $schema->getTable("process_manager_executables");
        $execTable->addColumn("lastrun", "bigint", ['default' => 0, 'notnull' => false]);

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $execTable = $schema->getTable("process_manager_executables");
        $execTable->dropColumn("lastrun");
    }
}
