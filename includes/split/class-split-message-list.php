<?php

namespace WC_Asaas\Split;

class Split_Message_List {

	const SAVED_SUCCESSFULLY                = 0;
	const KEPT_AS_DRAFT                     = 1;
	const EMPTY_NICKNAME                    = 2;
	const EMPTY_WALLET_ID                   = 3;
	const INVALID_WALLET_ID                 = 4;
	const NICKNAME_ALREADY_IN_USE           = 5;
	const WALLET_ID_ALREADY_IN_USE          = 6;
	const ERROR_ON_UPDATE                   = 7;
	const EMPTY_SETTING                     = 8;
	const PERCENTAGE_SETTINGS_EXCEEDS_LIMIT = 9;
	const INVALID_PERCENTAGE                = 10;

	private function messages() {
		return array(
			self::SAVED_SUCCESSFULLY                => __( 'Wallet saved successfully', 'woo-asaas' ),
			self::KEPT_AS_DRAFT                     => __( 'The wallet was kept as draft due to the invalid values provided.', 'woo-asaas' ),
			self::EMPTY_NICKNAME                    => __( 'Wallet nickname cannot be empty.', 'woo-asaas' ),
			self::EMPTY_WALLET_ID                   => __( 'Wallet ID cannot be empty.', 'woo-asaas' ),
			self::INVALID_WALLET_ID                 => __( 'Invalid Wallet ID.', 'woo-asaas' ),
			self::NICKNAME_ALREADY_IN_USE           => __( 'Wallet nickname is already in use.', 'woo-asaas' ),
			self::WALLET_ID_ALREADY_IN_USE          => __( 'Wallet ID is already in use.', 'woo-asaas' ),
			self::ERROR_ON_UPDATE                   => __( 'An error occurred while updating the wallet.', 'woo-asaas' ),
			self::EMPTY_SETTING                     => __( 'Select the wallet and fill in the percentage value in the table to register a new split wallet.', 'woo-asaas' ),
			self::PERCENTAGE_SETTINGS_EXCEEDS_LIMIT => __( 'The sum of the split wallets cannot exceeds 100%.', 'woo-asaas' ),
			self::INVALID_PERCENTAGE                => __( 'Invalid percentage value.', 'woo-asaas' ),
		);
	}

	public function code_from_message( string $message ) {
		$messages = $this->messages();
		$codes    = array_flip( $messages );

		if ( isset( $codes[ $message ] ) ) {
			return $codes[ $message ];
		}

		throw new \Exception( 'Message not found' );
	}

	public function message_from_code( int $code ) {
		$messages = $this->messages();

		if ( isset( $messages[ $code ] ) ) {
			return $messages[ $code ];
		}

		throw new \Exception( 'Code not found' );
	}
}
