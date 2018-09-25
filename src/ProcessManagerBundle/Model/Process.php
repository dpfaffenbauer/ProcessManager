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

namespace ProcessManagerBundle\Model;

use Pimcore\Logger;
use Pimcore\Model\AbstractModel;

class Process extends AbstractModel implements ProcessInterface
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $type = null;

    /**
     * @var string
     */
    public $message = '';

    /**
     * @var int
     */
    public $progress = 0;

    /**
     * @var int
     */
    public $total;

    /**
     * @param string      $name
     * @param string|null $type
     * @param string      $message
     * @param int         $total
     * @param int         $progress
     */
    public function __construct(string $name, string $type = null, string $message = '', int $total = 1, int $progress = 0)
    {
        $this->name = $name;
        $this->type = $type;
        $this->message = $message;
        $this->progress = $progress;
        $this->total = $total;
    }

    /**
     * get Log by id
     *
     * @param $id
     * @return null|Process
     */
    public static function getById($id)
    {
        try {
            $reflection = new \ReflectionClass(get_called_class());
            $obj = $reflection->newInstanceWithoutConstructor();
            $obj->getDao()->getById($id);
            return $obj;
        } catch (\Exception $ex) {

        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function delete()
    {
        $registry = \Pimcore::getContainer()->get('process_manager.registry.process_handler_factories');

        if ($this->getType() && $registry->has($this->getType())) {
            $registry->get($this->getType())->cleanup($this);
        }
        else {
            \Pimcore::getContainer()->get('process_manager.default_handler_factory')->cleanup($this);
        }

        parent::delete();
    }

    /**
     * Increase Process
     *
     * @param int $steps
     * @param string $message
     */
    public function progress($steps = 1, $message = '')
    {
        $this->setProgress($this->getProgress() + $steps);

        if($message) {
            $this->setMessage($message);
        }

        $this->save();
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
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return int
     */
    public function getProgress()
    {
        return $this->progress;
    }

    /**
     * @param int $progress
     */
    public function setProgress($progress)
    {
        $this->progress = $progress;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param int $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }

    /**
     * @return float
     */
    public function getPercentage() {
        if ($this->getTotal() == 0) {
            return 100;
        }

        return ((100 / $this->getTotal()) * $this->getProgress()) / 100;
    }
}
