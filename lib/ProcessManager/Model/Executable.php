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

namespace ProcessManager\Model;

use Pimcore\Logger;
use Pimcore\Model\AbstractModel;
use Pimcore\Tool;

/**
 * Class Executable
 * @package ProcessManager\Process
 */
class Executable extends AbstractModel
{
    /**
     * available Types.
     *
     * @var array
     */
    public static $availableTypes = array('pimcore', 'cli');

    /**
     * Add Type.
     *
     * @param $type
     */
    public static function addType($type)
    {
        if (!in_array($type, self::$availableTypes)) {
            self::$availableTypes[] = $type;
        }
    }

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $settings;

    /**
     * @var int
     */
    public $active;

    /**
     * @var string
     */
    public $cron;

    /**
     * get Log by id
     *
     * @param $id
     * @return null|Process
     */
    public static function getById($id)
    {
        try {
            $obj = new self;
            $obj->getDao()->getById($id);
            return $obj;
        } catch (\Exception $ex) {
            Logger::warn(sprintf("Process with id %s not found", $id));
        }

        return null;
    }

    /**
     * Runs the Command
     *
     * @return bool
     */
    public function run() {
        $typeHelper = $this->getTypeHelper();

        if($typeHelper instanceof Type) {
            $typeHelper->run($this);

            return true;
        }

        return false;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return Type|null
     */
    public function getTypeHelper() {
        $className = '\ProcessManager\Model\Type\\' . ucfirst($this->getType());

        if(Tool::classExists($className)) {
            return new $className();
        }

        return null;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param int $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return string
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @param string $settings
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;
    }

    /**
     * @return string
     */
    public function getCron()
    {
        return $this->cron;
    }

    /**
     * @param string $cron
     */
    public function setCron($cron)
    {
        $this->cron = $cron;
    }
}
