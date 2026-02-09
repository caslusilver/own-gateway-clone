<?php

namespace WC_Asaas\Connectivity\Data;

use stdClass;
use WC_Asaas\Connectivity\Provider\Gateway_Provider;
use WC_Asaas\Connectivity\Provider\Webhook_Events_Provider;
use WC_Asaas\Connectivity\Provider\Webhook_Helper_Provider;

class Webhook_Factory {
	const WEBHOOK_SUFFIX = '/asaas-webhook';

	public function create_from_api_response( stdClass $response ) {
		return new Registered_Webhook( $response->id, $response->enabled, $response->interrupted );
	}

	public function create_updatable_webhook( Registered_Webhook $webhook ) {
		$webhook_data = $this->create_webhook_with_woocommerce_data();

		return new Updatable_Webhook( $webhook->id(), true, false, $webhook_data->email(), $webhook_data->auth_token() );

	}

	public function create_webhook_with_woocommerce_data() {
		$name        = __( 'Webhooks from WooCommerce', 'woo-asaas' );
		$url         = home_url() . self::WEBHOOK_SUFFIX;
		$email       = ( new Gateway_Provider() )->gateway()->get_setting( 'email_notification' );
		$send_type   = 'SEQUENTIALLY';
		$enabled     = true;
		$interrupted = false;
		$auth_token  = ( new Webhook_Helper_Provider() )->webhook_helper()->generate_random_token();
		$events      = ( new Webhook_Events_Provider() )->events();

		return new Webhook( $name, $url, $email, $send_type, $enabled, $interrupted, $auth_token, $events );
	}
}
