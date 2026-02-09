<?php

namespace WC_Asaas\Connectivity\Hook;

use Exception;
use WC_Asaas\Connectivity\Adapter\API_Message_To_JSON_Response_Adapter;
use WC_Asaas\Connectivity\constant\API_Connection_Message;
use WC_Asaas\Connectivity\constant\HTTP_Message_Code;
use WC_Asaas\Connectivity\Service\API_Connection_Persistence_Service;
use WC_Asaas\Connectivity\Service\Check_API_Connection_Service;

class API_Connection_Ajax extends Connection_Ajax {
	public function __construct() {
		add_action( 'wp_ajax_check_api_connection_status', array( $this, 'check_api_connection_status' ) );
		add_action( 'wp_ajax_api_connection_health_check', array( $this, 'check_api_connection' ) );
	}

	public function check_api_connection_status() {
		try {
			$this->use_requested_connection_parameters();
		} catch ( Exception $e ) {
			wp_send_json_error( API_Connection_Message::message_response_from_code( $e->getCode() ), $e->getCode() );
		}

		$this->check_api_connection();
	}

	public function check_api_connection() {
		try {
			( new Check_API_Connection_Service() )->check_if_has_connection();

			( new API_Connection_Persistence_Service() )->update_connection_status( true );

			$message          = API_Connection_Message::message_response_from_code( HTTP_Message_Code::OK );
			$response_message = ( new API_Message_To_JSON_Response_Adapter( $message ) )->adapt();

			wp_send_json_success( $response_message );
		} catch ( Exception $e ) {
			( new API_Connection_Persistence_Service() )->update_connection_status( false );

			$message          = API_Connection_Message::message_response_from_code( $e->getCode() );
			$response_message = ( new API_Message_To_JSON_Response_Adapter( $message ) )->adapt();

			wp_send_json_error( $response_message, $e->getCode() );
		}
	}

}
