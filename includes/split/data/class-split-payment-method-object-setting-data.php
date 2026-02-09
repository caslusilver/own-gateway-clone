<?php

namespace WC_Asaas\Split\Data;

use WC_Asaas\Gateway\Gateway;
use WC_Asaas\Split\Validator\Split_Payment_Method_Object_Setting_Validator;

class Split_Payment_Method_Object_Setting_Data {

	private $gateway;

	private $wallet_id;

	private $value;

	public function __construct( Gateway $gateway, int $wallet_id, float $value ) {
		$this->gateway   = $gateway;
		$this->wallet_id = $wallet_id;
		$this->value     = $value;

		$this->validate_data();
	}

	private function validate_data() {
		( new Split_Payment_Method_Object_Setting_Validator() )->validate( $this );
	}

	public function repository(): Split_Payment_Method_Object_Setting_Repository {
		return new Split_Payment_Method_Object_Setting_Repository();
	}

	public function gateway(): Gateway {
		return $this->gateway;
	}

	public function wallet_id(): int {
		return $this->wallet_id;
	}

	public function wallet(): Split_Wallet_Ready_Data {
		$post = get_post( $this->wallet_id );
		return ( new Split_Wallet_Ready_Data_WP_Post_Factory() )->create( $post );
	}

	public function value(): float {
		return $this->value;
	}
}
