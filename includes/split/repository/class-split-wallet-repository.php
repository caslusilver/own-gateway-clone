<?php

namespace WC_Asaas\Split\Repository;

use WC_Asaas\Split\Data\Split_Wallet_Data;
use WC_Asaas\Split\Post_Type\Asaas_Wallet_Post_Type;

abstract class Split_Wallet_Repository {

	abstract public function update( $wallet, int $id);

	protected function persist_update( Split_Wallet_Data $wallet, int $id, string $status ) {
		$post_type = ( new Asaas_Wallet_Post_Type() )->slug();

		wp_update_post(
			array(
				'ID'          => $id,
				'post_status' => $status,
				'post_type'   => $post_type,
				'post_title'  => $wallet->nickname(),
				'meta_input'  => array(
					'wallet_id' => $wallet->asaas_id(),
				),
			)
		);
	}
}
