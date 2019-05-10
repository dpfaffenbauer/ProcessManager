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

use Monolog\Handler\StreamHandler;
use ProcessManagerBundle\Model\ProcessInterface;

class DefaultHandlerFactory implements HandlerFactoryInterface
{
    /**
     * @var string
     */
    private $logDirectory;

    /**
     * @param string $logDirectory
     */
    public function __construct(string $logDirectory)
    {
        $this->logDirectory = $logDirectory;
    }

    /**
     * {@inheritdoc}
     */
    public function getLogHandler(ProcessInterface $process)
    {
        // if no archive, load regular file
        if (false === ($path = $this->getArchiveFileStreamWrapper($process))) {
            $path = $this->getLogFilePath($process);
        }

        return new StreamHandler($path);
    }

    /**
     * {@inheritdoc}
     */
    public function getLog(ProcessInterface $process)
    {
        // if archive, load content
        if (false !== ($path = $this->getArchiveFileStreamWrapper($process))) {
            return file_get_contents($path);
        } else { // regular file
            $path = $this->getLogFilePath($process);

            if (file_exists($path)) {
                return file_get_contents($path);
            }
        }

        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function cleanup(ProcessInterface $process)
    {
        $archivePath = $this->getArchiveFilePath($process);

        if (file_exists($archivePath)) {
            unlink($archivePath);
        }

        $path = $this->getLogFilePath($process);

        if (file_exists($path)) {
            unlink($path);
        }
    }

    /**
     * Get log filename full path
     * @param ProcessInterface $process
     * @return string
     */
    private function getLogFilePath(ProcessInterface $process)
    {
        return sprintf('%s/process_manager_%s.log', $this->logDirectory, $process->getId());
    }

    /**
     * Find log file in log directory and return file name if it is found.
     * Archived file name example is process_manager_480-archive-2019-04-16.log.gz which contains file
     * process_manager_480-archive-2019-04-16.log
     * @param ProcessInterface $process
     * @return string|boolean first log file name matching criteria or false if not found
     */
    private function getArchiveFilePath(ProcessInterface $process)
    {
        $archivePattern = sprintf(
            '%s/process_manager_%s-archive-*.log.gz',
            $this->logDirectory,
            $process->getId()
        );

        // load file list
        $archivedLogFiles = glob($archivePattern);

        return reset($archivedLogFiles);
    }

    /**
     * Return stream wrapper path to be used to open file.
     * @param ProcessInterface $process
     * @return bool|string
     */
    private function getArchiveFileStreamWrapper(ProcessInterface $process)
    {
        // archive file does not exist
        if(false === ($archivePath = $this->getArchiveFilePath($process))) {
            return false;
        }

        // there is only one file in archive, no need to specify its filename
        return sprintf('compress.zlib://%s', $archivePath);
    }
}