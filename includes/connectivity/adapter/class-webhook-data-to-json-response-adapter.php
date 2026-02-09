<?php

namespace WC_Asaas\Connectivity\Adapter;

use WC_Asaas\Connectivity\Data\Registered_Webhook;

class Webhook_Data_To_Json_Response_Adapter {
	/**
	 * The webhook data.
	 *
	 * @var Registered_Webhook
	 */
	private $webhook;

	public function __construct( Registered_Webhook $webhook ) {
		$this->webhook = $webhook;
	}

	public function adapt() {
		return [
			'id'          => $this->webhook->id(),
			'enabled'     => $this->webhook->is_enabled(),
			'interrupted' => $this->webhook->is_interrupted(),
		];
	}
}
