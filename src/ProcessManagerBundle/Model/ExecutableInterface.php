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

use CoreShop\Component\Resource\Model\ResourceInterface;

interface ExecutableInterface extends ResourceInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getType();

    /**
     * @param string $type
     */
    public function setType($type);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @param string $description
     */
    public function setDescription($description);

    /**
     * @return int
     */
    public function getActive();

    /**
     * @param int $active
     */
    public function setActive($active);

    /**
     * @return array
     */
    public function getSettings();

    /**
     * @param array $settings
     */
    public function setSettings($settings);

    /**
     * @return string
     */
    public function getCron();

    /**
     * @param string $cron
     */
    public function setCron($cron);

    /**
     * @return int
     */
    public function getLastrun();

    /**
     * @param int $lastrun
     */
    public function setLastrun($lastrun);
}