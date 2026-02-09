<?php
/**
 * Webhook Settings Status class
 *
 * @package WooAsaas
 */

namespace WC_Asaas\Webhook\Admin\Settings;

use Exception;
use WP_Screen;
use WC_Asaas\WC_Asaas;
use WC_Asaas\Webhook\Meta\Webhook_Meta_Status;

/**
 * Webhook section fields for gateway settings
 */
class Webhook_Settings_Status {

	/**
	 * Webhook meta status
	 *
	 * @var Webhook_Meta_Status
	 */
	protected $status;

	/**
	 * Instance of this class
	 *
	 * @var self
	 */
	protected static $instance = null;

	/**
	 * Is not allowed to call from outside to prevent from creating multiple instances.
	 */
	private function __construct() {
		$this->status = new Webhook_Meta_Status();
	}

	/**
	 * Prevent the instance from being cloned.
	 */
	private function __clone() {
	}

	/**
	 * Prevent from being unserialized.
	 *
	 * @throws Exception If create a second instance of it.
	 */
	public function __wakeup() {
		throw new Exception( esc_html__( 'Cannot unserialize singleton', 'woo-asaas' ) );
	}

	/**
	 * Return an instance of this class
	 *
	 * @return self A single instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Check the status of the connection and display a warning message if needed.
	 *
	 * @return void
	 */
	public function show_notice_status() {
		$connection_status = $this->status->get_connection_status();

		switch ( $connection_status ) {
			case false:
				$this->show_notice_status_connection();
				$this->show_notice_invalid_api_key();
				break;
			case true:
				$this->show_notice_status_queue();
				break;
		}
	}

	/**
	 * Check the status of the connection and display a warning message if needed.
	 */
	private function show_notice_status_connection() {
		$dashboard_page = 'dashboard' === $this->get_screen()->id;
		$orders_page    = 'shop_order' === $this->get_screen()->post_type;

		if ( $dashboard_page || $orders_page ) {
			// translators: %s: URL to the gateway settings page.
			$message = sprintf( __( 'We have identified issues with the connection of your store to the Asaas Payment Method, caused by an invalid or missing API key. <a href="%s">Click here to provide a new key</a>.', 'woo-asaas' ), admin_url( 'admin.php?page=wc-settings&tab=checkout' ) );
			echo wp_kses_post( '<div class="notice notice-warning is-dismissible"><p>' . $message . '</p></div>' );
		}
	}

	/**
	 * Check the status of the webhooks queue and display a warning message if needed.
	 */
	private function show_notice_invalid_api_key() {
		$gateway = WC_Asaas::get_instance();

		$allowed_sections = array();
		foreach ( $gateway->get_gateways() as $gateway ) {
			$allowed_sections[] = $gateway->id;
		}

		if ( '' === $gateway->settings['api_key'] ) {
			return;
		}

		$section = filter_input( INPUT_GET, 'section', FILTER_SANITIZE_SPECIAL_CHARS );

		$settings_page = 'woocommerce_page_wc-settings' === $this->get_screen()->id;
		if ( $settings_page && ! empty( $section ) && in_array( $section, $allowed_sections, true ) ) {
			echo wp_kses_post( '<div class="notice notice-error is-dismissible"><p><strong>Webhook: </strong>' . __( 'The provided API key is invalid', 'woo-asaas' ) . '.</p></div>' );
		}
	}

	/**
	 * Check the status of the webhooks queue and display a warning message if needed.
	 */
	private function show_notice_status_queue() {
		$orders_page = 'shop_order' === $this->get_screen()->post_type;
		if ( $orders_page && ! $this->status->get_queue_status() ) {
			// translators: %s: URL to the system status page.
			$message = sprintf( __( 'We\'ve identified that the Asaas webhook queue is interrupted. <a href="%s">Click here to reactivate</a>', 'woo-asaas' ), admin_url( 'admin.php?page=wc-status&tab=status#webhook-status-section' ) );

			echo wp_kses_post( '<div class="notice notice-warning is-dismissible"><p>' . $message . '</p></div>' );
		}
	}

	/**
	 * Get the current screen
	 *
	 * @throws Exception If screen data cannot be loaded.
	 * @return WP_Screen
	 */
	private function get_screen(): WP_Screen {
		$screen = get_current_screen();
		if ( null === $screen ) {
			throw new Exception( esc_html__( 'Screen data cannot be loaded at this point in the request', 'woo-asaas' ) );
		}

		return $screen;
	}
}
