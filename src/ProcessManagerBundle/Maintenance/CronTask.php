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

namespace ProcessManagerBundle\Maintenance;

use Pimcore\Maintenance\TaskInterface;
use CoreShop\Component\Registry\ServiceRegistry;
use Cron\CronExpression;
use ProcessManagerBundle\Message\ProcessMessage;
use ProcessManagerBundle\Model\Executable;
use Symfony\Component\Messenger\MessageBusInterface;

class CronTask implements TaskInterface
{
    private ServiceRegistry $registry;
    private MessageBusInterface $messageBus;

    public function __construct(ServiceRegistry $registry, MessageBusInterface $messageBus)
    {
        $this->registry = $registry;
        $this->messageBus = $messageBus;
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

                $this->messageBus->dispatch(new ProcessMessage($exe->getId()));
            }
        }
    }

    /**
     * @return Executable[]
     */
    protected function getExecutables(): array
    {
        $executables = new Executable\Listing();
        $executables->setCondition('active = 1 && cron != ""');
        return $executables->getObjects();
    }
}
