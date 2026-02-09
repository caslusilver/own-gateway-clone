<?php

namespace WC_Asaas\Split\Service;

use WC_Asaas\Split\Data\Split_Wallet_Data;

class Split_Wallet_Persistence_Service {

	public function update_wallet( Split_Wallet_Data $data, int $id ) {
		$data->repository()->update( $data, $id );
	}
}
