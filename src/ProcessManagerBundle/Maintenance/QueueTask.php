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

namespace ProcessManagerBundle\Maintenance;

use Pimcore\Maintenance\TaskInterface;
use CoreShop\Component\Registry\ServiceRegistry;
use ProcessManagerBundle\Model\QueueItem;
use ProcessManagerBundle\Model\QueueItemInterface;
use ProcessManagerBundle\Process\QueueAwareProcessInterface;
use ProcessManagerBundle\ProcessManagerBundle;

class QueueTask implements TaskInterface
{
    private $registry;

    public function __construct(ServiceRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function execute()
    {
        /** @var QueueItemInterface $queueItem */
        foreach ($this->getQueueItems() as $queueItem) {
            if (!$this->registry->has($queueItem->getType())) {
                continue;
            }
            $process = $this->registry->get($queueItem->getType());

            if ($process instanceof QueueAwareProcessInterface) {
                $canRun = $process->canRun($queueItem);
                $method = 'runFromQueue';
            } else {
                $canRun = $this->canRun($queueItem);
                $method = 'run';
            }
            if ($canRun) {
                // Set env variable for queue id which allows process to know it was started from queue.
                putenv(sprintf('%s=%s', ProcessManagerBundle::ENV_QUEUE_ITEM_ID, $queueItem->getId()));

                // Mark queue item as starting up. The process itself is responsible for setting all further statuses.
                $queueItem->setStatus(ProcessManagerBundle::STATUS_STARTING);
                $queueItem->setStarted(time());
                $queueItem->save();

                // Run the process
                $process->{$method}($queueItem);
            }
        }
    }

    /**
     * Get all queued items
     *
     * @return QueueItem[]
     */
    protected function getQueueItems()
    {
        $queueItems = new QueueItem\Listing();
        $queueItems->setCondition('status = ?', ProcessManagerBundle::STATUS_QUEUED);
        return $queueItems->getObjects();
    }

    /**
     * Default canRun logic for processes that are not aware of the queue. Can run if status
     * is STATUS_QUEUED and there are no processes, from same queue, with status STATUS_RUNNING.
     *
     * @param QueueItemInterface $queueItem
     * @return bool
     */
    protected function canRun(QueueItemInterface $queueItem) : bool
    {
        $queueItems = new \ProcessManagerBundle\Model\QueueItem\Listing();
        $queueItems->setCondition('queue = ? AND (status = ? OR status = ?)', [$queueItem->getQueue(), ProcessManagerBundle::STATUS_RUNNING, ProcessManagerBundle::STATUS_STARTING]);

        return $queueItems->count() === 0 && $queueItem->getStatus() === ProcessManagerBundle::STATUS_QUEUED;
    }
}
