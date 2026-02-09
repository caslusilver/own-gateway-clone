<?php
/**
 * Anticipation Settings Status class
 *
 * @package WooAsaas
 */

namespace WC_Asaas\Anticipation\Admin\Settings;

use Exception;
use WP_Screen;
use WC_Asaas\Anticipation\Meta\Anticipation_Meta;

/**
 * Anticipation Settings Status class
 */
class Anticipation_Settings_Status {
	/**
	 * Instance of this class
	 *
	 * @var self
	 */
	protected static $instance = null;

	/**
	 * Anticipation Meta
	 *
	 * @var Anticipation_Meta
	 */
	protected $option_meta;

	/**
	 * Is not allowed to call from outside to prevent from creating multiple instances.
	 */
	private function __construct() {
		$this->option_meta = new Anticipation_Meta();
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
	 * Show a notice if the person type is not allowed anticipate payment
	 *
	 * @return void
	 */
	public function show_notice_person_type() {
		$section       = filter_input( INPUT_GET, 'section', FILTER_SANITIZE_SPECIAL_CHARS );
		$settings_page = 'woocommerce_page_wc-settings' === $this->get_screen()->id;

		if ( ! $settings_page ) {
			return;
		}

		$anticipation_payment_option = $this->option_meta->get_anticipation_payment_option();

		if ( ! $anticipation_payment_option || ! isset( $anticipation_payment_option['allowAnticipation'] ) ) {
			return;
		}

		$allow_payment = $anticipation_payment_option['allowAnticipation'];

		if ( ! $allow_payment && ! empty( $section ) && 'asaas-credit-card' === $section ) {
			$this->show_notice_not_allowed_person_type();
			$this->option_meta->delete_anticipation_payment_option();
		}
	}

	/**
	 * Check the status of the connection and display a warning message if needed.
	 *
	 * @return void
	 */
	private function show_notice_not_allowed_person_type() {
		echo wp_kses_post( '<div class="notice notice-warning is-dismissible"><p><strong>' . __( 'Anticipation', 'woo-asaas' ) . ': </strong>' . __( 'The automatic anticipation feature is currently available only for business accounts', 'woo-asaas' ) . '.</p></div>' );
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
