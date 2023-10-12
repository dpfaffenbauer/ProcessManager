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

use Monolog\Handler\StreamHandler;
use ProcessManagerBundle\Model\ProcessInterface;

class DefaultHandlerFactory implements HandlerFactoryInterface
{
    private string $logDirectory;
    private bool $keepLogs;

    public function __construct(string $logDirectory, bool $keepLogs)
    {
        $this->logDirectory = $logDirectory;
        $this->keepLogs = $keepLogs;
    }

    public function getLogHandler(ProcessInterface $process): StreamHandler
    {
        return new StreamHandler(sprintf('%s/process_manager_%s.log', $this->logDirectory, $process->getId()));
    }

    public function getLog(ProcessInterface $process): string
    {
        $path = sprintf('%s/process_manager_%s.log', $this->logDirectory, $process->getId());

        if (file_exists($path)) {
            return file_get_contents($path);
        }

        return '';
    }

    public function cleanup(ProcessInterface $process): void
    {
        $path = sprintf('%s/process_manager_%s.log', $this->logDirectory, $process->getId());

        if (!$this->keepLogs && file_exists($path)) {
            unlink($path);
        }
    }
}
