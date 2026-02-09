<?php
/**
 * Pix clone gateway.
 *
 * @package WooAsaas
 */

namespace WC_Asaas\Gateway;

use WC_Asaas\Admin\Settings\Pix_Clone as Pix_Clone_Settings;
use WC_Asaas\Billing_Type\Pix as Pix_Type;
use WC_Asaas\Connectivity\Service\Webhook_Invoices_Service;
use WC_Asaas\Meta_Data\Order;
use WC_Asaas\WC_Asaas;
use WC_Order;

class Pix_Clone extends Gateway {
	/**
	 * Init the gateway.
	 */
	public function __construct() {
		$this->id           = 'asaas-pix-clone';
		$this->has_fields   = true;
		$this->method_title = __( 'Asaas Pix (Clone)', 'woo-asaas' );
		$this->method_description = __( 'Pix via webhook (clone do Asaas).', 'woo-asaas' );

		$this->type = new Pix_Type();
		$this->init_logger();
		$this->admin_settings    = new Pix_Clone_Settings( $this );
		$this->validation_errors = new \WP_Error();

		parent::__construct();

		$this->supports = array(
			'products',
		);

		add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'append_html_to_thankyou_page' ) );
		add_action( 'woocommerce_receipt_' . $this->id, array( $this, 'append_html_to_thankyou_page' ) );
		add_action( 'woocommerce_view_order', array( $this, 'append_html_to_thankyou_page' ) );
	}

	/**
	 * Add Pix HTML to thankyou page.
	 *
	 * @param int $order_id WC Order id.
	 * @return void
	 */
	public function append_html_to_thankyou_page( $order_id ) {
		static $appended = false;

		if ( $appended ) {
			return;
		}

		$appended = true;

		$order = new Order( $order_id );

		if ( $this->id !== $order->get_wc()->get_payment_method() ) {
			return;
		}

		$expiration_settings = $this->expiration_settings();

		$data = array(
			'order'               => $order,
			'show_copy_and_paste' => $this->show_copy_and_paste(),
			'expiration_time'     => $this->expiration_time( $expiration_settings ),
			'expiration_period'   => $this->expiration_period( $expiration_settings ),
		);

		WC_Asaas::get_instance()->get_template_file( 'order/pix-thankyou.php', $data );
	}

	/**
	 * Check if the copy and paste code should be displayed.
	 *
	 * @return bool
	 */
	private function show_copy_and_paste() : bool {
		if ( 'no' === $this->settings['copy_and_paste'] ) {
			return false;
		}

		return true;
	}

	/**
	 * Process a Pix order using webhook.
	 *
	 * @param int $order_id WC Order id.
	 * @return array|null
	 */
	public function process_payment( $order_id ) {
		$order    = new Order( $order_id );
		$wc_order = $order->get_wc();

		if ( is_wc_endpoint_url( 'order-pay' ) || false !== $this->get_payment_id_from_order( $wc_order ) ) {
			return array(
				'result'   => 'success',
				'redirect' => $this->get_return_url( $wc_order ),
			);
		}

		$payload  = $this->build_invoice_payload( $wc_order );
		$service  = new Webhook_Invoices_Service();
		// Chamada server-side para proteger a chave do webhook.
		$response = $service->create_invoice( $payload, $this );

		if ( is_wp_error( $response ) ) {
			$this->send_checkout_failure( $response->get_error_messages() );
			return;
		}

		$normalized = $this->normalize_webhook_response( $response );
		if ( is_wp_error( $normalized ) ) {
			$this->send_checkout_failure( $normalized->get_error_messages() );
			return;
		}

		$order->set_meta_data( $normalized );

		$payment_id = $this->extract_payment_id( $normalized );
		if ( '' !== $payment_id ) {
			$this->add_payment_id_to_order( $payment_id, $wc_order );
		}

		$total = $wc_order->get_total();
		if ( 0 >= $total ) {
			$order->complete();
		} else {
			$this->awaiting_payment_status( $wc_order );
		}

		return array(
			'result'   => 'success',
			'redirect' => $this->get_return_url( $wc_order ),
		);
	}

	/**
	 * Build payload for webhook invoice creation.
	 *
	 * @param WC_Order $wc_order The WooCommerce order.
	 * @return array
	 */
	private function build_invoice_payload( WC_Order $wc_order ) {
		$items = array();
		foreach ( $wc_order->get_items() as $item ) {
			$product = $item->get_product();
			$items[] = array(
				'id'       => $item->get_product_id(),
				'name'     => $item->get_name(),
				'qty'      => $item->get_quantity(),
				'price'    => $product ? (float) $product->get_price() : 0,
				'subtotal' => (float) $item->get_subtotal(),
				'total'    => (float) $item->get_total(),
			);
		}

		return array(
			'source'    => 'woo-asaas-clone',
			'order_id'  => $wc_order->get_id(),
			'order_key' => $wc_order->get_order_key(),
			'currency'  => $wc_order->get_currency(),
			'total'     => (float) $wc_order->get_total(),
			'customer'  => array(
				'id'    => $wc_order->get_customer_id(),
				'name'  => trim( $wc_order->get_billing_first_name() . ' ' . $wc_order->get_billing_last_name() ),
				'email' => $wc_order->get_billing_email(),
				'phone' => $wc_order->get_billing_phone(),
				'cpf'   => $wc_order->get_meta( '_billing_cpf', true ),
				'cnpj'  => $wc_order->get_meta( '_billing_cnpj', true ),
			),
			'items'     => $items,
		);
	}

	/**
	 * Normalize webhook response to expected Pix fields.
	 *
	 * @param \stdClass $response The webhook response.
	 * @return \stdClass|\WP_Error
	 */
	private function normalize_webhook_response( $response ) {
		$encoded_image = property_exists( $response, 'encodedImage' ) ? $response->encodedImage : '';
		if ( '' === $encoded_image && property_exists( $response, 'encoded_image' ) ) {
			$encoded_image = $response->encoded_image;
		}

		$payload = property_exists( $response, 'payload' ) ? $response->payload : '';
		if ( '' === $payload && property_exists( $response, 'pixPayload' ) ) {
			$payload = $response->pixPayload;
		}

		if ( '' === $encoded_image || '' === $payload ) {
			return new \WP_Error(
				'asaas_clone_missing_pix_fields',
				__( 'Resposta do webhook sem encodedImage/payload.', 'woo-asaas' )
			);
		}

		$response->encodedImage = $encoded_image;
		$response->payload      = $payload;
		$response->billingType  = $this->type->get_id();
		if ( ! property_exists( $response, 'status' ) ) {
			$response->status = 'PENDING';
		}

		return $response;
	}

	/**
	 * Extract payment id from webhook response.
	 *
	 * @param \stdClass $response The normalized response.
	 * @return string
	 */
	private function extract_payment_id( $response ) {
		if ( property_exists( $response, 'id' ) ) {
			return (string) $response->id;
		}

		if ( property_exists( $response, 'payment_id' ) ) {
			return (string) $response->payment_id;
		}

		if ( property_exists( $response, 'paymentId' ) ) {
			return (string) $response->paymentId;
		}

		return '';
	}

	/**
	 * Override to avoid Asaas customer sync.
	 *
	 * @param int   $customer_id The WooCommerce customer id.
	 * @param array $data The checkout data.
	 * @return void
	 */
	public function set_customer( $customer_id, $data ) {
		return;
	}

	/**
	 * Override notifications to avoid Asaas API usage.
	 *
	 * @param string $old_value The old value.
	 * @param string $value The new value.
	 * @param string $option The option name.
	 * @return void
	 */
	public function enable_disable_customer_notifications( $old_value, $value, $option ) {
		return;
	}

	/**
	 * Override to avoid Asaas API usage.
	 *
	 * @param int $order_id Order ID.
	 * @param float $amount Refund amount.
	 * @param string $reason Refund reason.
	 * @return \WP_Error
	 */
	public function process_refund( $order_id, $amount = null, $reason = '' ) {
		return new \WP_Error( 'error', __( 'Refunds devem ser feitos manualmente no fluxo de contingência.', 'woo-asaas' ) );
	}

	/**
	 * Override to avoid Asaas API usage.
	 *
	 * @param WC_Order $order The order.
	 * @return void
	 */
	public function remove_expired_pix( $order ) {
		return;
	}

	/**
	 * Override to avoid Asaas API usage.
	 *
	 * @param int $order_id WC Order id.
	 * @return bool
	 */
	public function process_transactions_rollback( $order_id ) {
		return false;
	}

	/**
	 * Create due date using local expiration rules.
	 *
	 * @param string $reference_date (Optional) The reference date to calculate the due date.
	 * @param bool   $ignore_validity_days (Optional) Flag to ignore the sum of validity days.
	 * @return \DateTime The due date.
	 */
	public function create_due_date( $reference_date = 'now', $ignore_validity_days = false ) {
		$expiration_period = '0d';
		if ( false === $ignore_validity_days ) {
			$expiration_settings = $this->expiration_settings();
			$expiration_time     = $this->expiration_time( $expiration_settings );
			$expiration_period   = $this->expiration_period( $expiration_settings );
		}

		return new \DateTime( $reference_date . sprintf( '+ %d %s', $expiration_time, $expiration_period ), wp_timezone() );
	}

	/**
	 * Get expiration setting.
	 *
	 * @return string
	 */
	public function expiration_settings() {
		$expiration = $this->admin_settings->get_default_pix_validity_days();
		if ( isset( $this->settings['validity_days'] ) ) {
			$expiration = $this->settings['validity_days'];
		}

		return $expiration;
	}

	/**
	 * Get expiration period.
	 *
	 * @param string $expiration_settings The pix expiration time settings.
	 * @return string
	 */
	private function expiration_period( string $expiration_settings ) {
		$valid_period = array(
			'm' => 'minute',
			'h' => 'hour',
			'd' => 'day',
		);
		$period       = substr( $expiration_settings, -1 );

		if ( false === array_key_exists( $period, $valid_period ) ) {
			$period = 'd';
		}

		$period = strtr( $period, $valid_period );

		return $period;
	}

	/**
	 * Get expiration time.
	 *
	 * @param string $expiration_settings The pix expiration time settings.
	 * @return int
	 */
	private function expiration_time( string $expiration_settings ) {
		$valid_period     = array(
			'm' => 'minute',
			'h' => 'hour',
			'd' => 'day',
		);
		$period           = substr( $expiration_settings, -1 );
		$expiration_value = intval( substr( $expiration_settings, 0, -1 ) );

		if ( false === array_key_exists( $period, $valid_period ) ) {
			$expiration_value = intval( $expiration_settings );
		}

		return $expiration_value;
	}

	/**
	 * Adds clone payment id to order meta.
	 *
	 * @param string   $payment_id Payment id.
	 * @param WC_Order $order The checkout order.
	 * @return void
	 */
	public function add_payment_id_to_order( $payment_id, $order ) {
		$order->add_meta_data( '_asaas_clone_id', $payment_id );
		$order->save();
	}

	/**
	 * Gets clone payment id from order meta.
	 *
	 * @param WC_Order $order The order.
	 * @return string|false
	 */
	public function get_payment_id_from_order( $order ) {
		$payment_id = $order->get_meta( '_asaas_clone_id', true );
		return '' !== $payment_id ? $payment_id : false;
	}

	/**
	 * Gateway prefix for utilities.
	 *
	 * @return string
	 */
	public function prefix() : string {
		return 'pix-clone';
	}
}
