<?php

namespace WC_Asaas\Split\Hook;

use WC_Asaas\Split\Data\Split_Wallet_In_Progress_Data_WP_Post_Factory;
use WC_Asaas\Split\Post_Type\Asaas_Wallet_Post_Type;
use WP_Post;

class Split_Wallet_Admin_Table_Hook {

	public function __construct() {
		$post_type = ( new Asaas_Wallet_Post_Type() )->slug();

		add_filter( "manage_{$post_type}_posts_columns", array( $this, 'customize_wallet_columns' ) );
		add_action( "manage_{$post_type}_posts_custom_column", array( $this, 'populate_wallet_columns' ), 10, 2 );
		add_filter( 'post_row_actions', array( $this, 'remove_quick_actions' ), 10, 2 );
	}

	public function customize_wallet_columns( array $columns ) {
		unset( $columns['title'] );
		unset( $columns['date'] );

		$columns['nickname']   = __( 'Nickname', 'woo-asaas' );
		$columns['wallet_id']  = __( 'Wallet ID', 'woo-asaas' );
		$columns['created_at'] = __( 'Date created', 'woo-asaas' );

		return $columns;
	}

	public function populate_wallet_columns( string $column, int $post_id ) {
		$post   = get_post( $post_id );
		$wallet = ( new Split_Wallet_In_Progress_Data_WP_Post_Factory() )->create( $post );

		switch ( $column ) {
			case 'nickname':
				$edit_link = get_edit_post_link( $post_id );
				$title     = $wallet->nickname();
				echo '<a href="' . esc_url( $edit_link ) . '">' . esc_html( $title ) . '</a>';
				break;
			case 'wallet_id':
				$wallet_asaas_id = $wallet->asaas_id();
				echo esc_html( '' === $wallet_asaas_id ? __( 'Not available', 'woo-asaas' ) : $wallet_asaas_id );
				break;
			case 'created_at':
				echo esc_html( get_the_date( '', $post_id ) );
				break;
		}
	}

	public function remove_quick_actions( array $actions, WP_Post $post ) {
		if ( $post->post_type !== ( new Asaas_Wallet_Post_Type() )->slug() ) {
			return $actions;
		}

		unset( $actions['inline hide-if-no-js'] );
		unset( $actions['view'] );

		return $actions;
	}
}
