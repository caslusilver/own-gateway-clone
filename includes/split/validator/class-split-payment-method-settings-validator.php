<?php

namespace WC_Asaas\Split\Validator;

use InvalidArgumentException;
use WC_Asaas\Common\Validator\Validator;
use WC_Asaas\Split\Split_Message_List;

class Split_Payment_Method_Settings_Validator extends Validator {

	protected $exception_message = 'Invalid payment method settings.';

	public function validate( $settings ) {
		if ( ! is_array( $settings ) ) {
			throw new InvalidArgumentException( 'The validator deals just with array.' );
		}

		$total = 0;
		foreach ( $settings as $setting ) {
			$total += $setting->value();
		}

		if ( 100 < $total ) {
			$this->errors[] = ( new Split_Message_List() )->message_from_code( Split_Message_List::PERCENTAGE_SETTINGS_EXCEEDS_LIMIT );
		}

		parent::validate( $data );
	}
}
