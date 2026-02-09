<?php
/**
 * API '/anticipations' resource.
 *
 * @package WooAsaas
 */

namespace WC_Asaas\Api\Resources;

use WC_Asaas\Api\Response\Response;
use WC_Asaas\Api\Client\Client;

/**
 * API '/anticipations' resource.
 */
class Anticipations extends Resource {

	/**
	 * Resource path.
	 *
	 * @var string
	 */
	const PATH = '/anticipations/';

	/**
	 * Anticipation request
	 *
	 * @param  array $data Request body.
	 * @return Response The HTTP response.
	 */
	public function request( $data ) {
		$client = new Client( $this->gateway );
		return $client->post( self::PATH, $data );
	}
}
