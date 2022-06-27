define([
    'jquery',
    'Magento_Catalog/js/price-utils',
    'underscore',
    'mage/template',
    'jquery/ui'
], function ($, utils, _, mageTemplate) {
    'use strict';

    return function (widget) {
        $.widget('mage.priceBox', widget, {
            reloadPrice: function reDrawPrices() {
                let aeFinalPrice = null;

                var priceFormat = (this.options.priceConfig && this.options.priceConfig.priceFormat) || {},
                    priceTemplate = mageTemplate(this.options.priceTemplate);

                _.each(this.cache.displayPrices, function (price, priceCode) {
                    price.final = _.reduce(price.adjustments, function (memo, amount) {
                        return memo + amount;
                    }, price.amount);

                    price.formatted = utils.formatPrice(price.final, priceFormat);

                    $('[data-price-type="' + priceCode + '"]', this.element).html(priceTemplate({
                        data: price
                    }));

                    aeFinalPrice = price;
                }, this);

                this.recalculateEmiOptions(aeFinalPrice);
            },

            recalculateEmiOptions: function(aeFinalPrice) {
                let elementId = "adobe_emi_final_price_textbox";
                if ($('#' + elementId).length > 0) {
                    $('#' + elementId).val(aeFinalPrice.final).trigger('change');
                }
            }
        });

        return $.mage.priceBox;
    }
});
