<?php

namespace WC_Asaas\Split\Hook;

use WC_Asaas\Split\Metabox\Edit_Wallet_Meta_Box;
use WC_Asaas\Split\Post_Type\Asaas_Wallet_Post_Type;

class Split_Wallet_Post_Type_Hook {

	private $post_type;

	public function __construct() {
		$this->post_type = new Asaas_Wallet_Post_Type();

		add_action( 'init', array( $this, 'register' ), 20 );
		add_action( 'add_meta_boxes', array( $this, 'edit_wallet_meta_box' ) );
	}

	public function register() {
		register_post_type( $this->post_type->slug(), $this->post_type->args() );
	}

	public function edit_wallet_meta_box() {
		$meta_box = new Edit_Wallet_Meta_Box();

		add_meta_box(
			'edit_wallet_meta_box',
			$meta_box->title(),
			array( $meta_box, 'render' ),
			$this->post_type->slug(),
			$meta_box->context()
		);
	}
}
