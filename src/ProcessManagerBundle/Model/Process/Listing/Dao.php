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

namespace ProcessManagerBundle\Model\Process\Listing;

use ProcessManagerBundle\Model\Process;
use Pimcore\Model\Listing;

class Dao extends Listing\Dao\AbstractDao
{
    public function load()
    {
        $processesData = $this->db->fetchCol('SELECT id FROM process_manager_processes ' . $this->getCondition() . $this->getOrder() . $this->getOffsetLimit(), $this->model->getConditionVariables());
        $processes = [];

        foreach ($processesData as $processData) {
            $processes[] = Process::getById($processData);
        }

        $this->model->setObjects($processes);

        return $processes;
    }

    public function getTotalCount()
    {
        $amount = (int) $this->db->fetchOne('SELECT COUNT(*) as amount FROM process_manager_processes '.$this->getCondition(), $this->model->getConditionVariables());

        return $amount;
    }
}
