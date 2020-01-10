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

pimcore.registerNS('processmanager.executable.types.cli');

processmanager.executable.types.cli = Class.create(pimcore.plugin.processmanager.executable.abstractType, {
    getItems : function () {
        return [{
            xtype : 'textfield',
            fieldLabel: t('processmanager_command'),
            name: 'command',
            value : this.data.settings.command,
            allowBlank : false
        }];
    }
});
