<?php

namespace ProcessManagerBundle\DependencyInjection\Compiler;

use CoreShop\Bundle\PimcoreBundle\DependencyInjection\Compiler\RegisterSimpleRegistryTypePass;

final class ProcessReportTypeRegistryCompilerPass extends RegisterSimpleRegistryTypePass
{
    public function __construct()
    {
        parent::__construct(
            'process_manager.registry.process_reports',
            'process_manager.process_reports',
            'process_manager.process_report'
        );
    }
}
