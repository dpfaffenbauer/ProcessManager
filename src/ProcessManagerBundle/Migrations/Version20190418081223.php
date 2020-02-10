<?php

namespace ProcessManagerBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Pimcore\Migrations\Migration\AbstractPimcoreMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20190418081223 extends AbstractPimcoreMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $processesTable = $schema->getTable("process_manager_processes");
        $processesTable->addColumn("stoppable", "boolean", ['default' => false, 'notnull' => false]);
        $processesTable->addColumn("status", "string", ['notnull' => false]);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $processesTable = $schema->getTable("process_manager_processes");
        $processesTable->dropColumn("stoppable");
        $processesTable->dropColumn("status");
    }
}
