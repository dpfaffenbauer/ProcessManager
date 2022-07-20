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

namespace ProcessManagerBundle\Message;

use CoreShop\Component\Registry\ServiceRegistry;
use ProcessManagerBundle\Model\ExecutableInterface;
use ProcessManagerBundle\Process\ProcessInterface;
use ProcessManagerBundle\Repository\ExecutableRepositoryInterface;

class ProcessMessageHandler
{
    public function __construct(
        protected ExecutableRepositoryInterface $repository,
        protected ServiceRegistry $processRegistry
    )
    {
    }

    public function __invoke(ProcessMessage $message)
    {
        $exec = $this->repository->find($message->getExecutableId());

        if (!$exec instanceof ExecutableInterface) {
            return;
        }

        /**
         * @var ProcessInterface $process
         */
        $process = $this->processRegistry->get($exec->getType());
        $process->run($exec, $message->getParams());
    }
}
