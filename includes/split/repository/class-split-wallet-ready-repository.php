<?php

namespace WC_Asaas\Split\Repository;

use WC_Asaas\Common\Repository\Register_Not_Found_Exception;
use WC_Asaas\Split\Data\Split_Wallet_Ready_Data;
use WC_Asaas\Split\Query\Split_Wallet_Ready_Query;
use WC_Asaas\Split\Validator\Split_Ready_Wallet_Persistence_Validator;

class Split_Wallet_Ready_Repository extends Split_Wallet_Repository {

	public function update( $wallet, int $id ) {
		if ( ! is_a( $wallet, Split_Wallet_Ready_Data::class ) ) {
			throw new InvalidArgumentException( 'The repository deals just with ' . Split_Wallet_Ready_Data::class );
		}

		( new Split_Ready_Wallet_Persistence_Validator() )->validate( $wallet );

		parent::persist_update( $wallet, $id, 'publish' );
	}

	public function retrieve_from_asaas_wallet_id( string $asaas_wallet_id ) {
		$wallets = ( new Split_Wallet_Ready_Query() )
			->asaas_wallet_id( $asaas_wallet_id )
			->results();

		if ( 0 === count( $wallets ) ) {
			return new Register_Not_Found_Exception( sprintf( 'Wallet with id %s not found', $asaas_wallet_id ) );
		}

		return $wallets[0];
	}
}
