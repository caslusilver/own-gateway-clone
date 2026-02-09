<?php

namespace WC_Asaas\Split\Hook;

use WC_Asaas\Split\Service\Split_Legacy_Service;
use WC_Asaas\Split\Service\Split_Settings_Service;

class Split_Settings_Hook {

	const SECTION_NAME = 'split';

	public function __construct() {
		add_filter( 'woocommerce_asaas_settings_sections', array( $this, 'add_section' ), 10 );
		add_filter( 'woocommerce_asaas_settings_fields', array( $this, 'add_fields' ), 10, 2 );
		add_filter( 'woocommerce_generate_' . Split_Settings_Service::SETTING_TYPE . '_html', array( $this, 'generate_split_wallet_html' ), 10, 4 );
	}

	public function add_section( $sections ) {
		return array_merge(
			$sections,
			array(
				self::SECTION_NAME => array(
					'title'       => __( 'Split', 'woo-asaas' ),
					'priority'    => 15,
					'description' => __( 'Split does not support subscriptions.', 'woo-asaas' ),
				),
			)
		);
	}

	public function add_fields( $fields, $settings ) {
		$split_settings = new Split_Settings_Service( $settings->gateway );

		return array_merge(
			$fields,
			array(
				Split_Settings_Service::SETTING_NAME => array(
					'title'             => __( 'Wallets', 'woo-asaas' ),
					'type'              => Split_Settings_Service::SETTING_TYPE,
					'section'           => self::SECTION_NAME,
					'priority'          => 50,
					'sanitize_callback' => array( $split_settings, 'sanitize_split_wallet_field' ),
				),
			)
		);
	}

	public function generate_split_wallet_html( string $field_html, string $key, array $field_config, $gateway ) {
		if ( Split_Settings_Service::SETTING_NAME !== $key ) {
			return $field_html;
		}

		( new Split_Legacy_Service( $gateway ) )->ensure_legacy_settings_are_removed();

		return ( new Split_Settings_Service( $gateway ) )->generate_split_wallet_html( $key, $field_config );
	}
}
