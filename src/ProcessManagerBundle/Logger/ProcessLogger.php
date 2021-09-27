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

namespace ProcessManagerBundle\Logger;

use ProcessManagerBundle\Model\ProcessInterface;
use Psr\Log\LoggerInterface;

class ProcessLogger implements ProcessLoggerInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function emergency(ProcessInterface $process, string $message, array $context = []): void
    {
        $context['process'] = $process;

        $this->logger->emergency($message, $context);
    }

    public function alert(ProcessInterface $process, string $message, array $context = []): void
    {
        $context['process'] = $process;

        $this->logger->alert($message, $context);
    }

    public function critical(ProcessInterface $process, string $message, array $context = []): void
    {
        $context['process'] = $process;

        $this->logger->critical($message, $context);
    }

    public function error(ProcessInterface $process, string $message, array $context = []): void
    {
        $context['process'] = $process;

        $this->logger->error($message, $context);
    }

    public function warning(ProcessInterface $process, string $message, array $context = []): void
    {
        $context['process'] = $process;

        $this->logger->warning($message, $context);
    }

    public function notice(ProcessInterface $process, string $message, array $context = []): void
    {
        $context['process'] = $process;

        $this->logger->notice($message, $context);
    }

    public function info(ProcessInterface $process, string $message, array $context = []): void
    {
        $context['process'] = $process;

        $this->logger->info($message, $context);
    }

    public function debug(ProcessInterface $process, string $message, array $context = []): void
    {
        $context['process'] = $process;

        $this->logger->debug($message, $context);
    }

    public function log(ProcessInterface $process, int $level, string $message, array $context = []): void
    {
        $context['process'] = $process;

        $this->logger->log($level, $message, $context);
    }
}
