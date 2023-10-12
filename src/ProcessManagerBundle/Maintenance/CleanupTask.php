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

class CleanupTask implements TaskInterface
{
    public function __construct(private CleanupService $cleanupService, private string $logDirectory, private int $seconds, private bool $keepLogs) {
    }
    public function execute(): void
    {
        $this->cleanupService->cleanupDbEntries($this->seconds);
        $this->cleanupService->cleanupLogFiles($this->logDirectory, $this->seconds, $this->keepLogs);
    }
}
