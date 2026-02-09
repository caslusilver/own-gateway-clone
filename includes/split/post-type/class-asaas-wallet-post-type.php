<?php

namespace WC_Asaas\Split\Post_Type;

use WC_Asaas\Common\Post_Type\Post_Type;

class Asaas_Wallet_Post_Type extends Post_Type {

	public function slug(): string {
		return 'asaas_wallet';
	}

	public function args(): array {
		return array(
			'labels'       => array(
				'name'               => __( 'Wallets', 'woo-asaas' ),
				'singular_name'      => __( 'Wallet', 'woo-asaas' ),
				'menu_name'          => __( 'Split Wallets', 'woo-asaas' ),
				'add_new'            => __( 'Add New', 'woo-asaas' ),
				'add_new_item'       => __( 'Add New Wallet', 'woo-asaas' ),
				'edit'               => __( 'Edit Wallet', 'woo-asaas' ),
				'edit_item'          => __( 'Edit Wallet', 'woo-asaas' ),
				'new_item'           => __( 'New Wallet', 'woo-asaas' ),
				'view'               => __( 'View Wallet', 'woo-asaas' ),
				'view_item'          => __( 'View Wallet', 'woo-asaas' ),
				'search_items'       => __( 'Search Wallets', 'woo-asaas' ),
				'not_found'          => __( 'No wallets found', 'woo-asaas' ),
				'not_found_in_trash' => __( 'No wallets found in Trash', 'woo-asaas' ),
			),
			'public'       => false,
			'show_ui'      => true,
			'supports'     => array( 'title' ),
			'show_in_menu' => false,
		);
	}
}
