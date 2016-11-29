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
 * @copyright  Copyright (c) 2016 lineofcode.at (http://www.lineofcode.at)
 * @license    https://github.com/dpfaffenbauer/ProcessManager/blob/master/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

namespace ProcessManager\Model;

use Pimcore\Logger;
use Pimcore\Model\AbstractModel;

/**
 * Class Type
 * @package ProcessManager\Process
 */
abstract class Type
{
    /**
     * runs the executable
     *
     * @param Executable $executable
     * @return mixed
     */
    abstract function run(Executable $executable);
}
