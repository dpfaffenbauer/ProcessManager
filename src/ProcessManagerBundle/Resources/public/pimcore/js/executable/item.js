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

pimcore.registerNS('pimcore.plugin.processmanager.executable');
pimcore.registerNS('pimcore.plugin.processmanager.executable.item');

pimcore.plugin.processmanager.executable.item = Class.create({
    url: {
        save: '/admin/process_manager/executables/save',
        add: '/admin/process_manager/executables/add'
    },

    types: [],
    data: [],

    initialize: function (data, types, cb) {
        this.data = data;
        this.types = types;

        var typesStoreData = [];

        this.types.forEach(function (type) {
            typesStoreData.push([type]);
        }.bind(this));

        this.typeStore = new Ext.data.ArrayStore({
            data: typesStoreData,
            fields: ['type'],
            idProperty: 'type'
        });

        this.window = new Ext.window.Window({
            width: 800,
            height: 400,
            resizeable: true,
            modal: false,
            layout: 'fit',
            title: t('processmanager_executables'),
            iconCls: 'processmanager_icon_executable',
            bodyPadding: 10,
            items: this.getPanel(),
            buttons: [{
                text: t('save'),
                iconCls: 'pimcore_icon_apply',
                handler: this.save.bind(this)
            }],
            listeners: {
                close: function () {
                    if (Ext.isFunction(cb)) {
                        cb();
                    }
                }
            }
        });

        this.window.show();
    },

    getPanel: function () {
        if (!this.panel) {
            this.panel = Ext.create({
                xtype: 'panel',
                autoScroll: true,
                items: [
                    this.getSettings(),
                    this.getTypeSettings()
                ]
            });
        }

        return this.panel;
    },

    getSettings: function () {
        if (!this.settings) {
            this.settings = Ext.create({
                xtype: 'form',
                defaults: {
                    anchor: '100%'
                },
                items: [
                    {
                        xtype: 'textfield',
                        fieldLabel: t('name'),
                        name: 'name',
                        value: this.data.name,
                        allowBlank: false
                    },
                    {
                        xtype: 'textarea',
                        fieldLabel: t('description'),
                        name: 'description',
                        value: this.data.description
                    },
                    {
                        xtype: 'textfield',
                        fieldLabel: t('processmanager_cron'),
                        name: 'cron',
                        value: this.data.cron
                    },
                    {
                        xtype: 'combo',
                        fieldLabel: t('type'),
                        name: 'type',
                        displayField: 'type',
                        valueField: 'type',
                        store: this.typeStore,
                        value: this.data.type,
                        allowBlank: false,
                        listeners: {
                            change: function (combo, value) {
                                this.updateTypeSettingsViews(value);
                            }.bind(this)
                        }
                    },
                    {
                        xtype: 'checkbox',
                        name: 'active',
                        fieldLabel: t('active'),
                        value: this.data.active
                    }
                ]
            });
        }

        return this.settings;
    },

    getTypeSettings: function () {
        if (!this.typeSettings) {
            this.typeSettings = new Ext.form.Panel({
                autoScroll: true,
                border: false,
                title: t('type'),
                bodyPadding: '10 0',
                defaults: {
                    anchor: '100%'
                }
            });
        }

        if (this.data.type) {
            this.updateTypeSettingsViews(this.data.type);
        }

        return this.typeSettings;
    },

    updateTypeSettingsViews: function (type) {
        if (this.typeSettings) {
            this.typeSettings.removeAll();
            this.typeSettings.setTitle(type);
        }

        if (processmanager.executable.types[type.toLowerCase()] !== undefined) {
            this.typeSettings.add(new processmanager.executable.types[type.toLowerCase()](this.data ? this.data : {}, this).getItems());
        }
    },

    getSaveData: function () {
        var data = Ext.clone(this.data);

        Ext.apply(data, this.settings.getForm().getFieldValues());
        Ext.apply(data.settings, this.typeSettings.getForm().getFieldValues());

        return data;
    },

    save: function () {
        var me = this;

        if (this.isValid()) {
            var saveData = this.getSaveData();

            if (this.data.id) {
                saveData['id'] = this.data.id;
            }

            var updateFunction = function () {
                Ext.Ajax.request({
                    url: this.url.save,
                    method: 'post',
                    jsonData: saveData,
                    success: function (response) {
                        try {
                            var res = Ext.decode(response.responseText);

                            if (res.success) {
                                pimcore.helpers.showNotification(t('success'), t('success'), 'success');

                                this.window.close();
                            } else {
                                pimcore.helpers.showNotification(t('error'), t('error'),
                                    'error', res.message);
                            }
                        } catch (e) {
                            pimcore.helpers.showNotification(t('error'), t('error'), 'error');
                        }
                    }.bind(this)
                });
            }.bind(this);

            if (!this.data.id) {
                Ext.Ajax.request({
                    url: this.url.add,
                    method: 'post',
                    jsonData: saveData,
                    success: function (response) {
                        try {
                            var res = Ext.decode(response.responseText);

                            saveData.id = res.data.id;
                            updateFunction.call(me);
                        } catch (e) {
                            pimcore.helpers.showNotification(t('error'), t('error'), 'error');
                        }
                    }.bind(this)
                });
            }
            else {
                updateFunction.call(me);
            }
        }
    },

    isValid: function () {
        return this.settings.isValid() && this.typeSettings.isValid();
    }
});
