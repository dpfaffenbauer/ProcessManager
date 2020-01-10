/**
 * CoreShop
 *
 * LICENSE
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015-2020 Dominik Pfaffenbauer (https://www.pfaffenbauer.at)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 */

pimcore.registerNS('pimcore.layout.portlets.processManagerProcesses');
pimcore.layout.portlets.processManagerProcesses = Class.create(pimcore.layout.portlets.abstract, {

    getType: function () {
        return 'pimcore.layout.portlets.processManagerProcesses';
    },

    getName: function () {
        return t('processmanager_processes');
    },

    getIcon: function () {
        return 'processmanager_icon_processes';
    },

    getLayout: function (portletId) {
        var processPanel = new pimcore.plugin.processmanager.processes();

        this.layout = Ext.create('Portal.view.Portlet', Object.extend(this.getDefaultConfig(), {
            title: this.getName(),
            iconCls: this.getIcon(),
            height: 275,
            layout: 'fit',
            items: [processPanel.getGrid()],
            listeners : {
                destroy : function() {
                    processPanel.stop();
                }
            }
        }));

        this.layout.portletId = portletId;
        return this.layout;
    }
});
