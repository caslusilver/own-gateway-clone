<?php

namespace WC_Asaas\Connectivity\Adapter;

use WC_Asaas\Connectivity\Contract\Gateway_Interface;
use WC_Asaas\Gateway\Gateway;

class Gateway_Adapter implements Gateway_Interface {

	/**
	 * The gateway that will call the API
	 *
	 * @var Gateway
	 */
	private $gateway;

	public function __construct( Gateway $gateway ) {
		$this->gateway = $gateway;
	}

	public function get_setting( string $key ) {
		return $this->gateway->settings[ $key ];
	}

	public function get_option( string $key ) {
		return $this->gateway->get_option( $key );
	}

	public function get_api_key() {
		return $this->gateway->get_api_key();
	}

	public function get_logger() {
		return $this->gateway->get_logger();
	}
}
