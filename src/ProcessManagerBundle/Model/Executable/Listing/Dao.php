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

use ProcessManagerBundle\Model\Executable;
use Pimcore\Model\Listing;

class Dao extends Listing\Dao\AbstractDao
{
    public function load()
    {
        $executablesData = $this->db->fetchCol('SELECT id FROM process_manager_executables ' . $this->getCondition() . $this->getOrder() . $this->getOffsetLimit(), $this->model->getConditionVariables());
        $executables = [];

        foreach ($executablesData as $executableData) {
            $executables[] = Executable::getById($executableData);
        }

        $this->model->setObjects($executables);

        return $executables;
    }

    public function getTotalCount()
    {
        $amount = (int) $this->db->fetchOne('SELECT COUNT(*) as amount FROM process_manager_executables '.$this->getCondition(), $this->model->getConditionVariables());

        return $amount;
    }
}
