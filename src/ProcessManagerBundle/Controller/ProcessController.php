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
 * @copyright  Copyright (c) 2015-2020 Wojciech Peisert (http://divante.co/)
 * @license    https://github.com/dpfaffenbauer/ProcessManager/blob/master/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

namespace ProcessManagerBundle\Controller;

use CoreShop\Bundle\ResourceBundle\Controller\ResourceController;
use Pimcore\Db;
use ProcessManagerBundle\Model\Process;
use ProcessManagerBundle\Model\ProcessInterface;
use ProcessManagerBundle\Service\CleanupService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ProcessController extends ResourceController
{
    public function listAction(Request $request): JsonResponse
    {
        $class = $this->repository->getClassName();
        $listingClass = $class.'\Listing';

        /**
         * @var Process\Listing $list
         */
        $list = new $listingClass();
        if ($filterString = $request->get('filter')) {
            $db = Db::get();
            $filters = json_decode($filterString);
            $conditionParts = [];
            foreach ($filters as $f) {
                $fieldname = $f->property;
                $conditionParts[] = $db->quoteIdentifier($fieldname) . ' LIKE ' . $db->quote('%' . $f->value . '%');
            }
            $condition = implode(' AND ', $conditionParts);
            $list->setCondition($condition);
        }
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

    public function logDownloadAction(Request $request): Response
    {
        $process = $this->findOr404($request->get('id'));

        $response = new Response($this->getLog($process));
        $response->headers->set('Content-Type', 'text/plain');
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'process_manager_'.$process->getId().'.log'
        );
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }

    public function logReportAction(Request $request): JsonResponse
    {
        $process = $this->findOr404($request->get('id'));
        $registry = $this->get('process_manager.registry.process_reports');
        $log = $this->getLog($process);

        if ($registry->has($process->getType())) {
            $content = $registry->get($process->getType())->generateReport($process, $log);
        } else {
            $content = $this->get('process_manager.default_report')->generateReport($process, $log);
        }

        return $this->json(
            [
                'success' => true,
                'report' => $content,
            ]
        );
    }

    public function stopAction(Request $request): JsonResponse
    {
        /** @var Process $process */
        $process = $this->findOr404($request->get('id'));
        $process->setStatus('stopping');
        $process->save();

        return $this->json(
            [
                'success' => true,
            ]
        );
    }

    public function clearAction(Request $request): JsonResponse
    {
        $seconds = (int)$request->get('seconds', 604_800);
        $logDirectory = $this->parameterBag->get('process_manager.log_directory');
        $keepLogs = $this->parameterBag->get('process_manager.keep_logs');

        /** @var CleanupService $cleanupService */
        $cleanupService = $this->container->get(CleanupService::class);
        $cleanupService->cleanupDbEntries($seconds);
        $cleanupService->cleanupLogFiles($logDirectory, $seconds, $keepLogs);
        return $this->json(['success' => true]);
    }

    protected function getLog(ProcessInterface $process): string
    {
        $registry = $this->get('process_manager.registry.process_handler_factories');
        $handler = $registry->has($process->getType()) ? $registry->get($process->getType()) : $this->get(
            'process_manager.default_handler_factory'
        );

        return $handler->getLog($process);
    }
}
