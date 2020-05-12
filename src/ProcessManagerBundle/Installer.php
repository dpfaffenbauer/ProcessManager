<?php
/**
 * Process Manager.
 *
 * LICENSE
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015-2020 Dominik Pfaffenbauer (https://www.pfaffenbauer.at)
 * @license    https://github.com/dpfaffenbauer/ProcessManager/blob/master/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

namespace ProcessManagerBundle;

use Doctrine\DBAL\Migrations\Version;
use Doctrine\DBAL\Schema\Schema;
use Pimcore\Extension\Bundle\Installer\MigrationInstaller;
use Pimcore\Model\User\Permission;

class Installer extends MigrationInstaller
{
    /**
     * {@inheritdoc}
     */
    public function migrateInstall(Schema $schema, Version $version)
    {
        // DB Tables
        $processTable = $schema->createTable('process_manager_processes');
        $processTable->addColumn('id', 'integer')
            ->setAutoincrement(true);
        $processTable->addColumn('name', 'string');
        $processTable->addColumn('message', 'text');
        $processTable->addColumn('progress', 'integer');
        $processTable->addColumn('total', 'integer');
        $processTable->setPrimaryKey(['id']);

        $execTable = $schema->createTable('process_manager_executables');
        $execTable->addColumn('id', 'integer')
            ->setAutoincrement(true);
        $execTable->addColumn('name', 'string');
        $execTable->addColumn('description', 'text');
        $execTable->addColumn('type', 'string');
        $execTable->addColumn('cron', 'string');
        $execTable->addColumn('settings', 'text');
        $execTable->addColumn('active', 'boolean')
            ->setDefault(1);
        $execTable->setPrimaryKey(['id']);

        // Permissions
        $permission = new Permission\Definition();
        $permission->setKey('process_manager');

        $res = new Permission\Definition\Dao();
        $res->configure();
        $res->setModel($permission);
        $res->save();
    }

    /**
     * {@inheritdoc}
     */
    public function migrateUninstall(Schema $schema, Version $version)
    {
        $tables = [
            'process_manager_processes',
            'process_manager_executables'
        ];

        foreach ($tables as $table) {
            if ($schema->hasTable($table)) {
                $schema->dropTable($table);
            }
        }
    }
}
