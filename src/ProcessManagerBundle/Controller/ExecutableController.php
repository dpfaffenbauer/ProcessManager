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

namespace ProcessManagerBundle\Controller;

use CoreShop\Bundle\ResourceBundle\Controller\ResourceController;
use ProcessManagerBundle\Message\ProcessMessage;
use ProcessManagerBundle\Model\ExecutableInterface;
use ProcessManagerBundle\Process\ProcessStartupFormResolverInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

class ExecutableController extends ResourceController
{
    public function getConfigAction(Request $request): JsonResponse
    {
        $types = $this->getConfigTypes();

        return $this->viewHandler->handle([
            'types' => array_keys($types)
        ]);
    }

    public function listByTypeAction(Request $request): JsonResponse
    {
        $type = $request->get('type');

        if ($type) {
            $result = $this->repository->findByType($type);

            return $this->viewHandler->handle(['data' => $result, 'success' => true], ['group' => 'List']);
        }

        return $this->viewHandler->handle(['success' => false, 'message' => 'No type given']);
    }

    public function runAction(Request $request): JsonResponse
    {
        $exe = $this->repository->find($request->get('id'));

        if (!$exe instanceof ExecutableInterface) {
            return $this->viewHandler->handle([
                'success' => false
            ]);
        }

        $params = [];
        $form = $this->get(ProcessStartupFormResolverInterface::class)->resolveFormType($exe);

        if ($form) {
            $form = $this->createForm($form);
            $startupConfig = $request->get('startupConfig', '{}');
            $startupConfig = json_decode($startupConfig, true);
            $handledForm = $form->submit($startupConfig);

            if (!$handledForm->isValid()) {
                $errors = $this->formErrorSerializer->serializeErrorFromHandledForm($handledForm);

                return $this->viewHandler->handle([
                    'success' => false,
                    'message' => 'Startup Parameters given are not valid'.PHP_EOL.PHP_EOL.implode(PHP_EOL, $errors),
                ]);
            }

            $params = $form->getData();
        }

        $this->get('messenger.default_bus')->dispatch(new ProcessMessage($exe->getId(), $params));

        return $this->viewHandler->handle([
            'success' => true
        ]);
    }

    protected function getConfigTypes(): array
    {
        return $this->container->getParameter('process_manager.processes');
    }
}
