<?php

namespace ProcessManagerBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Pimcore\Migrations\Migration\AbstractPimcoreMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200907142612 extends AbstractPimcoreMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $queueItemTable = $schema->createTable('process_manager_queueitems');
        $queueItemTable->addColumn('id', 'integer')
            ->setAutoincrement(true);
        $queueItemTable->addColumn('name', 'string');
        $queueItemTable->addColumn('description', 'text');
        $queueItemTable->addColumn('type', 'string');
        $queueItemTable->addColumn('queue', 'string');
        $queueItemTable->addColumn('status', 'string');
        $queueItemTable->addColumn('settings', 'text');
        $queueItemTable->addColumn("created", "bigint", ['default' => 0, 'notnull' => false]);
        $queueItemTable->addColumn("started", "bigint", ['default' => 0, 'notnull' => false]);
        $queueItemTable->addColumn("completed", "bigint", ['default' => 0, 'notnull' => false]);
        $queueItemTable->setPrimaryKey(['id']);


    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        if ($schema->hasTable('process_manager_queueitems')) {
            $schema->dropTable('process_manager_queueitems');
        }
    }
}
