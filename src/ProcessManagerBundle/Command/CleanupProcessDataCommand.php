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
 * @copyright  Copyright (c) 2015-2020 Wojciech Peisert (http://divante.co/)
 * @license    https://github.com/dpfaffenbauer/ProcessManager/blob/master/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

namespace ProcessManagerBundle\Command;

use Doctrine\DBAL\Exception;
use Pimcore\Console\AbstractCommand;
use Pimcore\Db;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CleanupProcessDataCommand extends AbstractCommand
{
    private string $logDirectory;

    public function __construct(string $logDirectory) {
        parent::__construct();
        $this->logDirectory = $logDirectory;
    }
    protected function configure(): void
    {
        $this
            ->setName('process-manager:cleanup-process-data')
            ->setDescription('Cleanup process data from the database and from log file directory')
            ->setHelp(
                <<<EOT
The <info>%command.name%</info> cleanup process data from the database and from log file directory.
EOT
            )
            ->addOption(
                'keeplogs',
                'k',
                InputOption::VALUE_NONE,
                'Keep log files',
            )
            ->addOption(
                'seconds',
                's',
                InputOption::VALUE_OPTIONAL,
                'Cleanup process data older than this number of seconds (default "604800" - 7 days)',
                604800
            );
    }

    /**
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $keepLogs = $input->getOption('keeplogs');
        if ($input->getOption('seconds')) {
            $seconds = (int)$input->getOption('seconds');
        }

        // start deleting database entries older than x seconds
        $this->output->writeln('start cleaning database entries older than ' . $seconds . ' seconds');
        $connection = Db::get();
        $connection->executeStatement('DELETE FROM process_manager_processes  WHERE started < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL ? SECOND))', [$seconds]);
        $this->output->writeln('finish cleaning database entries older than ' . $seconds . ' seconds');

        // start deleting log files older than x seconds
        if (!$keepLogs && is_dir($this->logDirectory)) {
            $this->output->writeln('start cleaning log files older than ' . $seconds . ' seconds');
            $files = scandir($this->logDirectory);
            foreach ($files as $file) {
                $filePath = $this->logDirectory . '/' . $file;
                if (
                    file_exists($filePath) &&
                    str_contains($file, 'process_manager_') &&
                    filemtime($filePath) < time() - $seconds
                ) {
                    unlink($filePath);
                }
            }
            $this->output->writeln('finish cleaning logfile entries older than ' . $seconds . ' seconds');
        }
        return Command::SUCCESS;
    }
}
