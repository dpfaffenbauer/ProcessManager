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
 * @copyright  Copyright (c) 2015-2017 Wojciech Peisert (http://divante.co/)
 * @license    https://github.com/dpfaffenbauer/ProcessManager/blob/master/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

namespace ProcessManagerBundle\Controller;

use CoreShop\Bundle\ResourceBundle\Controller\ResourceController;
use ProcessManagerBundle\Model\ProcessInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ProcessController extends ResourceController
{
    /**
     * @param Request $request
     * @return Response
     * @return JsonResponse
     */
    public function logDownloadAction(Request $request)
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

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logReportAction(Request $request)
    {
        $process = $this->findOr404($request->get('id'));
        $registry = $this->get('process_manager.registry.process_reports');
        $log = $this->getLog($process);

        if ($registry->has($process->getType())) {
            $content = $registry->get($process->getType())->generateReport($process, $log);
        }
        else {
            $content = $this->get('process_manager.default_report')->generateReport($process, $log);
        }

        return $this->json(
            [
                'success' => true,
                'report' => $content
            ]
        );
    }

    protected function getLog(ProcessInterface $process)
    {
        $registry = $this->get('process_manager.registry.process_handler_factories');
        $handler = $registry->has($process->getType()) ? $registry->get($process->getType()) : $this->get(
            'process_manager.default_handler_factory'
        );

        return $handler->getLog($process);
    }
}
