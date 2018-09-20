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

use Pimcore\Bundle\AdminBundle\Controller\AdminController;
use ProcessManagerBundle\Exception\NonExistentReportFileException;
use ProcessManagerBundle\Model\ExecutableInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use ProcessManagerBundle\Service\ProcessReport;

class ProcessReportController extends AdminController
{
    /**
     * @var ProcessReport
     */
    private $report;

    public function __construct(ProcessReport $report)
    {
        $this->report = $report;
    }

    /**
     * @Route("/admin/process_manager/reports/log-download/{id}")
     * @return BinaryFileResponse
     * @return JsonResponse
     * @throws NonExistentReportFileException
     */
    public function logDownloadAction($id)
    {
        $filePath = $this->report->getReportLogFile($id);
        if (!file_exists($filePath)) {
            throw new NonExistentReportFileException($id);
        }

        $response = new BinaryFileResponse($filePath);
        $response->headers->set('Content-Type', 'text/plain');
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);

        return $response;
    }

    /**
     * @Route("/admin/process_manager/reports/get")
     * @param Request $request
     * @return JsonResponse
     */
    public function getReportAction(Request $request)
    {
        $success = true;
        $html    = '';
        $message = '';

        $id = $request->get('id');

        try {
            $report = $this->report->prepareReport($id);
            $html = $this->report->getReportHtml();
        } catch (NonExistentReportFileException $e) {
            $success = false;
            $message = "No report data";
        } catch (\Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }

        return $this->json(
            [
                'success' => $success,
                'message' => $message,
                'report'  =>
                    [
                        'title' => 'Report for process: ' . $id,
                        'html'  => $html,
                    ]
            ]
        );
    }
}
