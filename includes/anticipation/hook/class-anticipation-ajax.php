<?php
/**
 * File for class Anticipation Ajax
 *
 * @package WooAsaas
 */

namespace WC_Asaas\Anticipation\Hook;

use WC_Asaas\Anticipation\Meta\Anticipation_Meta;
use WC_Asaas\Api\Api;
use WC_Asaas\Gateway\Gateway;

/**
 * Anticipation Ajax
 */
class Anticipation_Ajax {
	/**
	 * The gateway that will call the API
	 *
	 * @var Gateway
	 */
	protected $gateway;

	/**
	 * Asaas API wrapper.
	 *
	 * @var Api
	 */
	protected $api;

	/**
	 * Anticipation Meta
	 *
	 * @var Anticipation_Meta
	 */
	protected $option_meta;

	/**
	 * Person type
	 *
	 * @var string
	 */
	const ALLOWED_PERSON_TYPE = 'JURIDICA';

	/**
	 * Initialize the object
	 *
	 * @param Api $api The API object.
	 * @param Anticipation_Meta $option_meta The anticipation options handler.
	 */
	public function __construct( $api, $option_meta ) {
		$this->api         = $api;
		$this->option_meta = $option_meta;

		add_action( 'wp_ajax_check_anticipation_option', array( $this, 'check_anticipation_option' ) );
		add_action( 'wp_ajax_check_anticipation_allowed', array( $this, 'check_anticipation_allowed' ) );
		add_action( 'wp_ajax_check_anticipation_allowed_person_type', array( $this, 'check_anticipation_allowed_person_type' ) );
	}

	/**
	 * Check if the option is enabled or not and update the option in the database.
	 *
	 * @return void
	 */
	public function check_anticipation_option() {
		if ( isset( $_POST['_nonce'] ) && ! wp_verify_nonce( sanitize_key( $_POST['_nonce'] ), 'own-gateway-clone-admin-nonce' ) ) {
			wp_send_json_error( array( 'error' => __( 'Falha na verificacao do nonce', 'checkout-tabs-wp-ml' ) ) );
		}

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_send_json_error( array( 'error' => __( 'Permissao negada', 'checkout-tabs-wp-ml' ) ) );
		}

		$this->option_meta->update_anticipation_payment_option();
		wp_send_json_success( array( 'enabled' => true ) );
	}

	/**
	 * Check if anticipation is allowed and send JSON response accordingly.
	 *
	 * @return void
	 */
	public function check_anticipation_allowed() {
		$data = $this->option_meta->get_anticipation_payment_option();

		if ( ! $data ) {
			wp_send_json_error( array( 'allowAnticipation' => false ) );
		}

		wp_send_json_success( $data );
	}

	/**
	 * Check if the person type is allowed or not and update the option in the database.
	 *
	 * @return void
	 */
	public function check_anticipation_allowed_person_type() {
		if ( isset( $_POST['_nonce'] ) && ! wp_verify_nonce( sanitize_key( $_POST['_nonce'] ), 'own-gateway-clone-admin-nonce' ) ) {
			wp_send_json_error( array( 'error' => __( 'Falha na verificacao do nonce', 'checkout-tabs-wp-ml' ) ) );
		}

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_send_json_error( array( 'error' => __( 'Permissao negada', 'checkout-tabs-wp-ml' ) ) );
		}

		$response = $this->api->accounts()->commercial_info();

		if ( 200 !== $response->code ) {
			wp_send_json_error( $response, $response->code );
		}

		if ( 401 === $response->code ) {
			wp_send_json_error( $response, $response->code );
		}

		$response_json = $response->get_json();

		if ( self::ALLOWED_PERSON_TYPE === $response_json->personType ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar
			$this->option_meta->update_anticipation_payment_option();
		} else {
			$this->option_meta->update_anticipation_payment_option( false );
		}

		$data = array( 'personType' => $response_json->personType ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar
		wp_send_json_success( $data, $response->code );
	}
}
