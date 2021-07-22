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

namespace ProcessManagerBundle;

use CoreShop\Bundle\ResourceBundle\AbstractResourceBundle;
use CoreShop\Bundle\ResourceBundle\ComposerPackageBundleInterface;
use CoreShop\Bundle\ResourceBundle\CoreShopResourceBundle;
use Pimcore\Extension\Bundle\PimcoreBundleInterface;
use Pimcore\Extension\Bundle\Traits\PackageVersionTrait;
use ProcessManagerBundle\DependencyInjection\Compiler\MonologHandlerPass;
use ProcessManagerBundle\DependencyInjection\Compiler\ProcessHandlerFactoryTypeRegistryCompilerPass;
use ProcessManagerBundle\DependencyInjection\Compiler\ProcessReportTypeRegistryCompilerPass;
use ProcessManagerBundle\DependencyInjection\Compiler\ProcessStartupFormRegistryCompilerPass;
use ProcessManagerBundle\DependencyInjection\Compiler\ProcessTypeRegistryCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ProcessManagerBundle extends AbstractResourceBundle implements PimcoreBundleInterface, ComposerPackageBundleInterface
{
    use PackageVersionTrait;

    public const STATUS_RUNNING = 'running';
    public const STATUS_STOPPED = 'stopped';
    public const STATUS_STOPPING = 'stopping';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_COMPLETED_WITH_EXCEPTIONS = 'completed_with_exceptions';
    public const STATUS_FAILED = 'failed';

    public function getPackageName(): string
    {
        return 'dpfaffenbauer/process-manager';
    }

    public function getSupportedDrivers(): array
    {
        return [
            CoreShopResourceBundle::DRIVER_PIMCORE,
        ];
    }

    public function build(ContainerBuilder $builder): void
    {
        parent::build($builder);

        $builder->addCompilerPass(new ProcessTypeRegistryCompilerPass());
        $builder->addCompilerPass(new ProcessReportTypeRegistryCompilerPass());
        $builder->addCompilerPass(new ProcessHandlerFactoryTypeRegistryCompilerPass());
        $builder->addCompilerPass(new MonologHandlerPass());
        $builder->addCompilerPass(new ProcessStartupFormRegistryCompilerPass());
    }

    public function getNiceName(): string
    {
        return 'Process Manager';
    }

    public function getDescription(): string
    {
        return 'Process Manager helps you to see statuses for long running Processes';
    }

    protected function getComposerPackageName(): string
    {
        return 'dpfaffenbauer/process-manager';
    }

    public function getInstaller(): Installer
    {
        return $this->container->get(Installer::class);
    }

    public function getAdminIframePath(): ?string
    {
        return null;
    }

    public function getJsPaths(): array
    {
        return [];
    }

    public function getCssPaths(): array
    {
        return [];
    }

    public function getEditmodeJsPaths(): array
    {
        return [];
    }

    public function getEditmodeCssPaths(): array
    {
        return [];
    }
}
