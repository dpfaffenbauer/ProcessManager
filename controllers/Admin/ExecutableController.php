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
 * Class ProcessManager_Admin_ExecutableController
 */
class ProcessManager_Admin_ExecutableController extends \Pimcore\Controller\Action\Admin
{
    public function init()
    {
        parent::init();
    }

    public function listAction()
    {
        $list = new \ProcessManager\Model\Executable\Listing();
        $list->load();

        $data = [];
        if (is_array($list->getData())) {
            foreach ($list->getData() as $exe) {
                if($exe instanceof \ProcessManager\Model\Executable) {
                    $data[] = [
                        'id' => $exe->getId(),
                        'name' => $exe->getName(),
                        'description' => $exe->getDescription(),
                        'active' => $exe->getActive(),
                        'settings' => $exe->getSettings(),
                        'type' => $exe->getType(),
                        'cron' => $exe->getCron()
                    ];
                }
            }
        }

        $this->_helper->json($data);
    }

    public function getTypesAction() {
        $this->_helper->json(array(
            'success' => true,
            'types' => \ProcessManager\Model\Executable::$availableTypes,
        ));
    }

    public function saveAction()
    {
        $id = $this->getParam('id', null);
        $data = $this->getParam('data');

        if(isset($id)) {
            $exe = \ProcessManager\Model\Executable::getById($id);
        }
        else {
            $exe = new \ProcessManager\Model\Executable();
        }

        if ($data) {
            $data = \Zend_Json::decode($this->getParam('data'));

            $exe->setValues($data);
            $exe->save();

            $this->_helper->json(array('success' => true, 'data' => $exe));
        } else {
            $this->_helper->json(array('success' => false));
        }
    }

    public function deleteAction() {
        $process = \ProcessManager\Model\Executable::getById($this->getParam("id"));

        if(!$process instanceof \ProcessManager\Model\Executable) {
            $this->_helper->json(["success" => false, "message" => "Executable not found"]);
        }

        $process->delete();

        $this->_helper->json(["success" => true]);
    }

    public function runAction() {
        $process = \ProcessManager\Model\Executable::getById($this->getParam("id"));

        if(!$process instanceof \ProcessManager\Model\Executable) {
            $this->_helper->json(["success" => false, "message" => "Executable not found"]);
        }

        $success = $process->run();

        if($success) {
            $this->_helper->json(["success" => true]);
        }
        else {
            $this->_helper->json(["success" => false, "message" => "Executable Type not found"]);
        }
    }
}
