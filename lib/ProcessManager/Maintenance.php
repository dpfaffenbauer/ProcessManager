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
 * @copyright  Copyright (c) 2016 lineofcode.at (http://www.lineofcode.at)
 * @license    https://github.com/dpfaffenbauer/ProcessManager/blob/master/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

namespace ProcessManager;

use Cron\CronExpression;
use ProcessManager\Model\Executable;

/**
 * Class Maintenance
 * @package ProcessManager
 */
class Maintenance {

    /**
     * Runs Cron Jobs
     */
    public static function runCron() {
        $list = new Model\Executable\Listing();
        $list->load();

        foreach($list->getData() as $exec) {
            if($exec instanceof Executable) {
                if($exec->getActive() && $exec->getCron()) {
                    $cron = CronExpression::factory($exec->getCron());

                    if($cron->isDue()) {
                        $exec->run();
                    }
                }
            }
        }
    }
}