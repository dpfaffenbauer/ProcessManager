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
 * @copyright  Copyright (c) 2016 lineofcode.at (http://www.lineofcode.at)
 * @license    https://github.com/dpfaffenbauer/ProcessManager/blob/master/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

/**
 * Class ProcessManager_Admin_ProcessController
 */
class ProcessManager_Admin_ProcessController extends \Pimcore\Controller\Action\Admin
{
    public function init()
    {
        parent::init();
    }

    public function listAction()
    {
        $list = new \ProcessManager\Model\Process\Listing();

        $data = [];
        if (is_array($list->getData())) {
            foreach ($list->getData() as $process) {
                $data[] = [
                    'id' => $process->getId(),
                    'name' => $process->getName(),
                    'message' => $process->getMessage(),
                    'percentage' => $process->getPercentage(),
                    'total' => $process->getTotal(),
                    'progress' => $process->getProgress()
                ];
            }
        }
        $this->_helper->json($data);
    }

    public function deleteAction() {
        $process = \ProcessManager\Model\Process::getById($this->getParam("id"));

        if(!$process instanceof \ProcessManager\Model\Process) {
            $this->_helper->json(["success" => false, "message" => "Process not found"]);
        }

        $process->delete();

        $this->_helper->json(["success" => true]);
    }

    public function readLogAction() {
        $process = \ProcessManager\Model\Process::getById($this->getParam("id"));

        if(!$process instanceof \ProcessManager\Model\Process) {
            $this->_helper->json(["success" => false, "message" => "Process not found"]);
        }

        $this->_helper->json(["success" => true, "log" => file_exists($process->getLogFilePath()) ? file_get_contents($process->getLogFilePath()) : ""]);
    }
}
