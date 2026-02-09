<?php
/**
 * Anticipation Settings Section class
 *
 * @package WooAsaas
 */

namespace WC_Asaas\Anticipation\Admin\Settings;

use Exception;
use WC_Asaas\Admin\Settings\Settings;

/**
 * Anticipation section fields for gateway settings
 */
class Anticipation_Settings_Fields {
	/**
	 * Instance of this class
	 *
	 * @var self
	 */
	protected static $instance = null;

	/**
	 * The Credit Card gateway id
	 *
	 * @var string $gateway_id.
	 */
	private $gateway_id;


	/**
	 * Is not allowed to call from outside to prevent from creating multiple instances.
	 */
	private function __construct() {
		$setting_option = "woocommerce_{$this->gateway_id}_settings";

		add_filter( "pre_update_option_{$setting_option}", array( $this, 'enable_disable_anticipation' ), 10, 3 );
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
	 * Add anticipation gateway settings section.
	 *
	 * @param array $sections Gateway sections.
	 * @return array
	 */
	public function add_section( $sections ) {
		return array_merge(
			$sections,
			array(
				'anticipation' => array(
					'title'    => __( 'Receivable Anticipation', 'woo-asaas' ),
					'priority' => 10,
				),
			)
		);
	}

	/**
	 * Add anticipation gateway settings fields.
	 *
	 * @param array    $fields Gateway fields.
	 * @param Settings $settings Gateway settings object.
	 * @return array
	 */
	public function add_fields( $fields, $settings ) {
		$gateway = $settings->gateway;
		$api_key = $gateway->settings['api_key'];

		/* translators: This message explains what the enabled setting does. */
		$enabled_description = sprintf( __( 'Automatically request advance payments on Asaas <a href="%s" target="_blank" rel="noopener">Click here for more details</a>.', 'woo-asaas' ), 'https://www.asaas.com/anticipation/index' );
		/* translators: This message explains what needs to be done when the setting is disabled. */
		$disabled_description = sprintf( __( 'To use this functionality, inform <a href="%s">API Key</a> below.', 'woo-asaas' ), '#woocommerce_asaas-credit-card_3' );

		$description = $disabled_description;
		if ( '' !== $api_key ) {
			$description = $enabled_description;
		}

		$anticipation_fields = array(
			'anticipation' => array(
				'title'       => __( 'Enable automatic anticipation', 'woo-asaas' ),
				'type'        => 'checkbox',
				'label'       => __( 'Enable', 'woo-asaas' ),
				'description' => $description,
				'disabled'    => true,
				'default'     => 'no',
				'section'     => 'anticipation',
				'priority'    => 50,
			),
		);

		if ( '' === $api_key ) {
			$anticipation_fields['anticipation']['disabled'] = true;
		}

		return array_merge( $fields, $anticipation_fields );
	}

	/**
	 * Disable anticipation if the environment value changes or if the API key is empty in the settings form.
	 *
	 * @param string $value The new value.
	 * @param string $old_value The value before the change.
	 * @param string $option The option name that was updated.
	 */
	public function enable_disable_anticipation( $value, $old_value, $option ) {
		if ( ! isset( $value['endpoint'], $old_value['endpoint'], $value['api_key'], $value['anticipation'] ) ) {
			return $value;
		}

		$environment_changed = $value['endpoint'] !== $old_value['endpoint'];
		$empty_api_key       = '' === $value['api_key'];

		if ( $environment_changed || $empty_api_key ) {
			$value['anticipation'] = 'no';
		}

		return $value;
	}
}
