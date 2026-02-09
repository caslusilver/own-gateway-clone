<?php
/**
 * File for class Webhook Ajax
 *
 * @package WooAsaas
 */

namespace WC_Asaas\Connectivity\Hook;

use Exception;
use WC_Asaas\Connectivity\Adapter\Webhook_Data_To_Json_Response_Adapter;
use WC_Asaas\Connectivity\Service\Webhook_Persistence_Service;
use WC_Asaas\Connectivity\Service\Woocommerce_Persistence_Service;

class Webhook_Connection_Ajax extends Connection_Ajax {
	/**
	 * Webhook persistence service
	 *
	 * @var Webhook_Persistence_Service
	 */
	private $webhook_persistence_service;
	/**
	 * WooCommerce Persistence Service
	 *
	 * @var Woocommerce_Persistence_Service
	 */
	private $woocommerce_persistence_service;

	public function __construct() {
		$this->webhook_persistence_service     = new Webhook_Persistence_Service();
		$this->woocommerce_persistence_service = new Woocommerce_Persistence_Service();

		add_action( 'wp_ajax_check_webhook_status', array( $this, 'check_webhook_status' ) );
		add_action( 'wp_ajax_webhook_health_check', array( $this, 'webhook_health_check' ) );
		add_action( 'wp_ajax_reenable_webhook_queue', array( $this, 'reenable_webhook_queue' ) );
		add_action( 'wp_ajax_update_existing_webhook_email', array( $this, 'update_existing_webhook_email' ) );
	}

	/**
	 * Check existing webhook on gateway settings page
	 */
	public function check_webhook_status() {
		try {
			$this->use_requested_connection_parameters();
		} catch ( Exception $e ) {
			wp_send_json_error( $e->getMessage(), $e->getCode() );
		}

		try {
			$this->check_existent_webhook_status();
		} catch ( Exception $e ) {
			$this->create_webhook();
		}
	}

	public function webhook_health_check() {
		try {
			$this->check_existent_webhook_status();
		} catch ( Exception $e ) {
			$this->create_webhook();
		}
	}

	public function check_existent_webhook_status() {
		$existent_webhook = $this->webhook_persistence_service->retrieve_existent_webhook();
		$queue_status     = $existent_webhook->is_enabled() && ! $existent_webhook->is_interrupted();

		$this->woocommerce_persistence_service->update_webhook_connectivity_status( $queue_status );

		$json_response = ( new Webhook_Data_To_Json_Response_Adapter( $existent_webhook ) )->adapt();

		wp_send_json_success( $json_response );
	}

	public function create_webhook() {
		try {
			$webhook = $this->webhook_persistence_service->create_webhook();

			$json_response = ( new Webhook_Data_To_Json_Response_Adapter( $webhook ) )->adapt();

			wp_send_json_success( $json_response );
		} catch ( Exception $e ) {
			$this->woocommerce_persistence_service->update_webhook_connectivity_status( false );

			wp_send_json_error( $e->getMessage(), $e->getCode() );
		}
	}

	public function reenable_webhook_queue() {

		try {
			$webhook           = $this->webhook_persistence_service->retrieve_existent_webhook();
			$reenabled_webhook = $this->webhook_persistence_service->reenable_webhook( $webhook );

			$json_response = ( new Webhook_Data_To_Json_Response_Adapter( $reenabled_webhook ) )->adapt();

			wp_send_json_success( $json_response );
		} catch ( Exception $e ) {
			$this->woocommerce_persistence_service->update_webhook_connectivity_status( false );

			wp_send_json_error( $e->getMessage(), $e->getCode() );
		}
	}

	public function update_existing_webhook_email() {
		try {
			$webhook         = $this->webhook_persistence_service->retrieve_existent_webhook();
			$updated_webhook = $this->webhook_persistence_service->update_webhook_email( $webhook );

			$json_response = ( new Webhook_Data_To_Json_Response_Adapter( $updated_webhook ) )->adapt();

			wp_send_json_success( $json_response );
		} catch ( Exception $e ) {
			wp_send_json_error( $e->getMessage(), $e->getCode() );
		}
	}
}
