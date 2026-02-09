<?php

namespace WC_Asaas\Connectivity\Validator;

use InvalidArgumentException;
use WC_Asaas\Connectivity\Data\Connection_Parameters;

class Connection_Parameters_Validator {
	public function validate( $data ) {
		if ( ! is_a( $data, Connection_Parameters::class ) ) {
			throw new InvalidArgumentException( 'The validator deals just with ' . Connection_Parameters::class, 422 );
		}

		if ( $data->url() === '' ) {
			throw new InvalidArgumentException( 'The url is required', 422 );
		}
		if ( $data->api_key() === '' ) {
			throw new InvalidArgumentException( 'The api key is required', 422 );
		}
	}
}
