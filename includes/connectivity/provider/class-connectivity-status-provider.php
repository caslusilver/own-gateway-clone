<?php

namespace WC_Asaas\Connectivity\Provider;

use WC_Asaas\Connectivity\Adapter\Connectivity_Status_Adapter;
use WC_Asaas\Webhook\Meta\Webhook_Meta_Status;

class Connectivity_Status_Provider {

	public function connectivity_status_manager() {
		$connectivity_status_object = new Webhook_Meta_Status();

		return new Connectivity_Status_Adapter( $connectivity_status_object );
	}
}
