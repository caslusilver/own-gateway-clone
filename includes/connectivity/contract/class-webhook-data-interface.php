<?php

namespace WC_Asaas\Connectivity\Contract;

interface Webhook_Data_Interface {
	public function set_access_token( string $token );

	public function set_email( string $email );

	public function get_request_data();

	public function url();
}
