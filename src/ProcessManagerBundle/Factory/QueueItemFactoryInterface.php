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

use CoreShop\Component\Resource\Factory\FactoryInterface;
use ProcessManagerBundle\Model\QueueItemInterface;

interface QueueItemFactoryInterface extends FactoryInterface
{
    /**
     * @param string $type
     * @param string $name
     * @param array $settings
     * @param string $description
     * @param string $queue
     * @param string $status
     * @param integer|null $created
     * @param integer|null $started
     * @param integer|null $completed
     * @return QueueItemInterface
     */
    public function createQueueItem(string $type, string $name, array $settings, string $description, string $queue, string $status=QueueItemInterface::STATUS_QUEUED, ?int $created = null, ?int $started = null, ?int $completed = null);
}
