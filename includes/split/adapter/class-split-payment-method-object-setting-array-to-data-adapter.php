<?php

namespace WC_Asaas\Split\Adapter;

use WC_Asaas\Gateway\Gateway;
use WC_Asaas\Split\Data\Split_Payment_Method_Object_Setting_Data;

class Split_Payment_Method_Object_Setting_Array_To_Data_Adapter {

	private $setting;

	public function __construct( array $setting ) {
		$this->setting = $setting;
	}

	public function adapt( Gateway $gateway ) {
		return new Split_Payment_Method_Object_Setting_Data( $gateway, $this->setting['wallet_id'], $this->setting['value'] );
	}
}
