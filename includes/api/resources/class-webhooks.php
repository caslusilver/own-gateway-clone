<?php
/**
 * API '/webhooks' resource.
 *
 * @package WooAsaas
 */

namespace WC_Asaas\Api\Resources;

use WC_Asaas\Api\Client\Collection_Client;
use WC_Asaas\Api\Response\Response;
use WC_Asaas\Api\Client\Client;
use WC_Asaas\Helper\Webhook_Helper;

/**
 * API '/webhooks' resource.
 */
class Webhooks extends Resource {

	/**
	 * Resource path.
	 *
	 * @var string
	 */
	const PATH = '/webhooks/';

	/**
	 * Create a newly webhook configuration
	 *
	 * @param  array $data Request body.
	 * @return Response The HTTP response.
	 */
	public function create( $data ) {
		$client = new Client( $this->gateway );
		return $client->post( self::PATH, $data, array( $this, 'filter_data_log' ) );
	}

	/**
	 * Check if has webhook route by its url
	 *
	 * @param  string $url webhook configuration url.
	 * @return Response The HTTP response.
	 */
	public function exists( $url ) {
		$client = new Collection_Client( $this->gateway );
		$data   = array();
		return $client->get( self::PATH . "?url=$url", $data, array( $this, 'filter_data_log' ) );
	}

	/**
	 * Retrieve webhook configurations list
	 *
	 * @return Response The HTTP response.
	 */
	public function list() {
		$client = new Client( $this->gateway );
		return $client->get( self::PATH );
	}

	/**
	 * Find a webhook configuration by its id
	 *
	 * @param  int $id webhook configuration id.
	 * @return Response The HTTP response.
	 */
	public function find( $id ) {
		$client = new Client( $this->gateway );
		return $client->get( self::PATH . $id );
	}

	/**
	 * Delete a webhook configuration by id
	 *
	 * @param  int $id Subscription id.
	 * @return Response The HTTP response.
	 */
	public function remove( $id ) {
		$client = new Client( $this->gateway );
		return $client->delete( self::PATH . $id );
	}

	/**
	 * Update a webhook configuration by id
	 *
	 * @param  int   $id webhook configuration id.
	 * @param  array $data Request body.
	 * @return Response The HTTP response.
	 */
	public function update( $id, $data ) {
		$client = new Client( $this->gateway );
		return $client->put( self::PATH . $id, $data, array( $this, 'filter_data_log' ) );
	}

	/**
	 * Remove sensitive authToken to not be stored in log
	 *
	 * @param string|\stdClass $data The data to be stored.
	 * @return string|false The data encoded on string.
	 */
	public function filter_data_log( $data ) {
		if ( is_string( $data ) ) {
			$data = json_decode( $data, true );
		}

		if ( is_null( $data ) ) {
			return $data;
		}

		( new Webhook_Helper() )->masked_auth_token( $data );

		return wp_json_encode( $data );
	}
}
