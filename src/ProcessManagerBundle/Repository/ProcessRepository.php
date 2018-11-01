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

use CoreShop\Bundle\ResourceBundle\Pimcore\PimcoreRepository;
use Pimcore\Model\Asset;

class ProcessRepository extends PimcoreRepository implements ProcessRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function findByArtifact(Asset $artifact)
    {
        $list = $this->getList();
        $list->setCondition('artifact = ?', [$artifact->getId()]);

        return $list->load();
    }
}