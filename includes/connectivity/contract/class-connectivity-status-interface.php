<?php

namespace WC_Asaas\Connectivity\Contract;

interface Connectivity_Status_Interface {
	public function set_status( bool $connection_status = true, bool $queue_status = false );

	public function get_connection_status();
}
