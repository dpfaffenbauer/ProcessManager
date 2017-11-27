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

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;
use Pimcore\Extension\Bundle\Traits\PackageVersionTrait;
use ProcessManagerBundle\DependencyInjection\Compiler\ProcessTypeRegistryCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ProcessManagerBundle extends AbstractPimcoreBundle
{
    use PackageVersionTrait;

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $builder)
    {
        parent::build($builder);

        $builder->addCompilerPass(new ProcessTypeRegistryCompilerPass());
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
}
