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

interface ProcessLoggerInterface
{
    /**
     * System is unusable.
     *
     * @param ProcessInterface $process
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function emergency(ProcessInterface $process, $message, array $context = array());

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param ProcessInterface $process
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function alert(ProcessInterface $process, $message, array $context = array());

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param ProcessInterface $process
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function critical(ProcessInterface $process, $message, array $context = array());

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param ProcessInterface $process
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function error(ProcessInterface $process, $message, array $context = array());

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param ProcessInterface $process
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function warning(ProcessInterface $process, $message, array $context = array());

    /**
     * Normal but significant events.
     *
     * @param ProcessInterface $process
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function notice(ProcessInterface $process, $message, array $context = array());

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param ProcessInterface $process
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function info(ProcessInterface $process, $message, array $context = array());

    /**
     * Detailed debug information.
     *
     * @param ProcessInterface $process
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function debug(ProcessInterface $process, $message, array $context = array());

    /**
     * Logs with an arbitrary level.
     *
     * @param ProcessInterface $process
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function log(ProcessInterface $process, $level, $message, array $context = array());
}
