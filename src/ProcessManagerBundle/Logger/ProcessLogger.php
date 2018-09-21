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

namespace ProcessManagerBundle\Logger;

use ProcessManagerBundle\Model\ProcessInterface;
use Psr\Log\LoggerInterface;

class ProcessLogger implements ProcessLoggerInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function emergency(ProcessInterface $process, $message, array $context = array())
    {
        $context['process'] = $process;

        $this->logger->emergency($message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function alert(ProcessInterface $process, $message, array $context = array())
    {
        $context['process'] = $process;

        $this->logger->alert($message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function critical(ProcessInterface $process, $message, array $context = array())
    {
        $context['process'] = $process;

        $this->logger->critical($message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function error(ProcessInterface $process, $message, array $context = array())
    {
        $context['process'] = $process;

        $this->logger->error($message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function warning(ProcessInterface $process, $message, array $context = array())
    {
        $context['process'] = $process;

        $this->logger->warning($message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function notice(ProcessInterface $process, $message, array $context = array())
    {
        $context['process'] = $process;

        $this->logger->notice($message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function info(ProcessInterface $process, $message, array $context = array())
    {
        $context['process'] = $process;

        $this->logger->info($message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function debug(ProcessInterface $process, $message, array $context = array())
    {
        $context['process'] = $process;

        $this->logger->debug($message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function log(ProcessInterface $process, $level, $message, array $context = array())
    {
        $context['process'] = $process;

        $this->logger->log($level, $message, $context);
    }
}