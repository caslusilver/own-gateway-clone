<?php
/**
 * Webhook Settings class
 *
 * @package WooAsaas
 */

namespace WC_Asaas\Webhook\Admin\Settings;

use WC_Asaas\Gateway\Gateway;

/**
 * Webhook settings common methods
 */
class Webhook_Settings {

	/**
	 * The gateway settings
	 *
	 * @var Gateway
	 */
	protected $gateway;

	/**
	 * Init the default field sections
	 *
	 * @param Gateway $gateway The gateway that call the logger.
	 */
	public function __construct( $gateway ) {
		$this->gateway = $gateway;
	}

	/**
	 * Validate email notification field
	 *
	 * @param string $value The input value.
	 * @return string The value sanitized.
	 */
	public function validate_email_notification_field( string $value ) {
		if ( '' === $value || ! is_email( $value ) ) {
			wp_die(
				esc_html__( 'Please enter a valid email notification address.', 'woo-asaas' ),
				esc_html__( 'Required field', 'woo-asaas' ),
				array(
					'back_link' => true,
				)
			);
		}

		return $value;
	}
}
