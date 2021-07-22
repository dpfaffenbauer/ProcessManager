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

namespace ProcessManagerBundle\Process;

use CoreShop\Component\Registry\ServiceRegistryInterface;
use ProcessManagerBundle\Model\ExecutableInterface;

final class CompositeProcessStartupFormResolver implements ProcessStartupFormResolverInterface
{
    private ServiceRegistryInterface $registry;

    public function __construct(ServiceRegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    public function supports(ExecutableInterface $executable): bool
    {
        return true;
    }

    public function resolveFormType(ExecutableInterface $executable): ?string
    {
        foreach ($this->registry->all() as $resolver) {
            if ($resolver->supports($executable)) {
                return $resolver->resolveFormType($executable);
            }
        }

        return null;
    }
}
