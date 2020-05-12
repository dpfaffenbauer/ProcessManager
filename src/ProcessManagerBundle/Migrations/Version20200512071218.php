<?php

namespace ProcessManagerBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Pimcore\Migrations\Migration\AbstractPimcoreMigration;
use Pimcore\Model\User\Permission;

class Version20200512071218 extends AbstractPimcoreMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $permission = new Permission\Definition();
        $permission->setKey('process_manager');

        $res = new Permission\Definition\Dao();
        $res->configure();
        $res->setModel($permission);
        $res->save();
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        // nothing to do here ...
    }
}
