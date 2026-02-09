<?php

namespace WC_Asaas\Common\Notice\Notificator;

use WC_Asaas\Common\Notice\Code_Notice_Provider;

class Code_Notificator extends Notificator {

	const QUERY_STRING = 'notice';

	private $provider;

	public function __construct( Code_Notice_Provider $provider ) {
		$this->provider = $provider;
	}

	public function notices_codes() {
		return array_keys( $this->ordered_notices() );
	}

	public function add( int $notice_code ) {
		$notice_data                   = $this->provider->notice_from_code( $notice_code );
		$this->notices[ $notice_code ] = $notice_data;
	}
}
