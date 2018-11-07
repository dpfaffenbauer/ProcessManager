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

namespace ProcessManagerBundle\Repository;

use CoreShop\Component\Resource\Repository\RepositoryInterface;
use Pimcore\Model\Asset;
use ProcessManagerBundle\Model\ExecutableInterface;
use ProcessManagerBundle\Model\ProcessInterface;

interface ExecutableRepositoryInterface extends RepositoryInterface
{
    /**
     * @param string $type
     * @return ExecutableInterface[]
     */
    public function findByType(string $type);
}