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
use ProcessManagerBundle\Service\CleanupService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CleanupProcessDataCommand extends AbstractCommand
{
    public function __construct(private CleanupService $cleanupService, private string $logDirectory) {
        parent::__construct();
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
        $output->writeln('start cleaning database entries older than ' . $seconds . ' seconds');
        $this->cleanupService->cleanupDbEntries($seconds);
        $output->writeln('finish cleaning database entries older than ' . $seconds . ' seconds');

        // start deleting log files older than x seconds
        $output->writeln('start cleaning log files older than ' . $seconds . ' seconds');
        $this->cleanupService->cleanupLogFiles($this->logDirectory, $seconds, $keepLogs);
        $output->writeln('finish cleaning logfile entries older than ' . $seconds . ' seconds');
        return Command::SUCCESS;
    }
}
