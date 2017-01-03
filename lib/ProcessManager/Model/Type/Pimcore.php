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

namespace ProcessManager\Model\Type;

use Pimcore\Tool\Console;
use ProcessManager\Model\Executable;
use ProcessManager\Model\Type;

/**
 * Class Pimcore
 * @package ProcessManager\Process\Type
 */
class Pimcore extends Type
{
    /**
     * runs the executable
     *
     * @param Executable $executable
     * @return string $pid
     */
    function run(Executable $executable) {
        $settings = $executable->getSettings();
        $command = $settings['command'];

        $command = PIMCORE_DOCUMENT_ROOT . "/pimcore/cli/console.php " . $command;

        return Console::runPhpScriptInBackground($command);
    }
}
