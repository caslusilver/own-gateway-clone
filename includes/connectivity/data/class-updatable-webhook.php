<?php

namespace WC_Asaas\Connectivity\Data;

class Updatable_Webhook extends Registered_Webhook {
	/**
	 * Webhook new email
	 *
	 * @var string
	 */
	private $email;
	/**
	 * Webhook new auth token
	 *
	 * @var string
	 */
	private $auth_token;

	public function __construct( string $id, bool $enabled, bool $interrupted, string $email, string $auth_token ) {
		$this->email      = $email;
		$this->auth_token = $auth_token;

		parent::__construct( $id, $enabled, $interrupted );
	}

	public function email() {
		return $this->email;
	}

	public function auth_token() {
		return $this->auth_token;
	}
}
