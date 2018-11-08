<?php

namespace ProcessManagerBundle\DependencyInjection\Compiler;

use CoreShop\Bundle\PimcoreBundle\DependencyInjection\Compiler\RegisterSimpleRegistryTypePass;

final class ProcessStartupFormRegistryCompilerPass extends RegisterSimpleRegistryTypePass
{
    public function __construct()
    {
        parent::__construct(
            'process_manager.registry.startup_form_resolver',
            'process_manager.startup_form_resolvers',
            'process_manager.startup_form_resolver'
        );
    }
}
