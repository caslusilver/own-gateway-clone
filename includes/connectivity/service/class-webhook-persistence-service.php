<?php

/**
 * Handles the persistence and management of webhook settings and operations.
 *
 * @package WooAsaas
 */

namespace WC_Asaas\Connectivity\Service;

use WC_Asaas\Connectivity\API\Resource\Webhook_Resource;
use WC_Asaas\Connectivity\Data\Registered_Webhook;
use WC_Asaas\Connectivity\Data\Webhook_Factory;
use WC_Asaas\Connectivity\Provider\Webhook_Provider;

/**
 * Webhook persistence service.
 */
class Webhook_Persistence_Service {
	/**
	 * WooCommerce persistence service.
	 *
	 * @var Woocommerce_Persistence_Service
	 */
	private $woocommerce_persistence_service;

	public function __construct() {
		$this->woocommerce_persistence_service = new Woocommerce_Persistence_Service();
	}

	public function retrieve_existent_webhook() {
		$webhook          = ( new Webhook_Factory() )->create_webhook_with_woocommerce_data();
		$existent_webhook = ( new Webhook_Resource() )->existent_webhook( $webhook->url() );

		return $existent_webhook;
	}

	public function create_webhook() {

		$new_webhook = ( new Webhook_Factory() )->create_webhook_with_woocommerce_data();

		$this->woocommerce_persistence_service->update_access_token( $new_webhook->auth_token() );

		$webhook = ( new Webhook_Resource() )->create( $new_webhook );

		$this->woocommerce_persistence_service->update_webhook_connectivity_status( true );

		return $webhook;
	}

	public function reenable_webhook( Registered_Webhook $webhook ) {
		$updatable_webhook = ( new Webhook_Factory() )->create_updatable_webhook( $webhook );

		$reenabled_webhook = ( new Webhook_Resource() )->reenable_webhook( $updatable_webhook );

		$this->woocommerce_persistence_service->update_access_token( $updatable_webhook->auth_token() );

		$this->woocommerce_persistence_service->update_webhook_connectivity_status( true );

		return $reenabled_webhook;
	}

	public function update_webhook_email( Registered_Webhook $webhook ) {
		$updatable_webhook = ( new Webhook_Factory() )->create_updatable_webhook( $webhook );

		$updated_webhook = ( new Webhook_Resource() )->update_webhook_email( $updatable_webhook );

		return $updated_webhook;
	}
}
