<?php
/**
 * QueueItem Manager.
 *
 * LICENSE
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015-2020 Dominik Pfaffenbauer (https://www.pfaffenbauer.at)
 * @license    https://github.com/dpfaffenbauer/QueueItemManager/blob/master/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

namespace ProcessManagerBundle\Factory;

use ProcessManagerBundle\Model\QueueItemInterface;
use ProcessManagerBundle\ProcessManagerBundle;

class QueueItemFactory implements QueueItemFactoryInterface
{
    private $model;

    public function __construct(string $model)
    {
        $this->model = $model;
    }

    public function createNew()
    {
        throw new \InvalidArgumentException('use createQueueItem instead');
    }

    public function createQueueItem(
        string $type,
        string $name,
        array $settings,
        string $description,
        string $queue,
        string $status = ProcessManagerBundle::STATUS_QUEUED,
        ?int $created = null,
        ?int $started = null,
        ?int $completed = null
    ): QueueItemInterface
    {
        if ($created === null) {
            $created = time();
        }

        return new $this->model($type, $name, $settings, $description, $queue, $status, $created, $started, $completed);
    }
}
