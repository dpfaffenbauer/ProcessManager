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
    /**
     * @var string
     */
    private $logDirectory;

    /**
     * @var bool
     */
    private $preventLogfileCleanup;

    /**
     * @param string $logDirectory
     * @param bool   $preventLogfileCleanup
     */
    public function __construct(string $logDirectory, bool $preventLogfileCleanup)
    {
        $this->logDirectory = $logDirectory;
        $this->preventLogfileCleanup = $preventLogfileCleanup;
    }

    /**
     * {@inheritdoc}
     */
    public function getLogHandler(ProcessInterface $process)
    {
        if ($this->preventLogfileCleanup) {
            return new StreamHandler(sprintf(
                '%s/processmanager/process_manager_%s.log',
                $this->logDirectory,
                $process->getId()
            ));
        }

        return new StreamHandler(sprintf('%s/process_manager_%s.log', $this->logDirectory, $process->getId()));
    }

    /**
     * {@inheritdoc}
     */
    public function getLog(ProcessInterface $process)
    {
        $path = sprintf('%s/process_manager_%s.log', $this->logDirectory, $process->getId());

        if ($this->preventLogfileCleanup) {
            $path = sprintf('%s/processmanager/process_manager_%s.log', $this->logDirectory, $process->getId());
        }

        if (file_exists($path)) {
            return file_get_contents($path);
        }

        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function cleanup(ProcessInterface $process)
    {
        $path = sprintf('%s/process_manager_%s.log', $this->logDirectory, $process->getId());

        if ($this->preventLogfileCleanup) {
            $path = sprintf('%s/processmanager/process_manager_%s.log', $this->logDirectory, $process->getId());
        }

        if (file_exists($path)) {
            unlink($path);
        }
    }
}
