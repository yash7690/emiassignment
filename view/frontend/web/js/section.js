define([
    'jquery',
    'uiComponent',
    'Magento_Customer/js/customer-data',
    'Magento_Catalog/js/price-utils',
    'ko',
    'dropdownDialog',
    'mage/translate'
], function ($, Component, customerData, utils, ko, dropdownDialog) {
    'use strict';

    return Component.extend({
        priceFormat: false,
        minEmi: ko.observable(0),
        finalPrice : ko.observable(0),
        storeConfigEmiOptions: [],
        emiOptions: ko.observableArray([]),

        initialize: function (config) {
            let self = this;

            this._super();
            this.customer = customerData.get('customer');
            this.priceFormat = config.priceFormat;
            this.storeConfigEmiOptions = config.storeConfigEmiOptions;
            this.finalPrice.subscribe(function(basePrice) {
                if (self.storeConfigEmiOptions) {
                    let storeConfigEmiOptions = self.storeConfigEmiOptions;
                    storeConfigEmiOptions = $.parseJSON(storeConfigEmiOptions);
                    if (storeConfigEmiOptions.length) {
                        let lines = [];
                        let emis = [];
                        $(storeConfigEmiOptions).each(function(k, item) {
                            let gender = parseInt(item.gender);
                            let customer_gender = self.customer().gender;

                            // if any other than female, we must consider male, as per assignment
                            if (customer_gender != 2) {
                                customer_gender = 1;
                            }

                            if (customer_gender == gender) {
                                let interest_rate = parseFloat(item.interest_rate);
                                let r = interest_rate / 1200;
                                let tenure_month = parseInt(item.tenure_month);


                                // formula we are using is below
                                // EMI = P × r × (1 + r)n/((1 + r)n - 1)
                                let emi = basePrice * r * (((1 + r) ** tenure_month) / (((1 + r) ** tenure_month) - 1));
                                let emiFormatted = utils.formatPrice(emi.toFixed(2), self.priceFormat);
                                emis.push(emi);

                                let totalAmount = emi * tenure_month;
                                let totalAmountFormatted = utils.formatPrice(totalAmount.toFixed(2), self.priceFormat);

                                let totalInterestPayable = parseFloat(totalAmount - basePrice);
                                let totalInterestPayableFormatted = utils.formatPrice(totalInterestPayable.toFixed(2), self.priceFormat);
                                lines.push({
                                    plan: $.mage.__("%1 X %2 m").replace('%1', emiFormatted).replace('%2', tenure_month),
                                    interest: $.mage.__("%1 (%2%)").replace('%1', totalInterestPayableFormatted).replace('%2', interest_rate),
                                    total: totalAmountFormatted
                                });
                            }
                        });

                        if (lines) {
                            self.emiOptions(lines);
                            let minEmi = Math.min.apply(null, emis);
                            self.minEmi(utils.formatPrice(minEmi.toFixed(2), self.priceFormat));
                        }
                    }
                }
            });
            this.finalPrice(config.initialPrice);

            $(document).on('click', '.adobe-emi-dropdown-button', function() {
                $('#adobe_emi_dropdown_content_wrapper').dropdownDialog({
                    appendTo: '.adobe-emi-dropdown-wrap',
                    triggerTarget: '.adobe-emi-dropdown-button',
                    closeOnMouseLeave: false,
                    closeOnEscape: true,
                    timeout: 2000,
                    triggerClass: 'active',
                    parentClass: 'active',
                    autoOpen: true,
                    buttons: []
                });
            });
        }
    });
});
