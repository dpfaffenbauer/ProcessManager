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

interface ProcessLoggerInterface
{
    /**
     * System is unusable.
     */
    public function emergency(ProcessInterface $process, string $message, array $context = []): void;

    /**
     * Action must be taken immediately.
     */
    public function alert(ProcessInterface $process, string $message, array $context = []): void;

    /**
     * Critical conditions.
     */
    public function critical(ProcessInterface $process, string $message, array $context = []): void;

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     */
    public function error(ProcessInterface $process, string $message, array $context = []): void;

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     */
    public function warning(ProcessInterface $process, string $message, array $context = []): void;

    /**
     * Normal but significant events.
     */
    public function notice(ProcessInterface $process, string $message, array $context = []): void;

    /**
     * Interesting events.
     */
    public function info(ProcessInterface $process, string $message, array $context = []): void;

    /**
     * Detailed debug information.
     * @return void
     */
    public function debug(ProcessInterface $process, string $message, array $context = []): void;

    /**
     * Logs with an arbitrary level.
     */
    public function log(ProcessInterface $process, int $level, string $message, array $context = []): void;
}
