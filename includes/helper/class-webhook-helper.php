<?php
/**
 * Webhook Helper
 *
 * @package WooAsaas
 */

namespace WC_Asaas\Helper;

/**
 * Webhook Helper Class
 */
class Webhook_Helper {
	/**
	 * Generates a random token of the specified length
	 *
	 * @param int $length The length of the token to generate. Default is 20.
	 * @return string The generated random token.
	 */
	public function generate_random_token( $length = 20 ) {
		$random_token = wp_generate_password( $length, false );

		return $random_token;
	}

	/**
	 * Masks the authToken value at any level of the provided data structure
	 *
	 * @param array &$data The data structure where authToken should be masked.
	 * @return void
	 */
	public function masked_auth_token( &$data ) {
		foreach ( $data as $key => &$value ) {
			// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			if ( 'authToken' === $key ) {
					$value = $this->mask_token( $value );
					continue;
			}

			if ( is_array( $value ) ) {
					$this->masked_auth_token( $value );
			}
		}
	}

	/**
	 * Mask a given string replacing all characters with '*'
	 *
	 * @param string $token The string to be masked.
	 * @return string The masked string.
	 */
	private function mask_token( $token ) {
		$token_length = strlen( $token );
		$masked_token = str_repeat( '*', $token_length );

		return $masked_token;
	}
}
