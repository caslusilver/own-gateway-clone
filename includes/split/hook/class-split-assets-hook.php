<?php
namespace WC_Asaas\Split\Hook;

use WC_Asaas\Split\Post_Type\Asaas_Wallet_Post_Type;


class Split_Assets_Hook {

	public function __construct() {
		add_filter( 'woocommerce_asaas_should_enqueue_script', array( $this, 'maybe_enqueue_scripts' ), 10, 2 );
	}

	public function maybe_enqueue_scripts( bool $should_enqueue, string $hook_suffix ) {
		$asas_wallet_post_type_slug    = ( new Asaas_Wallet_Post_Type() )->slug();
		$current_screen_post_type_slug = get_post_type();

		$allowed_suffix = array( 'post.php', 'edit.php', 'post-new.php' );
		if ( in_array( $hook_suffix, $allowed_suffix, true ) && $asas_wallet_post_type_slug === $current_screen_post_type_slug ) {
			return true;
		}

		return $should_enqueue;
	}
}
