<?php

namespace ProcessManagerBundle\DependencyInjection\Compiler;

use CoreShop\Bundle\PimcoreBundle\DependencyInjection\Compiler\RegisterSimpleRegistryTypePass;

final class ProcessHandlerFactoryTypeRegistryCompilerPass extends RegisterSimpleRegistryTypePass
{
    public function __construct()
    {
        parent::__construct(
            'process_manager.registry.process_handler_factories',
            'process_manager.process_handler_factories',
            'process_manager.process_handler_factory'
        );
    }
}
