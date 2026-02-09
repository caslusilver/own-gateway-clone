<?php
// phpcs:ignore PHPCompatibility.Keywords.ForbiddenNamesAsDeclared.resourceFound, PHPCompatibility.Keywords.ForbiddenNames.resourceFound
namespace WC_Asaas\Connectivity\API\Resource;

use WC_Asaas\Api\Api;
use WC_Asaas\Api\Resources\Webhooks;
use WC_Asaas\Api\Response\Collection_Response;
use WC_Asaas\Api\Response\Error_Response;
use WC_Asaas\Connectivity\Adapter\New_Webhook_To_Resource_Request_Adapter;
use WC_Asaas\Connectivity\Adapter\Reenabled_Webhook_To_Resource_Request_Adapter;
use WC_Asaas\Connectivity\Data\Updatable_Registered_Webhook;
use WC_Asaas\Connectivity\Data\Updatable_Webhook;
use WC_Asaas\Connectivity\Data\Webhook;
use WC_Asaas\Connectivity\Data\Webhook_Factory;
use WC_Asaas\Connectivity\Exception\API_Error_Response_Exception;
use WC_Asaas\Connectivity\Provider\Gateway_Provider;

class Webhook_Resource {
	/**
	 * Webhook resource.
	 *
	 * @var Webhooks
	 */
	private $resource;

	public function __construct() {
		$api = new Api( ( new Gateway_Provider() )->gateway() );

		$this->resource = $api->webhooks();
	}

	public function exists( string $url ) {
		return $this->resource->exists( $url );
	}

	public function existent_webhook( string $url ) {
		$webhooks = $this->resource->exists( $url );

		if ( ! $webhooks instanceof Collection_Response ) {
			throw new API_Error_Response_Exception( 'Webhook not found' );
		}

		$webhooks = $webhooks->get_items();

		if ( count( $webhooks ) <= 0 ) {
			throw new API_Error_Response_Exception( 'Webhook not found' );
		}

		$webhook = reset( $webhooks );

		return ( ( new Webhook_Factory() )->create_from_api_response( $webhook ) );
	}

	public function create( Webhook $webhook ) {

		$data = ( new New_Webhook_To_Resource_Request_Adapter( $webhook ) )->adapt();

		$response = $this->resource->create( $data );

		if ( $response instanceof Error_Response ) {
			throw new API_Error_Response_Exception( $response->get_errors()->get_error_message(), $response->code );
		}

		$webhook = $response->get_json();

		return ( ( new Webhook_Factory() )->create_from_api_response( $webhook ) );
	}

	public function reenable_webhook( Updatable_Webhook $webhook ) {
		$data = ( new Reenabled_Webhook_To_Resource_Request_Adapter( $webhook ) )->adapt();

		$response = $this->resource->update( $webhook->id(), $data );

		if ( $response instanceof Error_Response ) {
			throw new API_Error_Response_Exception( $response->get_errors()->get_error_message(), $response->code );
		}

		$webhook = $response->get_json();

		return ( ( new Webhook_Factory() )->create_from_api_response( $webhook ) );

	}

	public function update_webhook_email( Updatable_Webhook $webhook ) {
		$data = [
			'email' => $webhook->email(),
		];

		$response = $this->resource->update( $webhook->id(), $data );

		if ( $response instanceof Error_Response ) {
			throw new API_Error_Response_Exception( $response->get_errors()->get_error_message(), $response->code );
		}

		$webhook = $response->get_json();

		return ( ( new Webhook_Factory() )->create_from_api_response( $webhook ) );
	}

}
