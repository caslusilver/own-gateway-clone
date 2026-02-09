<?php
/**
 * Class to manage Anticipation Payment options.
 *
 * @package WooAsaas
 */

namespace WC_Asaas\Anticipation\Meta;

/**
 * Class Anticipation Meta.
 */
class Anticipation_Meta {

	/**
	 * Option name for anticipation payment.
	 *
	 * @var string
	 */
	private $option_name = 'asaas_anticipation_payment';

	/**
	 * Get anticipation payment option.
	 *
	 * @return array|bool The anticipation payment option or false if it doesn't exist.
	 */
	public function get_anticipation_payment_option() {
		return get_option( $this->option_name );
	}

	/**
	 * Delete anticipation payment option.
	 *
	 * @return void
	 */
	public function delete_anticipation_payment_option() {
		delete_option( $this->option_name );
	}

	/**
	 * Update allowed anticipation option.
	 *
	 * @param bool $allow Allow anticipation payment.
	 * @return void
	 */
	public function update_anticipation_payment_option( $allow = true ) {
		update_option( $this->option_name, array( 'allowAnticipation' => (bool) $allow ) );
	}
}
