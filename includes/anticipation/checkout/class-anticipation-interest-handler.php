<?php
/**
 * Order Anticipation Handler class
 *
 * @package WooAsaas
 */

namespace WC_Asaas\Anticipation\Checkout;

use WC_Order;
use WC_Asaas\Api\Api;

/**
 * Handle anticipation request.
 */
class Anticipation_Interest_Handler {

	/**
	 * Current order.
	 *
	 * @var WC_Order
	 */
	private $order;

	/**
	 * Constructor.
	 *
	 * @param WC_Order $order The current order.
	 */
	public function __construct( WC_Order $order ) {
		$this->order = $order;
	}

	/**
	 * Anticipation handler.
	 *
	 * @param \stdClass $payment_response The current Asaas API payment response.
	 * @return void
	 */
	public function request_anticipation( \stdClass $payment_response ) {
		$gateway = wc_get_payment_gateway_by_order( $this->order );

		$anticipation_enabled = 'yes' === $gateway->settings['anticipation'];
		if ( ! $anticipation_enabled ) {
			return;
		}

		$api = new Api( $gateway );

		$payment_data = array();
		if ( property_exists( $payment_response, 'installment' ) ) {
			$payment_data['installment'] = $payment_response->installment;
		} else {
			$payment_data['payment'] = $payment_response->id;
		}

		$response      = $api->anticipations()->request( $payment_data );
		$response_json = $response->get_json();

		$this->add_api_response_order_note( $response_json );
	}

	/**
	 * Add an anticipation response order note.
	 *
	 * @param \stdClass $response The Asaas API response.
	 * @return void
	 */
	private function add_api_response_order_note( $response ) {
		if ( ! is_object( $response ) ) {
			return;
		}

		$has_errors = property_exists( $response, 'errors' );

		if ( $has_errors ) {
			/* translators: %1$s is the error description, %2$s is the Asaas anticipation URL */
			$error_message = sprintf( __( 'Anticipation not created. %1$s <a href="%2$s" target="_blank" rel="noopener">Click here for more details.</a>', 'woo-asaas' ), $response->errors[0]->description, 'https://www.asaas.com/anticipation/index' );

			$this->order->add_order_note( $error_message );

			return;
		}

		/* translators: %1$s is the net value, %2$s is the Asaas receivable anticipation list URL */
		$success = sprintf( __( 'Anticipation successfully created. Net value of %1$s. Track the status of the advance through Asaas or <a href="%2$s" target="_blank" rel="noopener">clicking here.</a>', 'woo-asaas' ), wp_strip_all_tags( wc_price( $response->netValue ) ), 'https://www.asaas.com/receivableAnticipationList/index' ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar

		$this->order->add_order_note( $success );
	}
}
