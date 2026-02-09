<?php

namespace WC_Asaas\Connectivity\Data;

class Registered_Webhook {
	/**
	 * Webhook id.
	 *
	 * @var string
	 */
	private $id;
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

	public function __construct( string $id, bool $enabled, bool $interrupted ) {
		$this->id          = $id;
		$this->enabled     = $enabled;
		$this->interrupted = $interrupted;

	}

	public function id() {
		return $this->id;
	}

	public function is_enabled() {
		return $this->enabled;
	}

	public function is_interrupted() {
		return $this->interrupted;
	}
}
