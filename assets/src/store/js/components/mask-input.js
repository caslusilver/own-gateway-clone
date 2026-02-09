/**
 * Mask the credit card form input fields
 *
 * WooCommerce update checkout fields in some form fields updates. jQuery is
 * used for capture the `updated_checkout`. The event is triggered by jQuery,
 * so it's impossible capture using VanillaJS.
 *
 * The fields are maked by imask using the Pattern Mask.
 *
 * {@link https://unmanner.github.io/imaskjs/guide.html#pattern}
 *
 * @package WooAsaas
 */

import $ from 'jquery';
import IMask from 'imask';

$( document ).on(
	'ready updated_checkout', function() {
		let inputs = document.querySelectorAll( '.payment_method_asaas-credit-card input.input-text' );

		inputs.forEach(
			function( input ) {
				let inputMask = input.dataset.mask;

				if ( 'undefined' === typeof inputMask ) {
					return;
				}

				new IMask(
					input, {
						mask: inputMask
					}
				);
			}
		);
	}
);
