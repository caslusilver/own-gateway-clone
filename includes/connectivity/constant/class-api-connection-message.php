<?php

namespace WC_Asaas\Connectivity\Constant;

use WC_Asaas\Connectivity\Data\API_Message;

class API_Connection_Message {

	public static function messages() {
		return [
			HTTP_Message_Code::OK                    => new API_Message(
				'yes',
				__( 'Valid API Key.', 'woo-asaas' ),
				HTTP_Message_Code::OK
			),
			HTTP_Message_Code::UNAUTHORIZED          => new API_Message(
				'warning',
				__( 'Invalid key or incorrect environment (Sandbox vs Production). Check your API Key.', 'woo-asaas' ),
				HTTP_Message_Code::UNAUTHORIZED
			),
			HTTP_Message_Code::FORBIDDEN             => new API_Message(
				'warning',
				__( 'Access denied: verify if your server IP is authorized to communicate with Asaas.', 'woo-asaas' ),
				HTTP_Message_Code::FORBIDDEN
			),
			HTTP_Message_Code::INTERNAL_SERVER_ERROR => new API_Message(
				'error',
				__( 'Temporary instability. Please try again in a few minutes.', 'woo-asaas' ),
				HTTP_Message_Code::INTERNAL_SERVER_ERROR
			),
		];
	}

	public static function message_response_from_code( int $code ) {
		if ( ! isset( self::messages()[ $code ] ) ) {
			return ( new API_Message(
				'error',
				__( 'Unexpected error. Please wait a few minutes and try again.', 'woo-assas' ),
				$code
			) );
		}

		return self::messages()[ $code ];
	}

}
