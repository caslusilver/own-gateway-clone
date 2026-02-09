<?php

namespace WC_Asaas\Connectivity\Data;

class Connection_Parameters_Factory {
	public function create_from_requested_post_parameters() {
		// phpcs:ignore WordPress.CSRF.NonceVerification.NoNonceVerification
		$url = isset( $_POST['url'] ) ? esc_url_raw( wp_unslash( $_POST['url'] ) ) : '';
		// phpcs:ignore WordPress.CSRF.NonceVerification.NoNonceVerification
		$api_key = isset( $_POST['api_key'] ) ? wp_kses_data( wp_unslash( $_POST['api_key'] ) ) : '';

		return new Connection_Parameters( $url, $api_key );
	}
}
