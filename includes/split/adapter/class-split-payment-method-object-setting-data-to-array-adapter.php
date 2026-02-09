<?php

namespace WC_Asaas\Split\Adapter;

use WC_Asaas\Split\Data\Split_Payment_Method_Object_Setting_Data;

class Split_Payment_Method_Object_Setting_Data_To_Array_Adapter {

	private $setting;

	public function __construct( Split_Payment_Method_Object_Setting_Data $setting ) {
		$this->setting = $setting;
	}

	public function adapt_to_database(): array {
		return array(
			'wallet_id' => $this->setting->wallet_id(),
			'value'     => $this->setting->value(),
		);
	}

	public function adapt_to_asaas_api(): array {
		return array(
			'walletId'        => $this->setting->wallet()->asaas_id(),
			'percentualValue' => $this->setting->value(),
		);
	}
}
