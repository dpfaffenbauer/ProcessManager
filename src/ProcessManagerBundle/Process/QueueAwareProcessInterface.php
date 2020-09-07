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

namespace ProcessManagerBundle\Process;

use ProcessManagerBundle\Model\QueueItemInterface;

interface QueueAwareProcessInterface extends ProcessInterface
{
    /**
     * Determine if this process can run right now or not
     *
     * @param QueueItemInterface $queueItem
     * @param array $params
     * @return boolean
     */
    function canRun(QueueItemInterface $queueItem, array $params = null);

    /**
     * Runs the process in foreground! This way the queue runner can set status of the queue item after execution.
     *
     * @param QueueItemInterface $queueItem
     * @param array $params
     * @return void
     */
    function runFromQueue(QueueItemInterface $queueItem, array $params = null);
}
