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

namespace QueueItemManagerBundle\Factory;

use CoreShop\Component\Resource\Factory\FactoryInterface;

interface QueueItemFactoryInterface extends FactoryInterface
{
    /**
     * @param string $type
     * @param string $name
     * @param string $status
     * @param string $description
     * @param array $settings
     * @param string $queue
     * @param integer|null $created
     * @param integer|null $started
     * @param integer|null $completed
     * @return mixed
     */
    public function createQueueItem(string $type, string $name, string $status, string $description, array $settings, string $queue, ?int $created = null, ?int $started = null, ?int $completed = null);
}
