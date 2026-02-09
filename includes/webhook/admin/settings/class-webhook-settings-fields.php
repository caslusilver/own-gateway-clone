<?php
/**
 * Webhook Settings Section class
 *
 * @package WooAsaas
 */

namespace WC_Asaas\Webhook\Admin\Settings;

use Exception;
use WC_Asaas\Admin\View;
use WC_Asaas\Admin\Settings\Settings;
use WC_Asaas\Webhook\Meta\Webhook_Meta_Status;

/**
 * Webhook section fields for gateway settings
 */
class Webhook_Settings_Fields {
	/**
	 * Webhook meta status
	 *
	 * @var Webhook_Meta_Status
	 */
	protected $status;

	/**
	 * Instance of this class
	 *
	 * @var self
	 */
	protected static $instance = null;


	/**
	 * Is not allowed to call from outside to prevent from creating multiple instances.
	 */
	private function __construct() {
		$this->status = new Webhook_Meta_Status();
	}

	/**
	 * Prevent the instance from being cloned.
	 */
	private function __clone() {
	}

	/**
	 * Prevent from being unserialized.
	 *
	 * @throws Exception If create a second instance of it.
	 */
	public function __wakeup() {
		throw new Exception( esc_html__( 'Cannot unserialize singleton', 'woo-asaas' ) );
	}

	/**
	 * Return an instance of this class
	 *
	 * @return self A single instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Add webhook settings section.
	 *
	 * @param array $sections Gateway sections.
	 * @return array
	 */
	public function add_section( $sections ) {
		$asaas_log    = 'https://www.asaas.com/customerConfigIntegrations/webhookLogs';
		$asaas_doc    = 'https://docs.asaas.com/docs/fila-pausada';
		$admin_status = admin_url( 'admin.php?page=wc-status#webhook-status-section' );

		return array_merge(
			$sections,
			array(
				'webhook' => array(
					'title'       => __( 'Webhook', 'woo-asaas' ),
					'description' =>
						'<ul class="webhook-desc-content">' .
							'<li>' . __( 'Webhooks are responsible for updating orders in your store according to the payment status update on Asaas. If your orders are not being downloaded, click the button below to re-enable your synchronization queue.', 'woo-asaas' ) .
							/* translators: %1$s: URL to logs. */
							'<li>' . sprintf( __( 'If you have <a href="%1$s">identified in the logs</a> that your webhook queue is interrupted, click on the bottom below to reactivate it', 'woo-asaas' ), esc_url( $admin_status ), $asaas_log ) . '.</li>' .
							'<li><button class="button-secondary reenable-queue" disabled>' . __( 'Re-enable webhook queue', 'woo-asaas' ) . '</button></li>' .
							/* translators: %1$s: URL to Asaas logs, %2$s: URL to Asaas documentation. */
							'<li>' . sprintf( __( 'If even after reactivation you find that your queue is still being interrupted, please <a href="%1$s" target="_blank" rel="noopener noreferrer">access Asaas</a> to check the webhook information sent. If you have any questions, you can <a href="%2$s" target="_blank" rel="noopener noreferrer">access the documentation</a> or contact Asaas support (integracoes@asaas.com.br)', 'woo-asaas' ), $asaas_log, $asaas_doc ) . '.</li>' .
						'</ul>',
					'priority'    => 30,
				),
			)
		);
	}

	/**
	 * Add webhook gateway settings fields.
	 *
	 * @param array    $fields Gateway fields.
	 * @param Settings $settings Gateway settings object.
	 * @return array
	 */
	public function add_fields( $fields, $settings ) {
		$webhook_settings = new Webhook_Settings( $settings->gateway );

		return array_merge(
			$fields,
			array(
				'email_notification' => array(
					'title'             => __( 'Email for alerts', 'woo-asaas' ) . ' (' . __( 'required', 'woo-asaas' ) . ')',
					'type'              => 'email',
					'description'       => __( 'If your synchronization queue is interrupted due to any error, we will send an alert to the email provided above.', 'woo-asaas' ),
					'shared'            => true,
					'section'           => 'webhook',
					'priority'          => 10,
					'sanitize_callback' => array( $webhook_settings, 'validate_email_notification_field' ),
				),
			)
		);
	}

	/**
	 * Adds status section.
	 */
	public function add_status_connection_section() {
		$args = array(
			'connection_status' => $this->status->get_connection_status(),
			'queue_status'      => $this->status->get_queue_status(),
		);

		View::get_instance()->get_template_file( 'webhook-status-section.php', $args, false, 'webhook' );
	}
}
