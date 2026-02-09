<?php

namespace WC_Asaas\Connectivity\Exception;

use Exception;

class API_Error_Response_Exception extends Exception {
	public function __construct( $message = '', $code = 0, $previous = null ) {
		if ( '' === $message ) {
			$message = __( 'Error obtaining response from API', 'woo-asaas' );
		}

		parent::__construct( $message, $code, $previous );
	}
}
