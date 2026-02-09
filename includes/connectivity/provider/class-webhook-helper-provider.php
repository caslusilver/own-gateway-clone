<?php

namespace WC_Asaas\Connectivity\Provider;

use WC_Asaas\Connectivity\Adapter\Webhook_Helper_Adapter;
use WC_Asaas\Helper\Webhook_Helper;

class Webhook_Helper_Provider {
	public function webhook_helper() {
		$webhook_helper = new Webhook_Helper();

		return new Webhook_Helper_Adapter( $webhook_helper );
	}
}
