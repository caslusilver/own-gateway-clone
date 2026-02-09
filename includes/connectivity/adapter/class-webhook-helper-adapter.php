<?php

namespace WC_Asaas\Connectivity\Adapter;

use WC_Asaas\Connectivity\Contract\Webhook_Helper_Interface;
use WC_Asaas\Helper\Webhook_Helper;

class Webhook_Helper_Adapter implements Webhook_Helper_Interface {

	private $webhook_helper;

	public function __construct( Webhook_Helper $webhook_helper ) {
		$this->webhook_helper = $webhook_helper;
	}

	public function generate_random_token(): string {
		return $this->webhook_helper->generate_random_token();
	}
}
