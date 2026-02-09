<?php

namespace WC_Asaas\Split\Data;

use WC_Asaas\Split\Validator\Split_Wallet_Validator;
use WC_Asaas\Split\Repository\Split_Wallet_Repository;

abstract class Split_Wallet_Data {

	protected $nickname;

	protected $asaas_id;

	protected $post;

	public function __construct( string $nickname, string $asaas_id, $post ) {
		$this->nickname = $nickname;
		$this->asaas_id = $asaas_id;
		$this->post     = $post;

		$this->validate_data( $this );
	}

	abstract protected function validator(): Split_Wallet_Validator;

	abstract public function repository(): Split_Wallet_Repository;

	public function validate_data( self $wallet ) {
		$this->validator()->validate( $wallet );
	}

	public function nickname() : string {
		return $this->nickname;
	}

	public function asaas_id() : string {
		return $this->asaas_id;
	}

	public function post() {
		return $this->post;
	}
}
