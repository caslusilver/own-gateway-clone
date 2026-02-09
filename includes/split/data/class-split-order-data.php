<?php

namespace WC_Asaas\Split\Data;

class Split_Order_Data {

	private $wallet;

	private $value;

	public function __construct( $wallet, float $value ) {
		$this->wallet = $wallet;
		$this->value  = $value;
	}

	public function wallet() {
		return $this->wallet;
	}

	public function value() {
		return $this->value;
	}
}
