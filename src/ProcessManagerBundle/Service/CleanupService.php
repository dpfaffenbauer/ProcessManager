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

namespace ProcessManagerBundle\Service;

use Doctrine\DBAL\Exception;
use Pimcore\Db;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CleanupService
{
    public ParameterBagInterface $parameterBag;
    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    /**
     * Cleanup process db entries from the database
     *
     * @param int|null $seconds Only entries older than x seconds will be deleted
     *                          None or empty value will delete all entries
     * @throws Exception
     */
    public function cleanupDbEntries(?int $seconds): void
    {
        // delete all entries if there is no time passed
        if (empty($seconds)) {
            $seconds = 0;
        }
        $connection = Db::get();
        $connection->executeStatement('DELETE FROM process_manager_processes  WHERE started < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL ? SECOND))', [$seconds]);
    }

    /**
     * Cleanup all log files
     *
     * @param string $logDirectory Path to the log files. If not specified, default path from config will be used
     * @param int|null $seconds Only entries older than x seconds will be deleted
     *                          None or empty value will delete all entries
     * @param bool $keepLogs Whether to keep the log files or not
     *                       true - Keep the log files
     *                       false - Cleanup the logiles
     * @return void
     */
    public function cleanupLogFiles(string $logDirectory, ?int $seconds, bool $keepLogs = true): void
    {
        if (empty($logDirectory)) {
            $logDirectory = $this->parameterBag->get('process_manager.log_directory');
        }
        // delete all entries if there is no time passed
        if (empty($seconds)) {
            $seconds = 0;
        }
        if (!$keepLogs && is_dir($logDirectory)) {
            $files = scandir($logDirectory);
            foreach ($files as $file) {
                $filePath = $logDirectory . '/' . $file;
                if (
                    file_exists($filePath) &&
                    str_contains($file, 'process_manager_') &&
                    filemtime($filePath) < time() - $seconds
                ) {
                    unlink($filePath);
                }
            }
        }
    }
}