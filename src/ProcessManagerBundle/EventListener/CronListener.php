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
 * @copyright  Copyright (c) 2018 Jakub Płaskonka (jplaskonka@divante.pl)
 * @license    https://github.com/dpfaffenbauer/ProcessManager/blob/master/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

namespace ProcessManagerBundle\EventListener;

use CoreShop\Component\Registry\ServiceRegistry;
use Cron\CronExpression;
use ProcessManagerBundle\Model\Executable;

class CronListener
{
    private $registry;

    /**
     * CronListener constructor.
     * @param ServiceRegistry $registry
     */
    public function __construct(ServiceRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Runs waiting crons
     */
    public function run()
    {
        /** @var Executable $executable */
        foreach ($this->getExecutables() as $executable) {
            $cron = CronExpression::factory($executable->getCron());
            $lastrun =  new \DateTime();
            $lastrun->setTimestamp($executable->getLastrun());

            if($cron->getPreviousRunDate() > $lastrun) {
                $executable->setLastrun($cron->getPreviousRunDate()->getTimestamp());
                $executable->save();
                $this->registry->get($executable->getType())->run($executable);
            }
        }
    }

    /**
     * @return Executable[]
     */
    protected function getExecutables()
    {
        $executables = new Executable\Listing();
        $executables->setCondition('active = 1 && cron != ""');
        return $executables->getObjects();
    }
}