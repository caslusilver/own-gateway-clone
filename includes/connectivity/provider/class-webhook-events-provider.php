<?php

namespace WC_Asaas\Connectivity\Provider;

use WC_Asaas\Webhook\Webhook;

class Webhook_Events_Provider {
	public function events() {
		return [
			Webhook::PAYMENT_CONFIRMED,
			Webhook::PAYMENT_CREATED,
			Webhook::PAYMENT_DELETED,
			Webhook::PAYMENT_OVERDUE,
			Webhook::PAYMENT_RECEIVED,
			Webhook::PAYMENT_REFUNDED,
			Webhook::PAYMENT_RESTORED,
			Webhook::PAYMENT_UPDATED,
		];
	}
}
