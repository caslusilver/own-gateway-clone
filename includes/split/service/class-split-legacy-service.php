<?php

namespace WC_Asaas\Split\Service;

use WC_Asaas\Gateway\Gateway;
use WC_Asaas\Split\Repository\Split_Legacy_Repository;

class Split_Legacy_Service {
	private $gateway;
	private $repository;

	public function __construct( Gateway $gateway ) {
		$this->gateway    = $gateway;
		$this->repository = new Split_Legacy_Repository();
	}

	public function ensure_legacy_settings_are_removed() {
		if ( ! $this->has_legacy_options() ) {
			return;
		}
		$this->remove_legacy_options();
	}

	private function has_legacy_options() {
		$has_wallets = $this->repository->has_legacy_split_quantity_option( $this->gateway );
		if ( $has_wallets ) {
			return true;
		}

		$legacy_wallets = $this->repository->has_legacy_split_wallets( $this->gateway );
		if ( $legacy_wallets ) {
			return true;
		}

		return false;
	}

	private function remove_legacy_options() {
		$this->repository->truncate_legacy_wallets( $this->gateway );
		$this->repository->remove_legacy_split_count_option( $this->gateway );
	}
}
