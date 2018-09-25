<?php

namespace ProcessManagerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class MonologHandlerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('monolog.logger')) {
            return;
        }

        $logger = $container->getDefinition('monolog.logger');
        $logger->addMethodCall(
            'pushHandler',
            [
                new Reference('process_manager.monolog.handler'),
            ]
        );
    }
}
