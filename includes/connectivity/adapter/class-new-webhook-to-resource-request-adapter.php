<?php

namespace WC_Asaas\Connectivity\Adapter;

use WC_Asaas\Connectivity\Data\Updatable_Registered_Webhook;
use WC_Asaas\Connectivity\Data\Webhook;

class New_Webhook_To_Resource_Request_Adapter {
	/**
	 * The webhook data.
	 *
	 * @var Updatable_Registered_Webhook
	 */
	private $webhook;

	public function __construct( Webhook $webhook ) {
		$this->webhook = $webhook;
	}
	public function adapt() {
		return [
			'name'        => $this->webhook->name(),
			'url'         => $this->webhook->url(),
			'email'       => $this->webhook->email(),
			'sendType'    => $this->webhook->send_type(),
			'enabled'     => $this->webhook->is_enabled(),
			'interrupted' => $this->webhook->is_interrupted(),
			'authToken'   => $this->webhook->auth_token(),
			'events'      => $this->webhook->events(),
		];
	}
}
