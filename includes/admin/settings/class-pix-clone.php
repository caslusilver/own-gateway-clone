<?php
/**
 * Pix clone settings class.
 *
 * @package WooAsaas
 */

namespace WC_Asaas\Admin\Settings;

use WC_Asaas\Connectivity\Service\Webhook_Invoices_Service;

class Pix_Clone extends Settings {
	/**
	 * Default payment validity days.
	 *
	 * @var int
	 */
	const DEFAULT_VALIDITY_DAYS = 3;

	/**
	 * {@inheritDoc}
	 */
	public function get_fields() {
		$fields                           = parent::get_fields( $this->gateway->get_type() );
		$fields['description']['default'] = __( 'Pague sua compra com Pix via webhook.', 'woo-asaas' );

		$fields['enabled']['label'] = __( 'Enable Pix (Clone)', 'woo-asaas' );
		$fields['title']['default'] = __( 'Pix (Clone)', 'woo-asaas' );

		$fields['endpoint'] = array_merge(
			$fields['endpoint'],
			array(
				'title'       => __( 'Webhook URL', 'woo-asaas' ),
				'type'        => 'text',
				'description' => __( 'Endpoint do webhook para criação de cobranças Pix. Deixe vazio para usar o padrão.', 'woo-asaas' ),
				'default'     => Webhook_Invoices_Service::DEFAULT_WEBHOOK_URL,
			)
		);

		$fields['api_key'] = array_merge(
			$fields['api_key'],
			array(
				'title'       => __( 'Auth key name', 'woo-asaas' ),
				'type'        => 'text',
				'description' => __( 'Nome da chave no cofre/ambiente usada em sakm_get_key. Ex: authorization.', 'woo-asaas' ),
				'default'     => 'authorization',
			)
		);

		$fields['notification'] = array_merge(
			$fields['notification'],
			array(
				'title'       => __( 'Customer notifications', 'woo-asaas' ),
				'description' => __( 'Opção reservada para notificações externas. Não afeta o clone por enquanto.', 'woo-asaas' ),
				'default'     => 'no',
			)
		);

		return array_merge(
			apply_filters( 'woocommerce_asaas_pix_clone_settings_fields', $fields, $this ),
			array(
				'validity_days'  => array(
					'title'             => __( 'Expiration Period', 'woo-asaas' ),
					'type'              => 'text',
					'description'       => __( 'Pix validity after purchase. At the end of this period, the Pix will be removed. The value can be minutes (<code>10m</code> for 10 minutes), hours (<code>3h</code> for 3 hours) or days (<code>1d</code> for 1 day). If you do not enter <code>m</code>, <code>h</code> or <code>d</code>, the default will be <code>d</code>. Leave blank to not expire. The minimum period is 10 minutes.', 'woo-asaas' ),
					'default'           => $this->get_default_pix_validity_days(),
					'section'           => 'gateway',
					'priority'          => 20,
					'sanitize_callback' => array( $this, 'valid_min_expiration_period' ),
				),
				'copy_and_paste' => array(
					'title'    => __( 'Copy and paste', 'woo-asaas' ),
					'type'     => 'checkbox',
					'label'    => __( 'Enable copy and paste', 'woo-asaas' ),
					'default'  => 'yes',
					'section'  => 'gateway',
					'priority' => 20,
				),
			)
		);
	}

	/**
	 * Returns the default payment validity days for the PIX method.
	 *
	 * @return int
	 */
	public function get_default_pix_validity_days() : int {
		$validity_days = intval( apply_filters( 'woocommerce_asaas_default_pix_validity_days', self::DEFAULT_VALIDITY_DAYS ) );
		return $validity_days;
	}

	/**
	 * Ensure that the expiration period has at least 10m if is set.
	 *
	 * @param string $value The expiration period value.
	 * @return string The expiration period fixed value.
	 */
	public function valid_min_expiration_period( string $value ) : string {
		if ( '' === $value ) {
			return '';
		}

		$period = substr( $value, -1 );
		if ( 'm' !== $period ) {
			return $value;
		}

		$expiration_value = intval( substr( $value, 0, -1 ) );
		if ( 10 > $expiration_value ) {
			return '10m';
		}

		return $value;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_sections() {
		$sections                     = parent::get_sections();
		$sections['gateway']['title'] = __( 'Pix (Clone)', 'woo-asaas' );
		unset( $sections['subscriptions'] );
		return apply_filters( 'woocommerce_asaas_pix_clone_settings_sections', $sections );
	}
}
