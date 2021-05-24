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

namespace ProcessManagerBundle\Monolog;

use Monolog\Handler\AbstractHandler;
use Monolog\Logger;

class ProcessHandler extends AbstractHandler
{
    private bool $logProcessIntoRegularLogFile;

    public function __construct(
        bool $logProcessIntoRegularLogFile = false,
        $level = Logger::DEBUG,
        $bubble = true
    )
    {
        parent::__construct($level, $bubble);

        $this->logProcessIntoRegularLogFile = $logProcessIntoRegularLogFile;
    }

    public function handle(array $record): bool
    {
        if (!array_key_exists('process', $record['extra'])) {
            return true;
        }

        return $this->logProcessIntoRegularLogFile;
    }
}
