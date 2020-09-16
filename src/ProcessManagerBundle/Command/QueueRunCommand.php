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
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ProcessManagerBundle\Model\QueueItem;
use ProcessManagerBundle\Process\QueueAwareProcessInterface;
use CoreShop\Component\Registry\ServiceRegistry;
use Symfony\Component\Console\Helper\FormatterHelper;

final class QueueRunCommand extends AbstractCommand
{
    /**
     * @var ServiceRegistry
     */
    private $registry;

    /**
     * @var boolean
     */
    private $quiet;

    /**
     * @var FormatterHelper
     */
    private $formatter;

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
            ->addOption('id', 'i', InputOption::VALUE_REQUIRED, 'Run queue item with given id')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Force process to run')
            ->setHelp(
                <<<EOT
The <info>%command.name%</info> runs next process from queue.
EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->quiet = boolval($input->getOption('quiet'));        
        $this->formatter = $this->getHelper('formatter');
        
        /** @var QueueItem $queueItem */        
        foreach ($this->getQueueItems($input->getOption('id')) as $queueItem) {
            
            $process = $this->registry->get($queueItem->getType());
            
            if ($process == null) {
                $queueItem->complete(QueueItem::STATUS_FAILED);
                $output->writeln('<error>Failed to load process of type ' . $queueItem->getType() . '</error>');
            }
            else if (!($process instanceof QueueAwareProcessInterface)) {
                $queueItem->complete(QueueItem::STATUS_FAILED);
                $output->writeln('Unable to run process of type ' . $queueItem->getType() . ' from queue since it does not implement QueueAwareProcessInterface</error>');
            }
            else if ($input->getOption('force') || $process->canRun($queueItem)) {
                $queueItem->start();
                try {
                    $this->outputInfo(sprintf('Process %s with id %d from queue %s has started', $queueItem->getName(), $queueItem->getId(), $queueItem->getQueue()));
                    // Start process in foreground in order for us to be able to mark the queue item as completed (success/failed) after.
                    $process->runFromQueue($queueItem);                    
                    $this->outputInfo(sprintf('Process %s with id %d from queue %s has completed successfully', $queueItem->getName(), $queueItem->getId(), $queueItem->getQueue()));                
                    $queueItem->complete(QueueItem::STATUS_SUCCESS);                
                } catch (\Exception $e) {
                    $this->outputError(sprintf('Process %s with id %d from queue %s has failed with exception: %s', $queueItem->getName(), $queueItem->getId(), $queueItem->getQueue(), $e->getMessage()));                    
                    $queueItem->complete(QueueItem::STATUS_FAILED);
                }
                break; // only start one process per run
            } 
            else if ($input->hasOption('id')) {
                $this->outputError(sprintf('Process %s with id %d could not run', $queueItem->getName(), $queueItem->getId()));
            }
        }

        return 0;
    }

    /**
     * Get all queued items or if queueId is set only that one item
     *
     * @param integer|null $queueId
     * @return QueueItem[]
     */
    protected function getQueueItems(?int $queueId)
    {
        if ($queueId !== null) {
            $queueItems = QueueItem::getById($queueId);
            if ($queueItems) {
                return [$queueItems];
            } else {
                $this->outputError(sprintf('Failed to load queue item with id %d', $queueId));
                return [];
            }
        } else {
            $queueItems = new QueueItem\Listing();
            $queueItems->setCondition('status = ?', QueueItem::STATUS_QUEUED);
            return $queueItems->getObjects();
        }
    }

    protected function outputError(string $message)
    {
        if (!$this->quiet) {
            $this->output->writeln($this->formatter->formatBlock($message, 'error', true));
        }
    }

    protected function outputInfo(string $message)
    {
        if (!$this->quiet) {
            $this->output->writeln($this->formatter->formatBlock($message, 'info'));
        }
    }
}