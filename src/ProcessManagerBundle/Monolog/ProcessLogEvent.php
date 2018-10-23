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

namespace ProcessManagerBundle\Monolog;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class ProcessLogEvent.
 */
class ProcessLogEvent extends Event
{
    const PROCESS_LOG_EVENT = 'process_manager.process.log_event';

    /**
     * @var array
     */
    private $record;

    public function __construct(array $record)
    {
        $this->record = $record;
    }

    /**
     * @return array
     */
    public function getRecord(): array
    {
        return $this->record;
    }

    public function setRecord(array $record): void
    {
        $this->record = $record;
    }
}
