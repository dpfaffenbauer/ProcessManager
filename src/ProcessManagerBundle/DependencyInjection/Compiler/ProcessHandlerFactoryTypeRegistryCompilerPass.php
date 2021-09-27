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

use CoreShop\Component\Registry\RegisterSimpleRegistryTypePass;

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
