<?php

namespace WC_Asaas\Connectivity\Provider;

use WC_Asaas\Connectivity\Adapter\Gateway_Adapter;
use WC_Asaas\WC_Asaas;

class Gateway_Provider {
	public function gateway() {
		$gateway = WC_Asaas::get_instance()->get_gateway_by_id( 'asaas-ticket' );

		return new Gateway_Adapter( $gateway );
	}

	public function all_gateways() {
		return WC_Asaas::get_instance()->get_gateways();
	}
}
