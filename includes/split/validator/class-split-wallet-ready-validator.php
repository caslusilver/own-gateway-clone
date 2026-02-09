<?php

namespace WC_Asaas\Split\Validator;

use InvalidArgumentException;
use WC_Asaas\Split\Data\Split_Wallet_Ready_Data;
use WC_Asaas\Split\Split_Message_List;

class Split_Wallet_Ready_Validator extends Split_Wallet_Validator {

	private $message_list;

	public function __construct() {
		$this->message_list = new Split_Message_List();
	}

	public function validate( $wallet ) {
		if ( ! is_a( $wallet, Split_Wallet_Ready_Data::class ) ) {
			throw new InvalidArgumentException( 'The validator deals just with ' . Split_Wallet_Ready_Data::class );
		}

		$this
			->validate_nickname( $wallet->nickname() )
			->validate_asaas_wallet_id( $wallet->asaas_id() );

		parent::validate( $wallet );
	}


	private function validate_nickname( string $nickname ) {
		if ( $this->is_empty( $nickname ) ) {
			$this->errors[] = $this->message_list->message_from_code( Split_Message_List::EMPTY_NICKNAME );
		}

		return $this;
	}

	private function is_empty( string $value ) {
		return '' === $value;
	}

	private function validate_asaas_wallet_id( string $asaas_wallet_id ) {
		if ( $this->is_empty( $asaas_wallet_id ) ) {
			$this->errors[] = $this->message_list->message_from_code( Split_Message_List::EMPTY_WALLET_ID );
			return $this;
		}

		if ( ! $this->is_valid_wallet_id( $asaas_wallet_id ) ) {
			$this->errors[] = $this->message_list->message_from_code( Split_Message_List::INVALID_WALLET_ID );
			return $this;
		}

		return $this;
	}

	private function is_valid_wallet_id( string $wallet_id ) {
		$pattern         = '/^[a-f\d]{8}-[a-f\d]{4}-[a-f\d]{4}-[a-f\d]{4}-[a-f\d]{12}$/i';
		$valid_wallet_id = (bool) preg_match( $pattern, $wallet_id );

		return $valid_wallet_id;
	}
}
