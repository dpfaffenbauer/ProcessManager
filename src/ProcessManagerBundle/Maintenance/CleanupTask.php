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
 * @copyright  Copyright (c) 2018 Jakub Płaskonka (jplaskonka@divante.pl)
 * @license    https://github.com/dpfaffenbauer/ProcessManager/blob/master/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

namespace ProcessManagerBundle\Maintenance;

use Pimcore\Maintenance\TaskInterface;
use ProcessManagerBundle\Service\CleanupService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CleanupTask implements TaskInterface
{
    private CleanupService $cleanupService;
    private ParameterBagInterface $parameterBag;

    public function __construct(CleanupService $cleanupService, ParameterBagInterface $parameterBag) {
        $this->cleanupService = $cleanupService;
        $this->parameterBag = $parameterBag;
    }
    public function execute(): void
    {
        $seconds = $this->parameterBag->get('process_manager.seconds');
        $logDirectory = $this->parameterBag->get('process_manager.log_directory');
        $keepLogs = $this->parameterBag->get('process_manager.keep_logs');
        $this->cleanupService->cleanupDbEntries($seconds);
        $this->cleanupService->cleanupLogFiles($logDirectory, $seconds, $keepLogs);
    }
}
