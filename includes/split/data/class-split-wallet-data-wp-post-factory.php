<?php

namespace WC_Asaas\Split\Data;

use Exception;
use WC_Asaas\Split\Post_Type\Asaas_Wallet_Post_Type;
use WP_Post;

abstract class Split_Wallet_Data_WP_Post_Factory {

	abstract public function create_with_custom_asaas_wallet_id( WP_Post $post, string $asaas_wallet_id);

	protected function validate_state( WP_Post $post ) {
		$post_type_slug = ( new Asaas_Wallet_Post_Type() )->slug();
		if ( $post_type_slug !== $post->post_type ) {
			throw new Exception( 'Invalid state to create a split wallet.' );
		}
	}

	public function create( WP_Post $post ) {
		$this->validate_state( $post );

		$asaas_wallet_id = $post->wallet_id;
		$wallet          = $this->create_with_custom_asaas_wallet_id( $post, $asaas_wallet_id );

		return $wallet;
	}
}
