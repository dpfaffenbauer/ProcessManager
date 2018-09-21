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
 * @copyright  Copyright (c) 2015-2017 Dominik Pfaffenbauer (https://www.pfaffenbauer.at)
 * @license    https://github.com/dpfaffenbauer/ProcessManager/blob/master/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

namespace ProcessManagerBundle\DependencyInjection;

use CoreShop\Bundle\ResourceBundle\Controller\ResourceController;
use CoreShop\Bundle\ResourceBundle\CoreShopResourceBundle;
use CoreShop\Component\Resource\Factory\Factory;
use ProcessManagerBundle\Controller\ExecutableController;
use ProcessManagerBundle\Controller\ProcessController;
use ProcessManagerBundle\Factory\ProcessFactory;
use ProcessManagerBundle\Form\Type\ExecutableType;
use ProcessManagerBundle\Model\Executable;
use ProcessManagerBundle\Model\ExecutableInterface;
use ProcessManagerBundle\Model\Process;
use ProcessManagerBundle\Model\ProcessInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('process_manager');

        $rootNode
            ->children()
                ->scalarNode('driver')->defaultValue(CoreShopResourceBundle::DRIVER_PIMCORE)->end()
            ->end()
        ;

        $this->addPimcoreResourcesSection($rootNode);
        $this->addModelsSection($rootNode);

        return $treeBuilder;
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addModelsSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('executable')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(Executable::class)->cannotBeEmpty()->end()
                                        ->scalarNode('interface')->defaultValue(ExecutableInterface::class)->cannotBeEmpty()->end()
                                        ->scalarNode('admin_controller')->defaultValue(ExecutableController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->cannotBeEmpty()->end()
                                        ->scalarNode('form')->defaultValue(ExecutableType::class)->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('process')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(Process::class)->cannotBeEmpty()->end()
                                        ->scalarNode('interface')->defaultValue(ProcessInterface::class)->cannotBeEmpty()->end()
                                        ->scalarNode('admin_controller')->defaultValue(ProcessController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(ProcessFactory::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addPimcoreResourcesSection(ArrayNodeDefinition $node)
    {
        $node->children()
            ->arrayNode('pimcore_admin')
                ->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode('js')
                        ->addDefaultsIfNotSet()
                        ->ignoreExtraKeys(false)
                        ->children()
                            ->scalarNode('startup')->defaultValue('/bundles/processmanager/pimcore/js/startup.js')->end()
                            ->scalarNode('panel')->defaultValue('/bundles/processmanager/pimcore/js/panel.js')->end()
                            ->scalarNode('processes')->defaultValue('/bundles/processmanager/pimcore/js/processes.js')->end()
                            ->scalarNode('portlet')->defaultValue('/bundles/processmanager/pimcore/js/portlet.js')->end()
                            ->scalarNode('executables')->defaultValue('/bundles/processmanager/pimcore/js/executables.js')->end()
                            ->scalarNode('executable_item')->defaultValue('/bundles/processmanager/pimcore/js/executable/item.js')->end()
                            ->scalarNode('executable_abstractType')->defaultValue('/bundles/processmanager/pimcore/js/executable/abstractType.js')->end()
                            ->scalarNode('executable_type_cli')->defaultValue('/bundles/processmanager/pimcore/js/executable/types/cli.js')->end()
                            ->scalarNode('executable_type_pimcore')->defaultValue('/bundles/processmanager/pimcore/js/executable/types/pimcore.js')->end()
                        ->end()
                    ->end()
                    ->arrayNode('css')
                        ->addDefaultsIfNotSet()
                        ->ignoreExtraKeys(false)
                        ->children()
                            ->scalarNode('process_manager')->defaultValue('/bundles/processmanager/pimcore/css/processmanager.css')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();
    }
}
