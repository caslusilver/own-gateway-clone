<?php

namespace WC_Asaas\Connectivity\Service;

use WC_Asaas\Connectivity\API\Resource\Check_Connection_Resource;

class Check_API_Connection_Service {
	public function check_if_has_connection() {
		( new Check_Connection_Resource() )->check_connection();
	}
}
