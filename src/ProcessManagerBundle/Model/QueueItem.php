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

namespace ProcessManagerBundle\Model;

use Pimcore\Logger;
use Pimcore\Model\AbstractModel;

class QueueItem extends AbstractModel implements QueueItemInterface
{
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
     * @var array
     */
    public $settings;

    /**
     * @var string
     */
    public $queue;

    /**
     * @var string
     */
    public $status;

    /**
     * @var int
     */
    public $created;

    /**
     * @var int
     */
    public $started;

    /**
     * @var int
     */
    public $completed;


    /**
     * @param string $type
     * @param string $name
     * @param array $settings
     * @param string $description
     * @param string $queue
     * @param string $status
     * @param integer|null $created
     * @param integer|null $started
     * @param integer|null $completed
     */
    public function __construct(
        string $type,
        string $name,
        array $settings,
        string $description,
        string $queue,
        string $status,
        ?int $created = null,
        ?int $started = null,
        ?int $completed = null
    )
    {
        $this->type = $type;
        $this->name = $name;
        $this->status = $status;
        $this->description = $description;
        $this->settings = $settings;
        $this->queue = $queue;
        $this->created = $created;
        $this->started = $started;        
        $this->completed = $completed;
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
            Logger::warn(sprintf("Process with id %s not found", $id));
        }

        return null;
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
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @param array $settings
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;
    }

    /**
     * @return string
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * @param string $queue
     */
    public function setQueue($queue)
    {
        $this->queue = $queue;
    }

    /**
     * @return int
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param int $started
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return int
     */
    public function getStarted()
    {
        return $this->started;
    }

    /**
     * @param int $started
     */
    public function setStarted($started)
    {
        $this->started = $started;
    }

    /**
     * @return int
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * @param int $completed
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

}
