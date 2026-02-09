<?php

namespace WC_Asaas\Split\Hook;

use WC_Asaas\Common\Notice\Notificator\Code_Notificator;
use WC_Asaas\Common\Validator\Validation_Exception;
use WC_Asaas\Split\Data\Split_Wallet_In_Progress_Data;
use WC_Asaas\Split\Data\Split_Wallet_Ready_Data;
use WC_Asaas\Split\Notice\Wallet_Notice_Provider;
use WC_Asaas\Split\Post_Type\Asaas_Wallet_Post_Type;
use WC_Asaas\Split\Service\Split_Wallet_Persistence_Service;
use WC_Asaas\Split\Split_Message_List;
use WP_Post;


class Split_Wallet_Save_Hook {


	private $notificator;

	public function __construct() {
		$provider          = new Wallet_Notice_Provider();
		$this->notificator = new Code_Notificator( $provider );

		add_action( 'admin_action_editpost', array( $this, 'save_wallet' ) );
		add_filter( 'redirect_post_location', array( $this, 'disable_default_save_message' ) );
	}

	public function save_wallet() {
		if ( ! isset( $_POST['post_ID'] ) ) {
			return;
		}

		$post_id = intval( $_POST['post_ID'] ); // phpcs:ignore WordPress.CSRF.NonceVerification.NoNonceVerification
		check_admin_referer( 'update-post_' . $post_id );
		$post = get_post( $post_id );

		if ( ! $this->should_save_wallet( $post ) ) {
			return;
		}

		$action = isset( $_POST['save'] ) ? 'edit' : 'create';

		$persistence_service = new Split_Wallet_Persistence_Service();

		$nickname        = isset( $_POST['post_title'] ) ? sanitize_text_field( wp_unslash( $_POST['post_title'] ) ) : '';
		$asaas_wallet_id = isset( $_POST['wallet_id'] ) ? sanitize_text_field( wp_unslash( $_POST['wallet_id'] ) ) : '';

		try {
			$wallet = new Split_Wallet_Ready_Data( $nickname, $asaas_wallet_id, $post );
			$persistence_service->update_wallet( $wallet, $post->ID );
			$this->notificator->add( Split_Message_List::SAVED_SUCCESSFULLY );
		} catch ( Validation_Exception $e ) {
			if ( ! ( 'edit' === $action && 'publish' === $post->post_status ) ) {
				$this->keep_in_draft_with_new_values( $persistence_service, $post, $nickname, $asaas_wallet_id );
			} else {
				$this->keep_published_with_original_values();
			}

			$this->add_error_notices( $e );
		} finally {
			$this->disable_wp_save_post_action();
		}
	}

	private function should_save_wallet( WP_Post $post ) {
		$post_type = ( new Asaas_Wallet_Post_Type() )->slug();
		if ( $post->post_type !== $post_type ) {
			return false;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return false;
		}

		if ( ! current_user_can( 'edit_post', $post->ID ) ) {
			return false;
		}

		return true;
	}

	private function keep_in_draft_with_new_values(
		Split_Wallet_Persistence_Service $persistence_service,
		WP_Post $post,
		string $nickname,
		string $asaas_wallet_id
	) {
		$wallet = new Split_Wallet_In_Progress_Data( $nickname, $asaas_wallet_id, $post );
		$persistence_service->update_wallet( $wallet, $post->ID );
		$this->notificator->add( Split_Message_List::KEPT_AS_DRAFT );
	}

	private function add_error_notices( Validation_Exception $e ) {
		foreach ( $e->error_messages() as $message ) {
			$code = ( new Split_Message_List() )->code_from_message( $message );
			$this->notificator->add( $code );
		}
	}

	private function keep_published_with_original_values() {
		$this->notificator->add( Split_Message_List::ERROR_ON_UPDATE );
	}

	private function disable_wp_save_post_action() {
		add_filter( 'wp_insert_post_empty_content', '__return_true' );
	}

	public function disable_default_save_message( string $location ) {
		$post_type = ( new Asaas_Wallet_Post_Type() )->slug();
		if ( get_current_screen()->post_type !== $post_type ) {
			return $location;
		}

		$location = remove_query_arg( 'message', $location );
		$location = add_query_arg( Code_Notificator::QUERY_STRING, $this->notificator->notices_codes(), $location );

		return $location;
	}
}
