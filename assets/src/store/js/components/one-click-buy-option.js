/**
 * One click buy new credit card option.
 *
 * @package WooAsaas
 */

import $ from 'jquery';

$(document).ajaxComplete(function () {
	const creditCardOptions = $('[name="asaas_cc_options"]');

    if (0 !== creditCardOptions.length) {
        const ccFormWrapper = $('.one-click-buy-option .asaas-cc-form-wrapper');
        
        creditCardOptions.on('change', (event) => {
            if ('credit-card-new' !== event.target.value) {
                ccFormWrapper.hide();
                return;
            }
            ccFormWrapper.show();
        });
    }
});
  