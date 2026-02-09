<?php

namespace WC_Asaas\Connectivity\Validator;

use WC_Asaas\Connectivity\Exception\Invalid_Nonce_Exception;

class Nonce_Validator {
	public function validate() {
		if ( isset( $_POST['_nonce'] ) && ! wp_verify_nonce( sanitize_key( $_POST['_nonce'] ), 'own-gateway-clone-admin-nonce' ) ) {
			throw new Invalid_Nonce_Exception();
		}
	}
}
