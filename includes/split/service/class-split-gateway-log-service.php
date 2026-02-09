<?php

namespace WC_Asaas\Split\Service;

use InvalidArgumentException;
use WC_Asaas\Gateway\Gateway;

class Split_Gateway_Log_Service {

	public function log( Gateway $gateway, array $settings ) {
		if ( 0 === count( $settings ) ) {
			throw new InvalidArgumentException( 'Settings cannot be empty.' );
		}

		foreach ( $settings as $setting ) {
			$message = sprintf(
				// translators: %1$s is the percentual value, %2$s is the wallet nickname, %3$s is the wallet ID.
				__( 'Split configured at the value of %1$s%% for wallet %2$s Wallet ID: %3$s', 'woo-asaas' ),
				$setting->value(),
				$setting->wallet()->nickname(),
				$setting->wallet()->asaas_id()
			);

			$gateway->get_logger()->log( $message );
		}
	}
}
