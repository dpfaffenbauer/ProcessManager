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

pimcore.registerNS('pimcore.plugin.processmanager.processes');

pimcore.plugin.processmanager.processes = Class.create({
    storeId : 'processmanager_processes',
    task : null,

    url : {
        list : '/admin/process_manager/processes/list'
    },

    initialize: function () {
        this.createStore();
    },

    reloadProcesses: function() {
        pimcore.globalmanager.get(this.storeId).load(function () {
            this.createInterval();
        }.bind(this));
    },

    clear: function(seconds, msg) {
        Ext.MessageBox.show({
            title: t('processmanager_processes_clear'),
            msg: t(msg),
            buttons: Ext.MessageBox.OKCANCEL,
            icon: Ext.MessageBox.WARNING,
            fn: function (btn) {
                if(btn == 'ok') {
                    Ext.Ajax.request({
                        scope: this,
                        url: '/admin/process_manager/processes/clear',
                        params: {
                            seconds: seconds
                        },
                        method: 'post',
                        success: function () {
                            pimcore.helpers.showNotification(t('success'), t('processmanager_processes_clear_success'), 'success');
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
                { name:'message' },
                { name:'progress' },
                { name:'total' },
                { name:'started' },
                { name:'completed' },
                { name:'artifact' }
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
            plugins: 'gridfilters',
            columns: [
                {
                    text: t('id'),
                    dataIndex: 'id',
                    width: 100
                },
                {
                    text: t('name'),
                    dataIndex: 'name',
                    width: 400,
                    filter: 'string'
                },
                {
                    text: t('processmanager_message'),
                    dataIndex: 'message',
                    flex : 1,
                    filter: 'string'
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
                    text : t('processmanager_report'),
                    xtype:'actioncolumn',
                    width:50,
                    items: [
                        {
                            iconCls : 'pimcore_icon_reports',
                            tooltip: t('processmanager_report'),
                            handler: function(grid, rowIndex) {
                                var rec = grid.getStore().getAt(rowIndex);

                                Ext.Ajax.request({
                                    url: '/admin/process_manager/processes/log-report',
                                    params : {
                                        id : rec.get("id")
                                    },
                                    success: function (response, options) {
                                        var data = Ext.decode(response.responseText);
                                        if (data.success) {
                                            this.showReportWindow(data);
                                        } else {
                                            this.showErrorWindow(data.message);
                                        }
                                    }.bind(this)
                                });

                            }.bind(this)
                        }
                    ]
                },
                {
                    text : t('processmanager_log_download'),
                    xtype:'actioncolumn',
                    width:50,
                    items: [
                        {
                            iconCls : 'pimcore_icon_download',
                            tooltip: t('processmanager_log_download'),
                            handler: function(grid, rowIndex) {
                                var id = grid.getStore().getAt(rowIndex).get('id');
                                pimcore.helpers.download("/admin/process_manager/processes/log-download?id=" + id);
                            }.bind(this)
                        }
                    ]
                },
                {
                    text : t('processmanager_artifact_download'),
                    xtype:'actioncolumn',
                    width: 50,
                    renderer: function(value, metadata, record) {
                        var artifact = record.data.artifact;
                        if (!artifact) {
                            return;
                        }

                        var id = Ext.id();
                        Ext.defer(function () {
                            if (Ext.get(id)) {
                                new Ext.button.Button({
                                    renderTo: id,
                                    iconCls: 'pimcore_icon_download',
                                    cls: 'processmanager_artifact_download',
                                    handler: function () {
                                        pimcore.helpers.download("/admin/asset/download?id=" + artifact)
                                    }
                                });
                            }
                        }, 50);

                        return Ext.String.format('<span id="{0}"></span>', id);
                    }
                },
                {
                    text : t('processmanager_status'),
                    width: 100,
                    dataIndex: 'status',
                    filter: 'string',
                    renderer: function (value, metadata, record) {
                        if (record.data.status != '' && record.data.status != null) {
                            return t('processmanager_' + record.data.status);
                        }
                    },
                },
                {
                    text : t('processmanager_action'),
                    xtype: 'actioncolumn',
                    align: 'center',
                    renderer: function(value, metadata, record) {
                        var status = record.data.status;
                        var stoppable = record.data.stoppable;
                        var processId = record.data.id;

                        var id = Ext.id();
                        Ext.defer(function () {
                            if (Ext.get(id) && stoppable && status == 'running') {
                                new Ext.button.Button({
                                    renderTo: id,
                                    iconCls: 'processmanager_icon_process_stop',
                                    cls: 'processmanager_grid_transparent_button',
                                    backgroundColor: null,
                                    handler: function (button) {
                                        button.disable();
                                        Ext.Ajax.request({
                                            url: '/admin/process_manager/processes/stop-process?id=' + processId,
                                            method: 'GET',
                                            failure: function () {
                                                button.enable();
                                            }
                                        }).bind(this);
                                    }
                                });
                            }
                        }, 50);

                        return Ext.String.format('<span id="{0}"></span>', id);
                    }
                },
                {
                    xtype: 'actioncolumn',
                    width: 50,
                    renderer: function (value, metadata, record) {
                        var status = record.data.status;
                        var processId = record.data.id;

                        var id = Ext.id();
                        Ext.defer(function () {
                            if (Ext.get(id)) {
                                new Ext.button.Button({
                                    renderTo: id,
                                    iconCls: 'pimcore_icon_delete',
                                    cls: 'processmanager_grid_transparent_button',
                                    backgroundColor: null,
                                    handler: function (button) {
                                        button.disable();
                                        Ext.Ajax.request({
                                            url: '/admin/process_manager/processes/delete',
                                            method: 'delete',
                                            jsonData: {
                                                id: processId
                                            },
                                            failure: function () {
                                                button.enable();
                                            }
                                        }).bind(this);
                                    }
                                });
                            }
                        }, 50);

                        return Ext.String.format('<span id="{0}"></span>', id);
                    }
                }
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
                    xtype: 'splitbutton',
                    text: t("processmanager_processes_clear"),
                    iconCls: "pimcore_icon_delete",
                    menu: [
                        {
                            text: t('processmanager_processes_clear_7days'),
                            iconCls: "pimcore_icon_delete",
                            handler: this.clear.bind(this, 604_800, 'processmanager_processes_clear_confirmation_7days')
                        }, {
                            text: t('processmanager_processes_clear_1day'),
                            iconCls: "pimcore_icon_delete",
                            handler: this.clear.bind(this, 86_400, 'processmanager_processes_clear_confirmation_1day')
                        }, {
                            text: t('processmanager_processes_clear_12hours'),
                            iconCls: "pimcore_icon_delete",
                            handler: this.clear.bind(this, 43_200, 'processmanager_processes_clear_confirmation_12hours')
                        }, {
                            text: t('processmanager_processes_clear_all'),
                            iconCls: "pimcore_icon_delete",
                            handler: this.clear.bind(this, 0, 'processmanager_processes_clear_confirmation_all')
                        }
                    ]
                }
            ]
        };
    }
});
