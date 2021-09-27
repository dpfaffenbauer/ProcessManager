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

namespace ProcessManagerBundle\Model\Executable\Listing;

use Doctrine\DBAL\Query\QueryBuilder as DoctrineQueryBuilder;
use Pimcore\Model\Listing\Dao\QueryBuilderHelperTrait;
use ProcessManagerBundle\Model\Executable;
use Pimcore\Model\Listing;
use ProcessManagerBundle\Model\ExecutableInterface;

class Dao extends Listing\Dao\AbstractDao
{
    use QueryBuilderHelperTrait;

    /**
     * @var string
     */
    protected $tableName = 'process_manager_executables';

    /**
     * Get tableName, either for localized or non-localized data.
     *
     * @return string
     */
    protected function getTableName()
    {
        return $this->tableName;
    }

    public function getQueryBuilder(...$columns): DoctrineQueryBuilder
    {
        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder->select(...$columns)->from($this->getTableName());

        $this->applyListingParametersToQueryBuilder($queryBuilder);

        return $queryBuilder;
    }

    /**
     * Loads objects from the database.
     *
     * @return ExecutableInterface[]
     */
    public function load()
    {
        // load id's
        $list = $this->loadIdList();

        $objects = array();
        foreach ($list as $o_id) {
            if ($object = Executable::getById($o_id)) {
                $objects[] = $object;
            }
        }

        $this->model->setObjects($objects);

        return $objects;
    }

    /**
     * Loads a list for the specicifies parameters, returns an array of ids.
     *
     * @return array
     * @throws \Exception
     */
    public function loadIdList()
    {
        $queryBuilder = $this->getQueryBuilder([sprintf('%s as id', $this->getTableName() . '.id')]);
        $objectIds = $this->db->fetchCol((string) $queryBuilder, $this->model->getConditionVariables(), $this->model->getConditionVariableTypes());

        return array_map('intval', $objectIds);
    }

    /**
     * Get Count.
     *
     * @return int
     *
     * @throws \Exception
     */
    public function getCount()
    {
       if ($this->model->isLoaded()) {
            return count($this->model->getObjects());
        } else {
            $idList = $this->loadIdList();

            return count($idList);
        }
    }

    /**
     * Get Total Count.
     *
     * @return int
     *
     * @throws \Exception
     */
    public function getTotalCount()
    {
        $queryBuilder = $this->getQueryBuilder();
        $this->prepareQueryBuilderForTotalCount($queryBuilder);

        $totalCount = $this->db->fetchOne((string) $queryBuilder, $this->model->getConditionVariables(), $this->model->getConditionVariableTypes());

        return (int) $totalCount;
    }
}
