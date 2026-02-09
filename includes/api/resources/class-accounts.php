<?php
/**
 * API '/accounts' resource.
 *
 * @package WooAsaas
 */

namespace WC_Asaas\Api\Resources;

use WC_Asaas\Api\Response\Response;
use WC_Asaas\Api\Client\Client;

/**
 * API '/myAccount' resource.
 */
class Accounts extends Resource {
	/**
	 * Resource path.
	 *
	 * @var string
	 */
	const PATH = '/myAccount/commercialInfo/';

	/**
	 * Anticipation person type request
	 *
	 * @return Response The HTTP response.
	 */
	public function commercial_info() {
		$client = new Client( $this->gateway );
		return $client->get( self::PATH );
	}
}
