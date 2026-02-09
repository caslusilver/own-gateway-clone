<?php
/**
 * Common gateways settings class
 *
 * @package WooAsaas
 */

namespace WC_Asaas\Admin\Settings;

use WC_Asaas\Api\Api;
use WC_Asaas\Common\Notice\Notificator\Simple_Notificator;
use WC_Asaas\Gateway\Gateway;
use WC_Asaas\Webhook\Endpoint;
use WC_Asaas\Helper\WP_List_Util;

/**
 * Common gateways settings
 */
abstract class Settings {

	/**
	 * The billing type object data
	 *
	 * @var Gateway
	 */
	public $gateway;

	/**
	 * Settings fields
	 *
	 * This plugin setting field add section to organize the settings and priority args to allow a better order customization.
	 *
	 * @var array {
	 *     Similar to WooCommerce settings fields adding `section` and `priority`.
	 *
	 *     @type string $shared   Share the same config value between all plugin gateways.
	 *     @type string $section  The section id.
	 *     @type int    $priority The field priority to show.
	 * }
	 */
	protected $fields;

	/**
	 * Field Sections
	 *
	 * The sections fields allow group field in sections. The sections render is managed by this class
	 *
	 * @var array {
	 *     Similar to WooCommerce settings fields adding `section` and `priority`.
	 *
	 *     @type string $title    The section title.
	 *     @type int    $priority The section priority to show.
	 * }
	 */
	protected $sections;

	/**
	 * The generated WooCommerce settings field list
	 *
	 * @var array
	 */
	protected $fields_array;

	/**
	 * Use this replacement because the form fields is loaded before the save action
	 *
	 * @var string
	 */
	protected $config_url_replacement = '%config_url%';

	protected $notificator;

	/**
	 * Init the default field sections
	 *
	 * @param Gateway $gateway The gateway that call the logger.
	 */
	public function __construct( $gateway ) {
		$this->gateway     = $gateway;
		$this->notificator = new Simple_Notificator();

		$this->fields   = $this->get_fields();
		$this->sections = $this->get_sections();
	}

	/**
	 * Get the Asaas dashbord config page URL
	 *
	 * @return string The Asaas config URL.
	 */
	public function get_config_url() {
		$url_components = wp_parse_url( $this->gateway->get_option( 'endpoint' ) );

		if ( '' === $url_components['path'] ) {
			return '';
		}

		return $url_components['scheme'] . '://' . $url_components['host'] . '/config/index';
	}

	/**
	 * Define the default plugin field sections
	 */
	public function get_sections() {
		return apply_filters(
			'woocommerce_asaas_settings_sections', array(
				'default'       => array(
					'title'    => '',
					'priority' => 0,
				),
				'gateway'       => array(
					'title'    => __( 'Gateway', 'checkout-tabs-wp-ml' ),
					'priority' => 10,
				),
				'api'           => array(
					'title'    => __( 'API', 'checkout-tabs-wp-ml' ),
					'priority' => 20,
				),
				'subscriptions' => array(
					'title'    => __( 'Subscriptions', 'checkout-tabs-wp-ml' ),
					'priority' => 40,
				),
				'advanced'      => array(
					'title'    => __( 'Advanced Options', 'checkout-tabs-wp-ml' ),
					'priority' => 60,
				),
			),
			$this
		);
	}

	/**
	 * Shared fields between to billing types checkout
	 *
	 * @link https://docs.woocommerce.com/document/settings-api/
	 *
	 * @return array
	 */
	protected function get_fields() {
		$fields = array(
			'enabled'                 => array(
				'title'    => __( 'Enable/Disable', 'checkout-tabs-wp-ml' ),
				'type'     => 'checkbox',
				/* translators: %s: billing type name  */
				'label'    => sprintf( __( 'Enable %s', 'checkout-tabs-wp-ml' ), $this->gateway->get_type()->get_name() ),
				'default'  => 'no',
				'section'  => 'default',
				'priority' => 0,
			),
			'title'                   => array(
				'title'       => __( 'Title', 'checkout-tabs-wp-ml' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'checkout-tabs-wp-ml' ),
				/* translators: %s: billing type name  */
				'default'     => $this->gateway->get_type()->get_name(),
				'desc_tip'    => true,
				'section'     => 'default',
				'priority'    => 10,
			),
			'description'             => array(
				'title'       => __( 'Description', 'checkout-tabs-wp-ml' ),
				'type'        => 'textarea',
				'description' => __( 'This controls the description which the user sees during checkout.', 'checkout-tabs-wp-ml' ),
				'default'     => __( 'Pay your order using PIX.', 'checkout-tabs-wp-ml' ),
				'desc_tip'    => true,
				'section'     => 'default',
				'priority'    => 20,
			),
			'awaiting_payment_status' => array(
				'title'       => __( 'Open payment order status', 'checkout-tabs-wp-ml' ),
				'type'        => 'select',
				'description' => __( 'Status that the order will be saved when the customer makes a purchase and the order is not yet paid. <code>On hold</code> reduces stock, sends an email to the customer and to the shopkeeper. <code>Pending payment</code> does not reduce stock or send an email. This option is shared with other payment methods.', 'checkout-tabs-wp-ml' ),
				'shared'      => true,
				'section'     => 'default',
				'priority'    => 30,
				'options'     => array(
					'pending' => __( 'Pending payment', 'checkout-tabs-wp-ml' ),
					'on-hold' => __( 'On hold', 'checkout-tabs-wp-ml' ),
				),
			),
			'endpoint'                => array(
				'title'       => __( 'Enviroment', 'checkout-tabs-wp-ml' ),
				'type'        => 'select',
				'description' => __( 'Define the environment that will be used in your store\'s API calls. Select <code>Production</code> to use the actual transaction environment. If you are only testing the tool or are still in a testing period, select <code>Sandbox</code>. Each environment has its own key, remember to use the key corresponding to the selected environment.', 'checkout-tabs-wp-ml' ),
				'default'     => 'https://api.asaas.com/v3',
				'shared'      => true,
				'section'     => 'api',
				'priority'    => 10,
				'options'     => array(
					'https://api.asaas.com/v3'         => __( 'Production', 'checkout-tabs-wp-ml' ),
					'https://sandbox.asaas.com/api/v3' => __( 'Sandbox', 'checkout-tabs-wp-ml' ),
				),
			),
			'api_key'                 => array(
				'title'       => __( 'API Key', 'checkout-tabs-wp-ml' ),
				'type'        => $this->gateway->get_option( 'api_key', '' ) === '' ? 'text' : 'password',
				/* translators: %s: integration settings panel URL  */
				'description' => sprintf( __( 'The API Key used to connect with the gateway. <a href="%s">Click here</a> to get it.', 'checkout-tabs-wp-ml' ), $this->config_url_replacement ),
				'default'     => '',
				'shared'      => true,
				'section'     => 'api',
				'priority'    => 20,
			),
			'notification'            => array(
				'title'       => __( 'Notification between gateway and customer', 'checkout-tabs-wp-ml' ),
				'type'        => 'checkbox',
				'label'       => sprintf( __( 'Enable Notification', 'checkout-tabs-wp-ml' ), $this->gateway->get_type()->get_name() ),
				/* translators: %s: integration settings panel URL  */
				'description' => __( 'Allow the gateway to send email and SMS about the purchase and notify periodically while the purchase is not paid.', 'checkout-tabs-wp-ml' ),
				'default'     => 'no',
				'shared'      => true,
				'section'     => 'api',
				'priority'    => 30,
			),
			'debug'                   => array(
				'title'       => __( 'Debug log', 'checkout-tabs-wp-ml' ),
				'type'        => 'checkbox',
				'label'       => __( 'Enable logging', 'checkout-tabs-wp-ml' ),
				'default'     => 'yes',
				/* translators: %s: log page link */
				'description' => sprintf( __( 'Log API and webhook communication, inside %s.', 'checkout-tabs-wp-ml' ), $this->get_log_view() ),
				'section'     => 'advanced',
				'priority'    => 20,
			),
		);

		$shared_message = apply_filters( 'woocommerce_asaas_shared_option_message', __( 'This option is shared with another gateway.', 'checkout-tabs-wp-ml' ) );
		foreach ( $fields as &$field ) {
			if ( isset( $field['shared'] ) && true === $field['shared'] ) {
				$field['description'] .= ' ' . $shared_message;
			}
		}

		return apply_filters( 'woocommerce_asaas_settings_fields', $fields, $this );
	}

	/**
	 * Get fields to be showed in settings page
	 *
	 * @return array The fields in the WooCommerce checkout settings format
	 */
	public function fields() {
		if ( ! empty( $this->fields_array ) ) {
			return $this->fields_array;
		}

		$this->sort_sections();
		$this->add_fields_to_sections();
		$this->create_fields_array();

		return $this->fields_array;
	}

	/**
	 * Sort sections by priority
	 */
	public function sort_sections() {
		$this->sections = $this->sort_by_priority( $this->sections );
	}

	/**
	 * Sort the section fields by priority
	 *
	 * @param array $fields The fields list.
	 * @return array The fields list sorted
	 */
	public function sort_section_fields( $fields ) {
		return $this->sort_by_priority( $fields );
	}

	/**
	 * Sort an array by the priority key
	 *
	 * @global $wp_version string Check WP version for legacy code.
	 *
	 * @param array $list The array list.
	 * @return array The array sorted.
	 */
	private function sort_by_priority( $list ) {
		global $wp_version;

		$orderby       = array(
			'priority' => 'ASC',
		);
		$order         = 'ASC';
		$preserve_keys = true;

		// Legacy code support.
		if ( version_compare( $wp_version, '4.7.0', '<' ) ) {
			$util = new WP_List_Util( $list );
			return $util->sort( $orderby, $order, $preserve_keys );
		}

		return wp_list_sort( $list, $orderby, $order, $preserve_keys );
	}

	/**
	 * Add all fields to its respective section
	 */
	public function add_fields_to_sections() {
		foreach ( $this->sections as &$section ) {
			$section['fields'] = array();
		}

		foreach ( $this->fields as $id => $args ) {
			if ( empty( $args['section'] ) ) {
				$args['section'] = 'default';
			}

			if ( empty( $args['priority'] ) ) {
				$args['priority'] = 0;
			}

			$args['id']                                     = $id;
			$this->sections[ $args['section'] ]['fields'][] = $args;
		}
	}

	/**
	 * Prepare the fields array to be processed by WooCommerce
	 */
	public function create_fields_array() {
		$fields = array();

		foreach ( $this->sections as $id => $section ) {
			if ( ! empty( $section['title'] ) ) {
				$fields[] = $this->title_field( $section );
			}

			$section_fields = $this->sort_by_priority( $section['fields'] );
			$fields         = array_merge( $fields, array_combine( array_column( $section_fields, 'id' ), $section_fields ) );
		}

		$this->fields_array = $fields;
	}

	/**
	 * Create a WooCommerce settings title field
	 *
	 * @param array $section The section data.
	 * @return string[] The settings title field
	 */
	public function title_field( $section ) {
		$field         = $section;
		$field['type'] = 'title';
		return $field;
	}

	/**
	 * Replace config URL replacement to real value in fields that need
	 *
	 * @return array The fields array updated
	 */
	public function replace_config_url() {
		$config_url = $this->get_config_url();

		foreach ( $this->fields_array as $key => $field ) {
			if ( isset( $field['description'] ) && false !== strpos( $field['description'], $this->config_url_replacement ) ) {
				$this->fields_array[ $key ]['description'] = str_replace( $this->config_url_replacement, $config_url, $field['description'] );
			}
		}

		return $this->fields_array;
	}

	/**
	 * Get log view
	 *
	 * @return string The HTML with where get the log
	 */
	protected function get_log_view() {
		return '<a href="' . esc_url( admin_url( 'admin.php?page=wc-status&tab=logs&log_file=' . esc_attr( $this->gateway->id ) . '-' . sanitize_file_name( wp_hash( $this->gateway->id ) ) . '.log' ) ) . '">' . __( 'Status &gt; Logs', 'checkout-tabs-wp-ml' ) . '</a>';
	}

	/**
	 * Is tokenization available?
	 *
	 * @return bool True if tokenization is available for this account. Otherwise, false.
	 */
	public function is_tokenization_available() {
		$api      = new Api( $this->gateway );
		$response = $api->credit_card()->tokenize( array() );
		if ( is_array( $response->errors ) && 0 < count( $response->errors ) ) {
			// invalid_customer = tokenization enabled / forbidden = tokenization disabled.
			return 'invalid_customer' === $response->errors[0]->code;
		}
		return false;
	}

	public function notificator() {
		return $this->notificator;
	}
}
