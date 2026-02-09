<?php

namespace WC_Asaas\Split\Adapter;

use WC_Asaas\Split\Data\Split_Order_Data;
use WC_Asaas\Split\Repository\Split_Wallet_Ready_Repository;

class Split_Asaas_Api_Data_To_Order_Data_Adapter {

	private $asaas_api_split_object;

	public function __construct( $asaas_api_split_object ) {
		$this->asaas_api_split_object = $asaas_api_split_object;
	}

	public function adapt() {
		$wallet = ( new Split_Wallet_Ready_Repository() )->retrieve_from_asaas_wallet_id( $this->asaas_api_split_object->walletId );

		return new Split_Order_Data( $wallet, $this->asaas_api_split_object->percentualValue );
	}
}
