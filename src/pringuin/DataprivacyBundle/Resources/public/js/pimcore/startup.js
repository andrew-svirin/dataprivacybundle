pimcore.registerNS("pimcore.plugin.pringuinDataprivacyBundle");

pimcore.plugin.pringuinDataprivacyBundle = Class.create(pimcore.plugin.admin, {
    getClassName: function () {
        return "pimcore.plugin.pringuinDataprivacyBundle";
    },

    initialize: function () {
        pimcore.plugin.broker.registerPlugin(this);
    },

    showTab: function () {
            try {
                pimcore.globalmanager.get('dataprivacyadmin_index').activate();
            } catch (e) {
                pimcore.globalmanager.add(
                    'dataprivacyadmin_index',
                    new pimcore.tool.genericiframewindow(
                        'index',
                        '/admin/pringuin_dataprivacy',
                        "pimcore_icon_keys",
                        t("Dataprivacy"))
                );
            }
    },

    pimcoreReady: function (params, broker) {
        var toolbar = pimcore.globalmanager.get("layout_toolbar");

        var action = new Ext.Action({
            id: "pimcore_menu_dataprivacy",
            text: t("Dataprivacy"),
            iconCls:"pimcore_icon_keys",
            handler: this.showTab
        });

        toolbar.extrasMenu.add(action);
    }
});

var pringuinDataprivacyBundlePlugin = new pimcore.plugin.pringuinDataprivacyBundle();
