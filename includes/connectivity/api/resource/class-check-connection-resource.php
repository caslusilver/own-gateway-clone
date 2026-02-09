<?php
// phpcs:ignore PHPCompatibility.Keywords.ForbiddenNamesAsDeclared.resourceFound, PHPCompatibility.Keywords.ForbiddenNames.resourceFound
namespace WC_Asaas\Connectivity\API\Resource;

use WC_Asaas\Api\Response\Error_Response;
use WC_Asaas\Connectivity\Exception\API_Error_Response_Exception;


class Check_Connection_Resource {
	/**
	 * Webhook resource.
	 *
	 * @var Webhook_Resource
	 */
	private $resource;

	public function __construct() {
		$this->resource = new Webhook_Resource();
	}

	public function check_connection() {
		$response = $this->resource->exists( '' );
		if ( $response instanceof Error_Response ) {
			throw new API_Error_Response_Exception( $response->get_errors()->get_error_message(), $response->code );
		}
	}
}
