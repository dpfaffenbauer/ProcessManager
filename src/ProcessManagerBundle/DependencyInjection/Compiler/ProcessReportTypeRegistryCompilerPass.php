<?php

namespace ProcessManagerBundle\DependencyInjection\Compiler;

use CoreShop\Bundle\PimcoreBundle\DependencyInjection\Compiler\RegisterRegistryTypePass;

final class ProcessReportTypeRegistryCompilerPass extends RegisterRegistryTypePass
{
    public function __construct()
    {
        parent::__construct(
            'process_manager.registry.process_report',
            'process_manager.form.registry.process_reports',
            'process_manager.process_reports',
            'process_manager.process_report'
        );
    }
}
