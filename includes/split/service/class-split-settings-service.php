<?php

namespace WC_Asaas\Split\Service;

use WC_Asaas\Admin\View;
use WC_Asaas\Common\Notice\Data\Notice_Data;
use WC_Asaas\Common\Validator\Validation_Exception;
use WC_Asaas\Split\Adapter\Split_Payment_Method_Object_Setting_Array_To_Data_Adapter;
use WC_Asaas\Split\Adapter\Split_Payment_Method_Object_Setting_Data_To_Array_Adapter;
use WC_Asaas\Split\Data\Split_Payment_Method_Object_Setting_Data;
use WC_Asaas\Split\Notice\Prefixer;
use WC_Asaas\Split\Query\Split_Wallet_Ready_Query;
use WC_Asaas\Split\Validator\Split_Payment_Method_Settings_Validator;

class Split_Settings_Service {


	const SETTING_NAME = 'split_wallet';

	const SETTING_TYPE = 'split_wallet';

	protected $gateway;

	public function __construct( $gateway ) {
		$this->gateway = $gateway;
	}

	public function generate_split_wallet_html( string $key, array $field_config ) {
		$field_key = $this->gateway->get_field_key( $key );

		$registered_wallets = array();
		$field_value        = (array) $this->gateway->get_option( $key, array() );
		foreach ( $field_value as $value ) {
			$registered_wallets[] = ( new Split_Payment_Method_Object_Setting_Array_To_Data_Adapter( $value ) )->adapt( $this->gateway );
		}

		$wallet_list = ( new Split_Wallet_Ready_Query() )->results();
		$args        = array(
			'registered_wallets' => $registered_wallets,
			'field_config'       => $field_config,
			'field_key'          => $field_key,
			'is_global'          => true,
			'wallets'            => $wallet_list,
		);

		return View::get_instance()->get_template_file( 'object-setting/object-setting-table.php', $args, true, 'split' );
	}

	public function sanitize_split_wallet_field( $value ) {
		if ( false === is_array( $value ) ) {
			return $value;
		}

		$settings            = array();
		$validation_messages = array();
		foreach ( $value as $item ) {
			try {
				$settings[] = new Split_Payment_Method_Object_Setting_Data(
					$this->gateway,
					intval( $item['walletPostId'] ),
					floatval( $item['percentualValue'] )
				);
			} catch ( Validation_Exception $e ) {
				$validation_messages = array_merge( $validation_messages, $e->error_messages() );
			}
		}

		try {
			( new Split_Payment_Method_Settings_Validator() )->validate( $settings );
		} catch ( Validation_Exception $e ) {
			$validation_messages = array_merge( $validation_messages, $e->error_messages() );
		}

		if ( 0 < count( $validation_messages ) ) {
			foreach ( $e->error_messages() as $message ) {
				$prefixed_message = ( new Prefixer() )->prefix( $message );
				$notice           = new Notice_Data( Notice_Data::STATUS_ERROR, $prefixed_message, 1 );
				$this->gateway->get_admin_settings()->notificator()->add( $notice );
			}
			return $this->gateway->settings[ self::SETTING_NAME ];
		}

		$formatted_values = array();
		foreach ( $settings as $setting ) {
			$formatted_values[] = ( new Split_Payment_Method_Object_Setting_Data_To_Array_Adapter( $setting ) )->adapt_to_database();
		}

		return $formatted_values;
	}
}
