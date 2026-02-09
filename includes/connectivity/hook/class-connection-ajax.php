<?php

namespace WC_Asaas\Connectivity\Hook;

use WC_Asaas\Connectivity\Data\Connection_Parameters_Factory;
use WC_Asaas\Connectivity\Service\Request_Security_Validator_Service;

abstract class Connection_Ajax {

	protected function use_requested_connection_parameters() {
		( new Request_Security_Validator_Service() )->validate_security_parameters();

		$connection_parameters = ( new Connection_Parameters_Factory() )->create_from_requested_post_parameters();

		add_filter(
			'woocommerce_asaas_request_url', function () use ( $connection_parameters ) {
				return $connection_parameters->url();
			}
		);

		add_filter(
			'woocommerce_asaas_request_api_key', function () use ( $connection_parameters ) {
				return $connection_parameters->api_key();
			}
		);
	}
}
