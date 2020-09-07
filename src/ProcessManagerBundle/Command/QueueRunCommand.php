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

namespace ProcessManagerBundle\Command;

use Pimcore\Console\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ProcessManagerBundle\Model\QueueItem;
use ProcessManagerBundle\Process\QueueAwareProcessInterface;
use CoreShop\Component\Registry\ServiceRegistry;

final class QueueRunCommand extends AbstractCommand
{
    private $registry;

    public function __construct(ServiceRegistry $registry)
    {
        parent::__construct();
        $this->registry = $registry;
    }


    protected function configure()
    {
        $this
            ->setName('processmanager:queue:run')
            ->setDescription('Run next process from queue.')
            ->setHelp(
                <<<EOT
The <info>%command.name%</info> runs next process from queue.
EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var QueueItem $queueItem */
        foreach ($this->getQueueItems(QueueItem::STATUS_QUEUED) as $queueItem) {
            $process = $this->registry->get($queueItem->getType());
            if ($this->canRun($queueItem, $process)) {
                $queueItem->setStarted(time());
                $queueItem->setStatus(QueueItem::STATUS_RUNNING);
                $queueItem->save();
                try {
                    // Start process in foreground in order for us to be able to mark the queue item as completed (success/failed) after.
                    $process->runFromQueue($queueItem);
                    $queueItem->setCompleted(time());
                    $queueItem->setStatus(QueueItem::STATUS_RUNNING);
                    $queueItem->save();
                } catch (\Exception $e) {
                    $queueItem->setCompleted(time());
                    $queueItem->setStatus(QueueItem::STATUS_FAILED);
                    $queueItem->save();                    
                }
                break; // only start one process per run
            }
        }

        return 0;
    }

    /**
     * @return QueueItem[]
     */
    protected function getQueueItems($status, $queue=null)
    {
        $queueItems = new QueueItem\Listing();
        $queueItems->setCondition('status = ?', $status);
        if ($queue !== null) {
            $queueItems->setCondition('queue = ?', $queue);
        }
        return $queueItems->getObjects();
    }

    /** 
     * @param QueueItem $queueItem
     * @return boolean
     */
    protected function canRun($queueItem, $process)
    {
        if ($process instanceof QueueAwareProcessInterface) {
            return $process->canRun($queueItem);
        } else {
            return count($this->getQueueItems(QueueItem::STATUS_RUNNING, $queueItem->getQueue())) > 0;
        }
    }

}