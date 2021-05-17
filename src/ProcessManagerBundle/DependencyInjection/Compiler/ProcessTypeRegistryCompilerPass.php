<?php

namespace ProcessManagerBundle\DependencyInjection\Compiler;

use CoreShop\Component\Registry\RegisterRegistryTypePass;

final class ProcessTypeRegistryCompilerPass extends RegisterRegistryTypePass
{
    public function __construct()
    {
        parent::__construct(
            'process_manager.registry.processes',
            'process_manager.form.registry.processes',
            'process_manager.processes',
            'process_manager.process'
        );
    }
}
