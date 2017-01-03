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

pimcore.registerNS('pimcore.plugin.processmanager.panel');

pimcore.plugin.processmanager.panel = Class.create({
    layoutId: 'processmanager_definition_processes',
    iconCls : 'processmanager_icon_processes',

    types : [],

    initialize: function () {
        Ext.Ajax.request({
            url: '/plugin/ProcessManager/admin_executable/get-types',
            method: 'GET',
            success: function (result) {
                var result = Ext.decode(result.responseText);

                this.types = result.types;

                this.getLayout();
            }.bind(this)
        });
    },

    activate: function () {
        var tabPanel = Ext.getCmp('pimcore_panel_tabs');
        tabPanel.setActiveItem(this.layoutId);
    },

    getLayout: function () {
        if (!this.layout) {

            var processPanel = new pimcore.plugin.processmanager.processes();
            var executablesPanel = new pimcore.plugin.processmanager.executables(this.types);

            var tabPanel = new Ext.tab.Panel({
                items : [
                    executablesPanel.getLayout(),
                    {
                        layout : 'fit',
                        title: t('processmanager_processes'),
                        iconCls: this.iconCls,
                        items : [
                            processPanel.getGrid()
                        ]
                    }
                ]
            });

            // create new panel
            this.layout = new Ext.Panel({
                id: this.layoutId,
                title: t('processmanager'),
                iconCls: this.iconCls,
                border: false,
                closable: true,
                layout : 'fit',
                items: [tabPanel]
            });

            // add event listener
            var layoutId = this.layoutId;
            this.layout.on('destroy', function () {
                pimcore.globalmanager.remove(layoutId);

                processPanel.stop();
            }.bind(this));

            // add panel to pimcore panel tabs
            var tabPanel = Ext.getCmp('pimcore_panel_tabs');
            tabPanel.add(this.layout);
            tabPanel.setActiveItem(this.layoutId);

            // update layout
            pimcore.layout.refresh();
        }

        return this.layout;
    },

    refresh : function () {
        if (pimcore.globalmanager.exists(this.storeId)) {
            pimcore.globalmanager.get(this.storeId).load();
        }
    }
});
