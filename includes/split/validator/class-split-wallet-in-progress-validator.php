<?php

namespace WC_Asaas\Split\Validator;

use InvalidArgumentException;
use WC_Asaas\Split\Data\Split_Wallet_In_Progress_Data;

class Split_Wallet_In_Progress_Validator extends Split_Wallet_Validator {

	public function validate( $wallet ) {
		if ( ! is_a( $wallet, Split_Wallet_In_Progress_Data::class ) ) {
			throw new InvalidArgumentException( 'The validator deals just with ' . Split_Wallet_In_Progress_Data::class );
		}

		parent::validate( $wallet );
	}
}
