<?php

namespace WC_Asaas\Connectivity\Adapter;

use WC_Asaas\Connectivity\Data\API_Message;

class API_Message_To_JSON_Response_Adapter {
	/**
	 * The API Message object
	 *
	 * @var API_Message
	 */
	private $api_message;

	public function __construct( API_Message $api_message ) {
		$this->api_message = $api_message;
	}

	public function adapt() {
		return [
			'status'  => $this->api_message->status(),
			'message' => $this->api_message->message(),
			'code'    => $this->api_message->code(),
		];
	}
}
