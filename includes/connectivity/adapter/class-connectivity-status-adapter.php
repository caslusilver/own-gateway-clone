<?php

namespace WC_Asaas\Connectivity\Adapter;

use WC_Asaas\Connectivity\Contract\Connectivity_Status_Interface;
use WC_Asaas\Webhook\Meta\Webhook_Meta_Status;

class Connectivity_Status_Adapter implements Connectivity_Status_Interface {

	/**
	 * The webhook meta status object.
	 *
	 * @var Webhook_Meta_Status
	 */
	private $webhook_meta_status;

	public function __construct( Webhook_Meta_Status $webhook_meta_status ) {
		$this->webhook_meta_status = $webhook_meta_status;
	}

	public function set_status( bool $connection_status = true, bool $queue_status = false ) {
		$this->webhook_meta_status->set_status( $connection_status, $queue_status );
	}

	public function get_connection_status() {
		return $this->webhook_meta_status->get_connection_status();
	}
}
