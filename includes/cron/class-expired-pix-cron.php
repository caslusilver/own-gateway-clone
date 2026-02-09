<?php
/**
 * Credit Card class
 *
 * @package WooAsaas
 */

namespace WC_Asaas\Cron;

use Exception;
use WC_Asaas\Gateway\Pix;
use WC_Asaas\Meta_Data\Order;
use WC_Order;

/**
 * Handle checkout installments.
 */
class Expired_Pix_Cron {

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
	 * Create a custom event to run when new order was created.
	 *
	 * Event to remove overdue Pix.
	 *
	 * @param int $order_id The order ID.
	 */
	public function schedule_remove_expired_pix( $order_id ) {
		$pix                = new Pix();
		$expiration_setting = $pix->expiration_settings();

		if ( '' === $expiration_setting ) {
			return;
		}

		$order                = new Order( $order_id );
		$wc_order             = $order->get_wc();
		$order_payment_method = $wc_order->get_payment_method();

		if ( $pix->id !== $order_payment_method ) {
			return;
		}

		$due_time  = $pix->create_due_date();
		$run_event = $due_time->getTimestamp();

		wp_schedule_single_event( $run_event, 'remove_expired_pix_asaas', array( $order_id ) );
	}

	/**
	 * Execute the call to remove overdue Pix.
	 *
	 * @param int|WC_Order $order The order ID. WC_Order for legacy cron events.
	 * @return void
	 */
	public function execute_remove_expired_pix( $order ) {
		if ( is_int( $order ) ) {
			$order = wc_get_order( $order );
		}

		if ( ! $order instanceof WC_Order ) {
			return;
		}

		$pix = new Pix();
		$pix->remove_expired_pix( $order );
	}
}
