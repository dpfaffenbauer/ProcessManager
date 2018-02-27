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

pimcore.registerNS('pimcore.plugin.processmanager.processes');

pimcore.plugin.processmanager.processes = Class.create({
    storeId : 'processmanager_processes',
    task : null,

    url : {
        list : '/admin/process_manager/processes/list'
    },

    initialize: function () {
        this.createStore();
        this.reloadProcesses();
    },

    reloadProcesses: function() {
        pimcore.globalmanager.get(this.storeId).load(function () {
            this.createInterval();
        }.bind(this));
    },

    createInterval : function() {
        this.task = setTimeout(function () {
            this.reloadProcesses();
        }.bind(this), 5000);
    },

    stop : function() {
        clearTimeout(this.task);
    },

    createStore : function () {
        var proxy = new Ext.data.HttpProxy({
            url : this.url.list
        });

        var reader = new Ext.data.JsonReader({}, [
            { name:'id' },
            { name:'name' },
            { name:'message' },
            { name:'progress' },
            { name:'total' }
        ]);

        var store = new Ext.data.Store({
            restful:    false,
            proxy:      proxy,
            reader:     reader,
            autoload:   true
        });

        pimcore.globalmanager.add(this.storeId, store);
        store.load();
    },

    activate: function () {
        var tabPanel = Ext.getCmp('pimcore_panel_tabs');
        tabPanel.setActiveItem(this.layoutId);
    },

    getGrid: function () {
        return {
            xtype: 'grid',
            store: pimcore.globalmanager.get(this.storeId),
            columns: [
                {
                    text : t('id'),
                    dataIndex : 'id',
                    width : 100
                },
                {
                    text: t('processmanager_message'),
                    dataIndex: 'message',
                    flex : 1
                },
                {
                    text     : t('processmanager_progress'),
                    xtype    : 'widgetcolumn',
                    width    : 120,
                    dataIndex: 'percentage',
                    widget: {
                        xtype: 'progressbarwidget',
                        textTpl: [
                            '{percent:number("0")}% ' + t('processmanager_text')
                        ]
                    }
                },
                {
                    xtype:'actioncolumn',
                    width:50,
                    items: [
                        {
                            iconCls : 'pimcore_icon_delete',
                            tooltip: t('delete'),
                            handler: function(grid, rowIndex) {
                                var rec = grid.getStore().getAt(rowIndex);

                                Ext.Ajax.request({
                                    url: '/admin/process_manager/processes/delete',
                                    jsonData : {
                                        id : rec.get("id")
                                    },
                                    method: 'delete',
                                    success: function () {
                                        pimcore.globalmanager.get(this.storeId).reload();
                                    }.bind(this)
                                });
                            }.bind(this)
                        }
                    ]
                },
            ],
            useArrows: true,
            autoScroll: true,
            animate: true,
            containerScroll: true,
            viewConfig: {
                loadMask: false
            }
        };
    }
});
