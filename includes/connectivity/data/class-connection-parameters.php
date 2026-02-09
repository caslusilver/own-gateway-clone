<?php

namespace WC_Asaas\Connectivity\Data;

use WC_Asaas\Connectivity\Validator\Connection_Parameters_Validator;

class Connection_Parameters {
	private $url;
	private $api_key;

	public function __construct( string $url, string $api_key ) {
		$this->url     = $url;
		$this->api_key = $api_key;
		$this->validate_data();
	}

	public function validate_data() {
		( new Connection_Parameters_Validator() )->validate( $this );
	}

	public function url() {
		return $this->url;
	}

	public function api_key() {
		return $this->api_key;
	}
}
