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

pimcore.registerNS('pimcore.plugin.processmanager');


document.addEventListener(pimcore.events.pimcoreReady, function () {
    var user = pimcore.globalmanager.get('user');

    if (user.isAllowed('process_manager')) {
        var exportMenu = new Ext.Action({
            text: t('processmanager'),
            iconCls: 'processmanager_nav_icon_processes',
            handler: function () {
                try {
                    pimcore.globalmanager.get('processmanager_definition_processes').activate();
                } catch (e) {
                    pimcore.globalmanager.add('processmanager_definition_processes', new pimcore.plugin.processmanager.panel());
                }
            },
        });

        layoutToolbar.extrasMenu.add({xtype: 'menuseparator'});
        layoutToolbar.extrasMenu.add(exportMenu);

        const event = document.createEvent('CustomEvent');
        event.initCustomEvent('processmanager.ready', true, true);
        document.dispatchEvent(event);
    }
});
