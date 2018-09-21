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

use Monolog\Handler\HandlerInterface;
use ProcessManagerBundle\Model\ProcessInterface;

interface HandlerFactoryInterface
{
    /**
     * @param ProcessInterface $process
     * @return HandlerInterface
     */
    public function getLogHandler(ProcessInterface $process);

    /**
     * @param ProcessInterface $process
     * @return mixed
     */
    public function getLog(ProcessInterface $process);

    /**
     * @param ProcessInterface $process
     */
    public function cleanup(ProcessInterface $process);
}