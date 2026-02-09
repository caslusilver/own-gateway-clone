<?php

namespace WC_Asaas\Connectivity\Service;

use WC_Asaas\Connectivity\Validator\Nonce_Validator;
use WC_Asaas\Connectivity\Validator\User_Permission_Validator;

class Request_Security_Validator_Service {
	public function validate_security_parameters() {
		( new Nonce_Validator() )->validate();
		( new User_Permission_Validator() )->validate();
	}
}
