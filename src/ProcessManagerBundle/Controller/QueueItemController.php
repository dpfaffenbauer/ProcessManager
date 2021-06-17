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
 * @copyright  Copyright (c) 2015-2020 Dominik Pfaffenbauer (https://www.pfaffenbauer.at)
 * @license    https://github.com/dpfaffenbauer/ProcessManager/blob/master/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

namespace ProcessManagerBundle\Controller;

use CoreShop\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use ProcessManagerBundle\Model\QueueItem;

class QueueItemController extends ResourceController
{
    public function listAction(Request $request): JsonResponse
    {
        $class = $this->repository->getClassName();
        $listingClass = $class.'\Listing';

        /**
         * @var QueueItem\Listing $list
         */
        $list = new $listingClass();
        if ($sort = $request->get('sort')) {
            $sort = json_decode($sort)[0];
            $list->setOrderKey($sort->property);
            $list->setOrder($sort->direction);
        } else {
            $list->setOrderKey("id");
            $list->setOrder("DESC");
        }

        $data = $list->getItems(
            $request->get('start', 0),
            $request->get('limit', 50)
        );

        return $this->viewHandler->handle(
            [
                'data' => $data,
                'total' => $list->getTotalCount(),
            ],
            ['group' => 'List']
        );
    }
}
