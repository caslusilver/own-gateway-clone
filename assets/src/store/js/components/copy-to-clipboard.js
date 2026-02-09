/**
 * Copy to clipboard the Pix payload code.
 *
 * @package WooAsaas
 */

const copyToClipboardButton = document.querySelector('.woocommerce-order-details__asaas-pix-button');

if (null !== copyToClipboardButton) {
	const payloadCode = document.querySelector('.woocommerce-order-details__asaas-pix-code');
	const defaultButtonText = copyToClipboardButton.textContent;
	const successCopiedButtonText = copyToClipboardButton.getAttribute('data-success-copy');

	copyToClipboardButton.addEventListener('click', function() {
		navigator.clipboard.writeText(payloadCode.value);

		copyToClipboardButton.textContent = successCopiedButtonText;
		setTimeout(() => {
			copyToClipboardButton.textContent = defaultButtonText;
		}, 3000);
	});
}