<?php

namespace WC_Asaas\Connectivity\Data;

class Webhook {
	/**
	 * Webhook name
	 *
	 * @var string
	 */
	private $name;

	/**
	 * Webhook URL
	 *
	 * @var string
	 */
	private $url;

	/**
	 * Email for notifications
	 *
	 * @var string
	 */
	private $email;

	/**
	 * Type of sending
	 *
	 * @var string
	 */
	private $send_type;

	/**
	 * Webhook enabled status
	 *
	 * @var bool
	 */
	private $enabled;

	/**
	 * Webhook interrupted status
	 *
	 * @var bool
	 */
	private $interrupted;

	/**
	 * Authentication token
	 *
	 * @var string
	 */
	private $auth_token;

	/**
	 * Webhook events
	 *
	 * @var array
	 */
	private $events;

	public function __construct( string $name, string $url, string $email, string $send_type, bool $enabled, bool $interrupted, string $auth_token, array $events ) {
		$this->name        = $name;
		$this->url         = $url;
		$this->email       = $email;
		$this->send_type   = $send_type;
		$this->enabled     = $enabled;
		$this->interrupted = $interrupted;
		$this->auth_token  = $auth_token;
		$this->events      = $events;
	}

	public function name() {
		return $this->name;
	}

	public function url() {
		return $this->url;
	}

	public function email() {
		return $this->email;
	}

	public function send_type() {
		return $this->send_type;
	}

	public function is_enabled() {
		return $this->enabled;
	}

	public function is_interrupted() {
		return $this->interrupted;
	}

	public function auth_token() {
		return $this->auth_token;
	}

	public function events() {
		return $this->events;
	}
}
