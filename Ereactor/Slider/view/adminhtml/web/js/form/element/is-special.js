define([
    'jquery',
    'Magento_Ui/js/form/element/select'
], function ($, Select) {
    'use strict';

    return Select.extend({
        defaults: {
            customName: '${ $.parentName }.${ $.index }_input'
        },

        initialize: function () {
            this._super();

            if (this.customEntry) {
                registry.get(this.name, this.initInput.bind(this));
            }

            if (this.filterBy) {
                this.initFilter();
            }

            return this;
        },

        selectOption: function (id) {
            setTimeout(function() {
            if ($("#"+id).val() == 'cms_page') {
                $('div[data-index="keywords"]').parent().hide();
                $('div[data-index="url_key"]').show();
            }

            if ($("#"+id).val() == 'gallery') {
                $('div[data-index="keywords"]').parent().show();
                $('div[data-index="url_key"]').hide();
            }
            }, 1);
        },
    });
});

