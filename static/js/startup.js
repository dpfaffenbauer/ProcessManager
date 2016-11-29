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

pimcore.registerNS('pimcore.plugin.processmanager');

pimcore.plugin.processmanager = Class.create(pimcore.plugin.admin, {
    getClassName: function () {
        return 'pimcore.plugin.processmanager';
    },

    initialize: function () {
        pimcore.plugin.broker.registerPlugin(this);
    },

    pimcoreReady: function (params, broker) {

        var user = pimcore.globalmanager.get('user');

        if (user.isAllowed('plugins')) {

            var exportMenu = new Ext.Action({
                text: t('processmanager'),
                iconCls: 'processmanager_icon_processes',
                handler:this.openProcesses
            });

            layoutToolbar.extrasMenu.add({xtype: 'menuseparator'});
            layoutToolbar.extrasMenu.add(exportMenu);

            $(document).trigger('processmanager.ready');
        }
    },

    openProcesses : function ()
    {
        try {
            pimcore.globalmanager.get('processmanager_definition_processes').activate();
        }
        catch (e) {
            pimcore.globalmanager.add('processmanager_definition_processes', new pimcore.plugin.processmanager.panel());
        }
    }
});

var processmanagerPlugin = new pimcore.plugin.processmanager();

