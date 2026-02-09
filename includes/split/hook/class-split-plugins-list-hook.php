<?php

namespace WC_Asaas\Split\Hook;

use WC_Asaas\Split\Post_Type\Asaas_Wallet_Post_Type;

class Split_Plugins_List_Hook {

	public function __construct() {
		add_filter( 'plugin_action_links', array( $this, 'plugin_action_links' ), 10, 2 );
	}

	public function plugin_action_links( array $action_links, string $plugin_file ) {
		if ( false === str_contains( $plugin_file, 'woo-asaas' ) ) {
			return $action_links;
		}

		$post_type_slug = ( new Asaas_Wallet_Post_Type() )->slug();

		$action_links[] = '<a id="split-wallets-manage-link" href="' . esc_url( admin_url( 'edit.php?post_type=' . $post_type_slug ) ) . '">' . __( 'Manage Split Wallets', 'woo-asaas' ) . '</a>';

		return $action_links;
	}
}
