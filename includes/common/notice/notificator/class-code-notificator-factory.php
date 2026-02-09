<?php

namespace WC_Asaas\Common\Notice\Notificator;

use WC_Asaas\Common\Notice\Code_Notice_Provider;

class Code_Notificator_Factory {

	public function create_from_query_string( Code_Notice_Provider $provider ) {
		$notificator = new Code_Notificator( $provider );
		if ( ! isset( $_GET[ Code_Notificator::QUERY_STRING ] ) ) { // phpcs:ignore WordPress.CSRF.NonceVerification.NoNonceVerification
			return $notificator;
		}

		$codes = array_map( 'intval', $_GET[ Code_Notificator::QUERY_STRING ] ); // phpcs:ignore WordPress.CSRF.NonceVerification.NoNonceVerification
		foreach ( $codes as $code ) {
			$notificator->add( $code );
		}

		return $notificator;
	}
}
