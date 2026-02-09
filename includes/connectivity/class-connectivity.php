<?php

namespace WC_Asaas\Connectivity;

use WC_Asaas\Connectivity\Hook\API_Connection_Ajax;
use WC_Asaas\Connectivity\Hook\Webhook_Connection_Ajax;

class Connectivity {
	public function __construct() {
		( new API_Connection_Ajax() );
		( new Webhook_Connection_Ajax() );
	}
}
