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

namespace ProcessManagerBundle\Factory;

use CoreShop\Component\Resource\Factory\FactoryInterface;

interface ProcessFactoryInterface extends FactoryInterface
{
    /**
     * @param string      $name
     * @param string|null $type
     * @param string      $message
     * @param int         $total
     * @param int         $progress
     * @return mixed
     */
    public function createProcess(string $name, string $type = null, string $message = '', int $total = 1, int $progress = 0);
}