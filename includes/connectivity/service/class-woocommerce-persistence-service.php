<?php

namespace WC_Asaas\Connectivity\Service;

use WC_Asaas\Connectivity\Provider\Connectivity_Status_Provider;
use WC_Asaas\Connectivity\Provider\Gateway_Provider;

class Woocommerce_Persistence_Service {
	public function update_access_token( string $token ) {
		$gateways = ( new Gateway_Provider() )->all_gateways();

		foreach ( $gateways as $gateway ) {
			$gateway->update_option( 'webhook_access_token', $token );
		}
	}
	public function update_webhook_connectivity_status( bool $status ) {
		$status_manager = ( new Connectivity_Status_Provider() )->connectivity_status_manager();

		$status_manager->set_status( $status_manager->get_connection_status(), $status );
	}
}
