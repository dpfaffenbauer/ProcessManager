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

use Pimcore\Db;
use Pimcore\Extension\Bundle\Installer\SettingsStoreAwareInstaller;
use Pimcore\Model\User\Permission;

class Installer extends SettingsStoreAwareInstaller
{
    public function install(): void
    {
        $schema = Db::get()->getSchemaManager()->createSchema();

        if (!$schema->hasTable('process_manager_processes')) {
            $processTable = $schema->createTable('process_manager_processes');
            $processTable->addColumn('id', 'integer')
                ->setAutoincrement(true);
            $processTable->addColumn('name', 'string');
            $processTable->addColumn('message', 'text');
            $processTable->addColumn('progress', 'integer');
            $processTable->addColumn('total', 'integer');
            $processTable->addColumn('type', 'string');
            $processTable->addColumn('started', 'bigint')->setDefault(0)->setNotnull(false);
            $processTable->addColumn('completed', 'bigint')->setDefault(0)->setNotnull(false);
            $processTable->addColumn('artifact', 'integer')->setNotnull(false);
            $processTable->addColumn('stoppable', 'boolean')->setDefault(false)->setNotnull(false);
            $processTable->addColumn('status', 'string')->setNotnull(false);
            $processTable->setPrimaryKey(['id']);
        }

        if (!$schema->hasTable('process_manager_executables')) {
            $execTable = $schema->createTable('process_manager_executables');
            $execTable->addColumn('id', 'integer')
                ->setAutoincrement(true);
            $execTable->addColumn('name', 'string');
            $execTable->addColumn('description', 'text');
            $execTable->addColumn('type', 'string');
            $execTable->addColumn('cron', 'string');
            $execTable->addColumn('settings', 'text');
            $execTable->addColumn('active', 'boolean')->setDefault(1);
            $execTable->addColumn('lastrun', 'bigint')->setDefault(0)->setNotnull(false);
            $execTable->setPrimaryKey(['id']);
        }

        $sqls = $schema->getMigrateFromSql(Db::get()->getSchemaManager()->createSchema(), Db::get()->getDatabasePlatform());

        foreach ($sqls as $sql) {
            Db::get()->exec($sql);
        }

        $permission = new Permission\Definition();
        $permission->setKey('process_manager');

        $res = new Permission\Definition\Dao();
        $res->configure();
        $res->setModel($permission);
        $res->save();

        parent::install();
    }

    public function uninstall(): void
    {
        $schema = Db::get()->getSchemaManager()->createSchema();

        $tables = [
            'process_manager_processes',
            'process_manager_executables'
        ];

        foreach ($tables as $table) {
            if ($schema->hasTable($table)) {
                $schema->dropTable($table);
            }
        }

        parent::uninstall();
    }
}
