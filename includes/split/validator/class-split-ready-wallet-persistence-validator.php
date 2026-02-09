<?php

namespace WC_Asaas\Split\Validator;

use InvalidArgumentException;
use WC_Asaas\Common\Validator\Validator;
use WC_Asaas\Split\Data\Split_Wallet_Ready_Data;
use WC_Asaas\Split\Query\Split_Wallet_Ready_Query;
use WC_Asaas\Split\Split_Message_List;

class Split_Ready_Wallet_Persistence_Validator extends Validator {

	protected $exception_message = 'Invalid persistent wallet ready data.';

	public function validate( $wallet ) {
		if ( ! is_a( $wallet, Split_Wallet_Ready_Data::class ) ) {
			throw new InvalidArgumentException( 'The validator deals just with ' . Split_Wallet_Ready_Data::class );
		}

		$ready_wallets_with_same_nickname_count = ( new Split_Wallet_Ready_Query() )
			->nickname( $wallet->nickname() )
			->ignore_post( $wallet->post() )
			->count();

		if ( $ready_wallets_with_same_nickname_count > 0 ) {
			$this->errors[] = ( new Split_Message_List() )->message_from_code( Split_Message_List::NICKNAME_ALREADY_IN_USE );
		}

		$ready_wallets_with_same_asaas_wallet_id_count = ( new Split_Wallet_Ready_Query() )
			->asaas_wallet_id( $wallet->asaas_id() )
			->ignore_post( $wallet->post() )
			->count();

		if ( $ready_wallets_with_same_asaas_wallet_id_count > 0 ) {
			$this->errors[] = ( new Split_Message_List() )->message_from_code( Split_Message_List::WALLET_ID_ALREADY_IN_USE );
		}

		parent::validate( $wallet );
	}
}
