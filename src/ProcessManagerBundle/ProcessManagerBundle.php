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

namespace ProcessManagerBundle;

use CoreShop\Bundle\ResourceBundle\AbstractResourceBundle;
use CoreShop\Bundle\ResourceBundle\ComposerPackageBundleInterface;
use CoreShop\Bundle\ResourceBundle\CoreShopResourceBundle;
use Pimcore\Extension\Bundle\PimcoreBundleInterface;
use Pimcore\Extension\Bundle\Traits\PackageVersionTrait;
use ProcessManagerBundle\DependencyInjection\Compiler\MonologHandlerPass;
use ProcessManagerBundle\DependencyInjection\Compiler\ProcessHandlerFactoryTypeRegistryCompilerPass;
use ProcessManagerBundle\DependencyInjection\Compiler\ProcessReportTypeRegistryCompilerPass;
use ProcessManagerBundle\DependencyInjection\Compiler\ProcessTypeRegistryCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ProcessManagerBundle extends AbstractResourceBundle implements PimcoreBundleInterface, ComposerPackageBundleInterface
{
    use PackageVersionTrait;

    /**
     * {@inheritdoc}
     */
    public function getPackageName()
    {
        return 'dpfaffenbauer/process-manager';
    }

    /**
     * {@inheritdoc}
     */
    public function getSupportedDrivers()
    {
        return [
            CoreShopResourceBundle::DRIVER_PIMCORE,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $builder)
    {
        parent::build($builder);

        $builder->addCompilerPass(new ProcessTypeRegistryCompilerPass());
        $builder->addCompilerPass(new ProcessReportTypeRegistryCompilerPass());
        $builder->addCompilerPass(new ProcessHandlerFactoryTypeRegistryCompilerPass());
        $builder->addCompilerPass(new MonologHandlerPass());
    }

    /**
     * {@inheritdoc}
     */
    public function getNiceName()
    {
        return 'Process Manager';
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return 'Process Manager helps you to see statuses for long running Processes';
    }

        /**
     * {@inheritdoc}
     */
    protected function getComposerPackageName(): string
    {
        return 'dpfaffenbauer/process-manager';
    }

    /**
     * {@inheritdoc}
     */
    public function getInstaller()
    {
        return $this->container->get(Installer::class);
    }

        /**
     * {@inheritdoc}
     */
    public function getAdminIframePath()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getJsPaths()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getCssPaths()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getEditmodeJsPaths()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getEditmodeCssPaths()
    {
        return [];
    }
}
