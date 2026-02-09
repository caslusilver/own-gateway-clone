<?php

namespace WC_Asaas\Connectivity\Service;

use WC_Asaas\Connectivity\Provider\Connectivity_Status_Provider;

class API_Connection_Persistence_Service {
	public function update_connection_status( bool $status ) {
		$status_manager = ( new Connectivity_Status_Provider() )->connectivity_status_manager();
		$status_manager->set_status( $status, false );
	}
}
