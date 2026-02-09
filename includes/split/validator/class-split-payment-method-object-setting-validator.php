<?php

namespace WC_Asaas\Split\Validator;

use InvalidArgumentException;
use WC_Asaas\Common\Validator\Validation_Exception;
use WC_Asaas\Common\Validator\Validator;
use WC_Asaas\Split\Data\Split_Payment_Method_Object_Setting_Data;
use WC_Asaas\Split\Split_Message_List;

class Split_Payment_Method_Object_Setting_Validator extends Validator {

	protected $exception_message = 'Invalid payment method setting.';

	public function validate( $setting ) {
		if ( ! is_a( $setting, Split_Payment_Method_Object_Setting_Data::class ) ) {
			throw new InvalidArgumentException( 'The validator deals just with ' . Split_Payment_Method_Object_Setting_Data::class );
		}

		if ( 0.0 === $setting->value() || 0 === $setting->wallet_id() ) {
			$this->errors[] = ( new Split_Message_List() )->message_from_code( Split_Message_List::EMPTY_SETTING );
		}

		if ( 0 < $setting->wallet_id() ) {
			try {
				$setting->wallet();
			} catch ( Validation_Exception $e ) {
				$this->errors = array_merge( $this->errors, $e->error_messages() );
			}
		}

		if ( 0 > $setting->value() || 100 < $setting->value() ) {
			$this->errors[] = ( new Split_Message_List() )->message_from_code( Split_Message_List::INVALID_PERCENTAGE );
		}

		parent::validate( $setting );
	}
}
