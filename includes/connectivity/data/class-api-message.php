<?php

namespace WC_Asaas\Connectivity\Data;

class API_Message {
	/**
	 * Message status
	 *
	 * @var string
	 */
	private $status;
	/**
	 * Message
	 *
	 * @var string
	 */
	private $message;
	/**
	 * Message code
	 *
	 * @var int
	 */
	private $code;

	public function __construct( string $status, string $message, int $code ) {
		$this->status  = $status;
		$this->message = $message;
		$this->code    = $code;
	}

	public function status() {
		return $this->status;
	}

	public function message() {
		return $this->message;
	}

	public function code() {
		return $this->code;
	}

}
