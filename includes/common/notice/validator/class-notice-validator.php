<?php

namespace WC_Asaas\Common\Notice\Validator;

use WC_Asaas\Common\Notice\Data\Notice_Data;
use WC_Asaas\Common\Validator\Validation_Exception;

class Notice_Validator {

	const ALLOWED_STATUS = [
		Notice_Data::STATUS_ERROR,
		Notice_Data::STATUS_WARNING,
		Notice_Data::STATUS_SUCCESS,
	];

	private $errors = array();

	public function validate( Notice_Data $notice ) {
		if ( ! in_array( $notice->status(), self::ALLOWED_STATUS, true ) ) {
			$this->errors[] = __( 'Invalid notice status.', 'woo-asaas' );
		}

		if ( 0 < count( $this->errors ) ) {
			throw new Validation_Exception( 'Invalid notice.', 0, null, $this->errors );
		}
	}
}
