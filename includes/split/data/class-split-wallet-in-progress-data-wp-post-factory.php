<?php

namespace WC_Asaas\Split\Data;

use WP_Post;

class Split_Wallet_In_Progress_Data_WP_Post_Factory extends Split_Wallet_Data_WP_Post_Factory {

	public function create( WP_Post $post ) {
		$asaas_wallet_id = $post->wallet_id;
		$wallet          = $this->create_with_custom_asaas_wallet_id( $post, $asaas_wallet_id );

		return $wallet;
	}

	public function create_with_custom_asaas_wallet_id( WP_Post $post, string $asaas_wallet_id ) {
		$this->validate_state( $post );

		return new Split_Wallet_In_Progress_Data( $post->post_title, $asaas_wallet_id, $post );
	}
}
