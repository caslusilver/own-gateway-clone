<?php

namespace WC_Asaas\Connectivity\Validator;

use WC_Asaas\Connectivity\Exception\Permission_Denied_Exception;

class User_Permission_Validator {
	public function validate() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			throw new Permission_Denied_Exception();
		}
	}
}
