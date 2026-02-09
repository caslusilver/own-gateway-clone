<?php

namespace WC_Asaas\Connectivity\Adapter;

use WC_Asaas\Connectivity\Data\Registered_Webhook;

class Reenabled_Webhook_To_Resource_Request_Adapter {
	/**
	 * Updatable webhook with auth token.
	 *
	 * @var Registered_Webhook
	 */
	private $webhook;

	public function __construct( Registered_Webhook $webhook ) {
		$this->webhook = $webhook;
	}

	public function adapt() {
		return [
			'enabled'     => $this->webhook->is_enabled(),
			'interrupted' => $this->webhook->is_interrupted(),
			'authToken'  => $this->webhook->auth_token(),
		];
	}
}
