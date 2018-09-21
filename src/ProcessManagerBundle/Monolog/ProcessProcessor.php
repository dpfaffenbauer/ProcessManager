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
 * @copyright  Copyright (c) 2015-2017 Dominik Pfaffenbauer (https://www.pfaffenbauer.at)
 * @license    https://github.com/dpfaffenbauer/ProcessManager/blob/master/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

namespace ProcessManagerBundle\Monolog;

use CoreShop\Component\Registry\ServiceRegistryInterface;
use Monolog\Logger;
use ProcessManagerBundle\Logger\HandlerFactoryInterface;
use ProcessManagerBundle\Model\ProcessInterface;

class ProcessProcessor
{
    /**
     * @var HandlerFactoryInterface
     */
    private $defaultHandlerFactory;

    /**
     * @var array
     */
    private $loggers = [];

    /**
     * @var ServiceRegistryInterface
     */
    private $registry;

    /**
     * @param HandlerFactoryInterface  $defaultHandlerFactory
     * @param ServiceRegistryInterface $registry
     */
    public function __construct(HandlerFactoryInterface $defaultHandlerFactory, ServiceRegistryInterface $registry)
    {
        $this->defaultHandlerFactory = $defaultHandlerFactory;
        $this->registry = $registry;
    }

    public function __invoke(array $record): array
    {
        if (!isset($record['context']['process'])) {
            return $record;
        }

        $process = $record['context']['process'];

        if (!$process instanceof ProcessInterface) {
            return $process;
        }

        if (!array_key_exists($process->getId(), $this->loggers)) {
            $log = new Logger('process_'.$process->getId());

            if ($process->getType() && $this->registry->has($process->getType())) {
                /**
                 * @var $handlerFactory HandlerFactoryInterface
                 */
                $handlerFactory = $this->registry->get($process->getType());

                $handler = $handlerFactory->getLogHandler($process);
            } else {
                $handler = $this->defaultHandlerFactory->getLogHandler($process);
            }

            $log->pushHandler($handler);

            $this->loggers[$process->getId()] = $log;
        } else {
            $log = $this->loggers[$process->getId()];
        }

        $record['extra']['process'] = $process;

        unset($record['context']['process']);

        $log->addRecord($record['level'], $record['message'], $record['context']);

        return $record;
    }
}