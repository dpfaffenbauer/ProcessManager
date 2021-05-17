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

namespace ProcessManagerBundle\Maintenance;

use Pimcore\Maintenance\TaskInterface;
use CoreShop\Component\Registry\ServiceRegistry;
use Cron\CronExpression;
use ProcessManagerBundle\Model\Executable;

class CronTask implements TaskInterface
{
    private $registry;

    public function __construct(ServiceRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function execute()
    {
        /** @var Executable $executable */
        foreach ($this->getExecutables() as $executable) {
            try {
                $cron = CronExpression::factory($executable->getCron());
            } catch (\Exception $exception) {
                continue;
            }
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
