<?php

namespace WC_Asaas\Connectivity\Exception;

use Exception;

class Invalid_Nonce_Exception extends Exception {
	public function __construct( string $message = '', int $code = 403, $previous = null ) {
		if ( '' === $message ) {
			$message = __( 'Nonce verification failed', 'woo-asaas' );
		}

		parent::__construct( $message, $code, $previous );
	}
}
