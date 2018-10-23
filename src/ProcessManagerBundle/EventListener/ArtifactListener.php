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

        /** @var ProcessInterface $process */
        $process = $record['extra']['process'];

        $artifactPath = $record['context']['artifact'];

        // TODO: copy $artifactPath in place of this asset
        $artifact = new Asset();

        $process->setArtifact($artifact);
        $process->save();
    }
}
