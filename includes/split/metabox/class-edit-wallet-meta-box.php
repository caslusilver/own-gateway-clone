<?php

namespace WC_Asaas\Split\Metabox;

use WC_Asaas\Admin\View;
use WC_Asaas\Common\Metabox\Meta_Box;
use WC_Asaas\Split\Data\Split_Wallet_In_Progress_Data_WP_Post_Factory;

class Edit_Wallet_Meta_Box extends Meta_Box {

	public function title() {
		return __( 'Edit wallet', 'woo-asaas' );
	}

	public function render() {
		$post        = get_post();
		$wallet_data = ( new Split_Wallet_In_Progress_Data_WP_Post_Factory() )->create( $post );

		View::get_instance()->get_template_file(
			'split-wallet-edit-field.php',
			array(
				'wallet_id' => $wallet_data->asaas_id(),
			),
			false,
			'split'
		);
	}

	public function context(): string {
		return 'normal';
	}
}
