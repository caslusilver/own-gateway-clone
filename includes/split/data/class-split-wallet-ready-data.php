<?php

namespace WC_Asaas\Split\Data;

use WC_Asaas\Split\Repository\Split_Wallet_Ready_Repository;
use WC_Asaas\Split\Repository\Split_Wallet_Repository;
use WC_Asaas\Split\Validator\Split_Wallet_Ready_Validator;
use WC_Asaas\Split\Validator\Split_Wallet_Validator;

class Split_Wallet_Ready_Data extends Split_Wallet_Data {

	public function validator(): Split_Wallet_Validator {
		return new Split_Wallet_Ready_Validator();
	}

	public function repository(): Split_Wallet_Repository {
		return new Split_Wallet_Ready_Repository();
	}
}
