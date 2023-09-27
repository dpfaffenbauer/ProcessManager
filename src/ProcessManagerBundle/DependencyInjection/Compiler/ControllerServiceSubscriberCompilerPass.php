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

namespace ProcessManagerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ControllerServiceSubscriberCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $definition = $container->getDefinition('process_manager.admin_controller.process');
        $definition->addTag('container.service_subscriber', [
            'key' => 'process_manager.registry.process_reports',
            'id' => 'process_manager.registry.process_reports',
        ]);
        $definition->addTag('container.service_subscriber', [
            'key' => 'process_manager.registry.process_handler_factories',
            'id' => 'process_manager.registry.process_handler_factories',
        ]);
        $definition->addTag('container.service_subscriber', [
            'key' => 'process_manager.default_handler_factory',
            'id' => 'process_manager.default_handler_factory',
        ]);
        $definition->addTag('container.service_subscriber', [
            'key' => 'process_manager.default_report',
            'id' => 'process_manager.default_report',
        ]);
    }
}
