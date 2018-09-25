<?php

namespace ProcessManagerBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Pimcore\Migrations\Migration\AbstractPimcoreMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180921171142 extends AbstractPimcoreMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $processesTable = $schema->getTable("process_manager_processes");
        $processesTable->addColumn("type", "string");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $processesTable = $schema->getTable("process_manager_processes");
        $processesTable->dropColumn("type");
    }
}
