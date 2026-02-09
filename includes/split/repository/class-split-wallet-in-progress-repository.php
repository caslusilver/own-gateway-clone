<?php

namespace WC_Asaas\Split\Repository;

use InvalidArgumentException;
use WC_Asaas\Split\Data\Split_Wallet_In_Progress_Data;

class Split_Wallet_In_Progress_Repository extends Split_Wallet_Repository {

	public function update( $wallet, int $id ) {
		if ( ! is_a( $wallet, Split_Wallet_In_Progress_Data::class ) ) {
			throw new InvalidArgumentException( 'The repository deals just with ' . Split_Wallet_In_Progress_Data::class );
		}

		parent::persist_update( $wallet, $id, 'draft' );
	}
}
