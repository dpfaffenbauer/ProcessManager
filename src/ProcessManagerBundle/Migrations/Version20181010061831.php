<?php

namespace ProcessManagerBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Pimcore\Migrations\Migration\AbstractPimcoreMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181010061831 extends AbstractPimcoreMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $processesTable = $schema->getTable("process_manager_processes");
        $processesTable->addColumn("started", "bigint", ['default' => 0, 'notnull' => false]);
        $processesTable->addColumn("completed", "bigint", ['default' => 0, 'notnull' => false]);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $processesTable = $schema->getTable("process_manager_processes");
        $processesTable->dropColumn("started");
        $processesTable->dropColumn("completed");
    }
}
