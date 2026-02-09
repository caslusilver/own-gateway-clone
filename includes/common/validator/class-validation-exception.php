<?php

namespace WC_Asaas\Common\Validator;

use Exception;

class Validation_Exception extends Exception {

	private $error_messages;

	public function __construct( string $message = '', int $code = 0, $previous = null, array $error_messages = array() ) {
		parent::__construct( $message, $code, $previous );
		$this->error_messages = $error_messages;
	}

	public function error_messages(): array {
		return $this->error_messages;
	}
}
