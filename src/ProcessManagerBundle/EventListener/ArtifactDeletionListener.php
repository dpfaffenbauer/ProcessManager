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

namespace ProcessManagerBundle\EventListener;

use Pimcore\Event\Model\AssetEvent;
use ProcessManagerBundle\Repository\ProcessRepositoryInterface;

class ArtifactDeletionListener
{
    private ProcessRepositoryInterface $repository;

    public function __construct(ProcessRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function onArtifactAssetDelete(AssetEvent $event)
    {
        $asset = $event->getAsset();

        $processes = $this->repository->findByArtifact($asset);

        foreach ($processes as $process) {
            $process->setArtifact(null);
            $process->save();
        }
    }
}
