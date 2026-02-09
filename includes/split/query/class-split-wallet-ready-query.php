<?php

namespace WC_Asaas\Split\Query;

class Split_Wallet_Ready_Query extends Split_Wallet_Query {

	public function __construct() {
		parent::__construct();
		$this->query->set( 'post_status', 'publish' );
	}
}
