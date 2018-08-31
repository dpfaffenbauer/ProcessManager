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

pimcore.registerNS('pimcore.plugin.processmanager.executables');

pimcore.plugin.processmanager.executables = Class.create({
    storeId: 'processmanager_executables',
    iconCls: 'processmanager_icon_executable',
    task: null,
    types: [],

    url: {
        list: '/admin/process_manager/executables/list',
        delete: '/admin/process_manager/executables/delete'
    },

    initialize: function (types) {
        this.createStore();

        this.types = types;
    },

    createStore: function () {
        var proxy = new Ext.data.HttpProxy({
            url: this.url.list
        });

        var reader = new Ext.data.JsonReader({}, [
            {name: 'id'},
            {name: 'name'},
            {name: 'message'},
            {name: 'progress'},
            {name: 'total'}
        ]);

        var store = new Ext.data.Store({
            restful: false,
            proxy: proxy,
            reader: reader,
            autoload: true
        });

        pimcore.globalmanager.add(this.storeId, store);
        store.load();
    },

    activate: function () {
        var tabPanel = Ext.getCmp('pimcore_panel_tabs');
        tabPanel.setActiveItem(this.layoutId);
    },

    getLayout: function () {
        if (!this.layout) {
            var menu = [];

            this.types.forEach(function (type) {
                menu.push({
                    text: t('processmanager_type_' + type),
                    iconCls: 'processmanager_icon_executable_type_' + type.toLowerCase(),
                    handler: this.createNewExe.bind(this, type)
                });
            }.bind(this));

            this.layout = new Ext.Panel({
                title: t('processmanager_executables'),
                iconCls: this.iconCls,
                border: false,
                layout: 'fit',
                items: [this.getGrid()],
                tbar: [
                    {
                        xtype: 'splitbutton',
                        text: t('processmanager_create_executable'),
                        iconCls: this.iconCls + "_add",
                        menu: new Ext.menu.Menu({
                            items: menu
                        })
                    }
                ]
            });
        }

        return this.layout;
    },

    createNewExe: function (type) {
        new pimcore.plugin.processmanager.executable.item({type: type, settings: {}}, this.types, function () {
            pimcore.globalmanager.get(this.storeId).reload();
        }.bind(this));
    },

    getGrid: function () {
        return {
            xtype: 'grid',
            store: pimcore.globalmanager.get(this.storeId),
            columns: [
                {
                    text: t('id'),
                    dataIndex: 'id',
                    width: 100
                },
                {
                    text: t('name'),
                    dataIndex: 'name',
                    flex: 1
                },
                {
                    text: t('description'),
                    dataIndex: 'description',
                    flex: 1
                },
                {
                    text: t('processmanager_cron'),
                    dataIndex: 'cron',
                    width: 100
                },
                {
                    xtype: 'actioncolumn',
                    width: 50,
                    items: [
                        {
                            iconCls: 'processmanager_icon_executable_run',
                            tooltip: t('run'),
                            handler: function (grid, rowIndex, colIndex) {
                                var rec = grid.getStore().getAt(rowIndex);

                                Ext.Ajax.request({
                                    url: '/admin/process_manager/executables/run',
                                    params: {
                                        id: rec.get("id")
                                    },
                                    method: 'GET',
                                    success: function (result) {
                                        result = Ext.decode(result.responseText);

                                        if (result.success) {
                                            Ext.Msg.alert(t('success'), t('processmanager_executable_started'));
                                        }
                                        else {
                                            Ext.Msg.alert(t('error'), result.message);
                                        }
                                    }.bind(this)
                                });
                            }.bind(this)
                        }
                    ]
                },
                {
                    xtype: 'actioncolumn',
                    width: 50,
                    items: [
                        {
                            iconCls: 'pimcore_icon_edit',
                            tooltip: t('edit'),
                            handler: function (grid, rowIndex, colIndex) {
                                var rec = grid.getStore().getAt(rowIndex);

                                Ext.Ajax.request({
                                    url: '/admin/process_manager/executables/get',
                                    params: {
                                        id: rec.get("id")
                                    },
                                    method: 'GET',
                                    success: function (result) {
                                        result = Ext.decode(result.responseText);

                                        new pimcore.plugin.processmanager.executable.item(result.data, this.types, function () {
                                            pimcore.globalmanager.get(this.storeId).reload();
                                        }.bind(this));
                                    }.bind(this)
                                });
                            }.bind(this)
                        }
                    ]
                },
                {
                    xtype: 'actioncolumn',
                    width: 40,
                    tooltip: t('delete'),
                    icon: '/bundles/pimcoreadmin/img/flat-color-icons/delete.svg',
                    handler: function (grid, rowIndex, colIndex) {
                        var rec = grid.getStore().getAt(rowIndex);
                        this.delete(rec.get('id'));
                    }.bind(this)
                }
            ],
            useArrows: true,
            autoScroll: true,
            animate: true,
            containerScroll: true,
            viewConfig: {
                loadMask: false
            }
        };
    },

    delete: function (id) {
        Ext.Ajax.request({
            url: this.url.delete,
            method: 'delete',
            jsonData: {
                id: id
            },
            success: function (response) {
                try {
                    var res = Ext.decode(response.responseText);

                    if (res.success) {
                        pimcore.helpers.showNotification(t('success'), t('success'), 'success');
                        pimcore.globalmanager.get(this.storeId).load();
                    } else {
                        pimcore.helpers.showNotification(t('error'), t('error'),
                            'error', res.message);
                    }
                } catch (e) {
                    pimcore.helpers.showNotification(t('error'), t('error'), 'error');
                }
            }.bind(this)
        });
    }
});
