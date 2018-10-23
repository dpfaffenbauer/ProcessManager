<?php

declare(strict_types=1);

namespace ProcessManagerBundle\EventListener;

use Pimcore\Model\Asset;
use ProcessManagerBundle\Monolog\ProcessLogEvent;

/**
 * Class ArtifactListener.
 */
class ArtifactListener
{
    /**
     * @param ImportDefinitionEvent $event
     */
    public function onProcessLogEvent(ProcessLogEvent $event)
    {
        // receives it via the logger from self::onArtifactEvent()
        $record = $event->getRecord();

        if (false === array_key_exists('artifact', $record['context'])) {
            return;
        }

        // TODO: need asset path in Executable config, hardcoded for now
        $artifactAssetPath = '/exports';

        /** @var ProcessInterface $process */
        $process = $record['extra']['process'];

        $artifactPath = $record['context']['artifact'];
        $artifact = new Asset();

        // TODO: how to do this better?
        // this loads the entire file in memory instead of just moving the file using the filesystem
        $artifact->setData(file_get_contents($artifactPath));
        $artifact->setFilename(pathinfo($artifactPath, PATHINFO_FILENAME));
        $artifact->setParent(Asset\Service::createFolderByPath($artifactAssetPath));
        $artifact->addMetadata('process_manager.process', 'number', $process->getId());
        $artifact->save();

        $process->setArtifact($artifact);
        $process->save();
    }
}
