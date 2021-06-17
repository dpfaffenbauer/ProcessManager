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

pimcore.registerNS('pimcore.plugin.processmanager.queueitems');

pimcore.plugin.processmanager.queueitems = Class.create({
    storeId : 'processmanager_queueitems',
    task : null,

    url : {
        list : '/admin/process_manager/queueitems/list'
    },

    initialize: function () {
        this.createStore();
    },

    reloadProcesses: function() {
        pimcore.globalmanager.get(this.storeId).load(function () {
            this.createInterval();
        }.bind(this));
    },

    clear: function() {
        Ext.MessageBox.show({
            title: 'processmanager_queueitems_clear',
            msg: 'processmanager_queueitems_clear_confirmation',
            buttons: Ext.MessageBox.OKCANCEL,
            icon: Ext.MessageBox.WARNING,
            fn: function (btn) {
                if(btn == 'ok') {
                    Ext.Ajax.request({
                        scope: this,
                        url: '/admin/process_manager/queueitem/clear',
                        method: 'post',
                        success: function () {
                            pimcore.helpers.showNotification(t('success'), t('processmanager_queueitems_clear_success'), 'success');
                        }
                    });
                }
            }
        });
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
        var store = new Ext.data.JsonStore({
            remoteSort: true,
            remoteFilter: true,
            autoDestroy: true,
            autoSync: true,
            pageSize: pimcore.helpers.grid.getDefaultPageSize(),
            proxy: {
                type: 'ajax',
                reader: {
                    type: 'json',
                    rootProperty: 'data',
                    totalProperty: 'total'
                },
                api: {
                    read: this.url.list,
                }
            },
            fields: [
                { name:'id' },
                { name:'name' },
                { name:'description' },
                { name:'type' },
                { name:'queue' },
                { name:'created' },
                { name:'started' },
                { name:'completed' },
                { name:'status'}
            ]
        });

        pimcore.globalmanager.add(this.storeId, store);
        this.reloadProcesses();
    },

    activate: function () {
        var tabPanel = Ext.getCmp('pimcore_panel_tabs');
        tabPanel.setActiveItem(this.layoutId);
    },

    showReportWindow: function(data) {
        var raportWin = new Ext.Window({
            title: data.report.title,
            modal: true,
            iconCls: "pimcore_icon_reports",
            width: 700,
            height: 400,
            html: data.report,
            autoScroll: true,
            bodyStyle: "padding: 10px; background:#fff;",
            buttonAlign: "center",
            shadow: false,
            closable: true
        });
        raportWin.show();
    },

    showErrorWindow: function(message) {
        var errWin = new Ext.Window({
            title: "ERROR",
            modal: true,
            iconCls: "pimcore_icon_error",
            width: 600,
            height: 300,
            html: message,
            autoScroll: true,
            bodyStyle: "padding: 10px; background:#fff;",
            buttonAlign: "center",
            shadow: false,
            closable: true
        });
        errWin.show();
    },

    getGrid: function () {
        var store = pimcore.globalmanager.get(this.storeId);

        return {
            xtype: 'grid',
            store: store,
            bbar: pimcore.helpers.grid.buildDefaultPagingToolbar(store),
            columns: [
                {
                    text: t('id'),
                    dataIndex: 'id',
                    width: 100
                },
                {
                    text: t('name'),
                    dataIndex: 'name',
                    width: 300
                },
                {
                    text: t('description'),
                    dataIndex: 'description',
                    flex : 1
                },
                {
                    text: t('type'),
                    dataIndex: 'type',
                    flex : 1
                },
                {
                    text: t('processmanager_queue'),
                    dataIndex: 'queue',
                    flex : 1
                },
                {
                    text: t('processmanager_created'),
                    dataIndex: 'created',
                    renderer: function (value) {
                        if (value == 0) {
                            return null;
                        } else {
                            return Ext.Date.format(Ext.Date.parse(value, "U"), "Y-m-d H:i:s");
                        }
                    },
                    width: 180
                },
                {
                    text: t('processmanager_started'),
                    dataIndex: 'started',
                    renderer: function (value) {
                        if (value == 0) {
                            return null;
                        } else {
                            return Ext.Date.format(Ext.Date.parse(value, "U"), "Y-m-d H:i:s");
                        }
                    },
                    width: 180
                },
                {
                    text: t('processmanager_completed'),
                    dataIndex: 'completed',
                    renderer: function (value) {
                        if (value == 0) {
                            return null;
                        } else {
                            return Ext.Date.format(Ext.Date.parse(value, "U"), "Y-m-d H:i:s");
                        }
                    },
                    width: 180
                },                
                {
                    text : t('processmanager_status'),
                    width: 100,
                    dataIndex: 'status',
                    renderer: function (value, metadata, record) {
                        if (record.data.status != '' && record.data.status != null) {
                            return t('processmanager_' + record.data.status);
                        }
                    },
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
                                    url: '/admin/process_manager/queueitems/delete',
                                    jsonData : {
                                        id : rec.get("id")
                                    },
                                    method: 'delete',
                                    success: function () {
                                        //We don't reload the store here, this triggers a new timer, we just delete the
                                        //record manually from the store
                                        pimcore.globalmanager.get(this.storeId).remove(rec);
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
            },
            tbar: [
                {
                    xtype: 'button',
                    text: t('processmanager_queueitems_clear'),
                    iconCls: 'pimcore_icon_delete',
                    handler: this.clear
                }
            ]
        };
    }
});
