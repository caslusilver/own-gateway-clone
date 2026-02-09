<?php

namespace WC_Asaas\Split\Hook;

use WC_Asaas\Admin\View;
use WC_Asaas\Common\Notice\Notificator\Code_Notificator_Factory;
use WC_Asaas\Split\Notice\Wallet_Notice_Provider;
use WC_Asaas\Split\Post_Type\Asaas_Wallet_Post_Type;
use WC_Asaas\WC_Asaas;
use WP_Post;

class Split_Wallet_Edit_Page_Hook {

	public function __construct() {
		add_action( 'admin_notices', array( $this, 'notices' ) );
		add_filter( 'enter_title_here', array( $this, 'custom_post_title_label' ) );
		add_action( 'post_submitbox_misc_actions', array( $this, 'render_submitbox_author_misc' ) );
		add_action( 'woocommerce_asaas_add_inline_script', array( $this, 'render_submitbox_date_created_misc' ) );
	}

	public function notices() {
		if ( ! $this->is_edit_page() ) {
			return;
		}

		$notices = ( new Code_Notificator_Factory() )->create_from_query_string( new Wallet_Notice_Provider() );
		$notices->render();
	}

	private function is_edit_page() {
		$current_screen = get_current_screen();

		if ( is_null( $current_screen ) ) {
			return false;
		}

		$post_type = ( new Asaas_Wallet_Post_Type() )->slug();
		if ( $post_type !== $current_screen->post_type ) {
			return false;
		}

		if ( 'post' !== $current_screen->base ) {
			return false;
		}

		return true;
	}

	public function custom_post_title_label( string $title ) {
		if ( ! $this->is_edit_page() ) {
			return $title;
		}

		return __( 'Nickname', 'woo-asaas' );
	}

	public function customize_submit_box() {
		if ( ! $this->is_edit_page() ) {
			return $title;
		}

		add_action( 'post_submitbox_misc_actions', array( $this, 'render_submitbox_author_misc' ) );
		add_action( 'woocommerce_asaas_add_inline_script', array( $this, 'render_submitbox_date_created_misc' ) );
	}

	public function render_submitbox_author_misc( WP_Post $post ) {
		if ( ! $this->is_edit_page() ) {
			return;
		}

		View::get_instance()->get_template_file(
			'split-wallet-misc-post-author.php',
			[ 'author' => $post->post_author ],
			false,
			'split'
		);
	}

	public function render_submitbox_date_created_misc( string $script_name ) {
		if ( WC_Asaas::get_instance()->assets_handle() !== $script_name ) {
			return;
		}

		if ( ! $this->is_edit_page() ) {
			return;
		}

		wp_add_inline_script( $script_name, 'let createdInLabel = "' . __( 'Created on', 'woo-asaas' ) . '"', 'before' );
	}
}
