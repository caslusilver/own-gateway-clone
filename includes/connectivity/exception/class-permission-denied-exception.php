<?php

namespace WC_Asaas\Connectivity\Exception;

use Exception;

class Permission_Denied_Exception extends Exception {
	public function __construct( $message = '', $code = 403, $previous = null ) {
		if ( '' === $message ) {
			$message = __( 'Permission denied', 'woo-asaas' );
		}

		parent::__construct( $message, $code, $previous );
	}
}
