<?php

namespace WC_Asaas\Anticipation;

use WC_Asaas\Anticipation\Hook\Anticipation_Ajax;
use WC_Asaas\Anticipation\Meta\Anticipation_Meta;
use WC_Asaas\Api\Api;
use WC_Asaas\WC_Asaas;

class Anticipation {
	const ALLOWED_GATEWAY = 'asaas-credit-card';

	public function __construct() {
		$gateway           = WC_Asaas::get_instance()->get_gateway_by_id( self::ALLOWED_GATEWAY );
		$api               = new Api( $gateway );
		$anticipation_meta = new Anticipation_Meta();

		new Anticipation_Ajax( $api, $anticipation_meta );
	}
}
