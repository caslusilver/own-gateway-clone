<?php

namespace WC_Asaas\Common\Notice\Data;

use WC_Asaas\Common\Notice\Validator\Notice_Validator;

class Notice_Data {

	const STATUS_ERROR   = 'error';
	const STATUS_WARNING = 'warning';
	const STATUS_SUCCESS = 'success';

	private $status;

	private $message;

	private $priority;

	public function __construct( string $status, string $message, int $priority ) {
		$this->status   = $status;
		$this->message  = $message;
		$this->priority = $priority;

		$this->validate_data( $this );
	}

	private function validate_data() {
		( new Notice_Validator() )->validate( $this );
	}

	public function status() : string {
		return $this->status;
	}

	public function message() : string {
		return $this->message;
	}

	public function priority() : int {
		return $this->priority;
	}
}
