<?php
/**
 * Admin view class
 *
 * @package WooAsaas
 */

namespace WC_Asaas\Admin;

use Exception;

/**
 * Admin view class
 */
class View {

	/**
	 * Instance of this class
	 *
	 * @var self
	 */
	protected static $instance = null;

	/**
	 * Block external object instantiation
	 */
	private function __construct() {}

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
	 * Get the admin views directory path
	 *
	 * @param string $module Module name.
	 * @return string The absolute path to the template directory.
	 */
	public function get_template_path( $module = 'admin' ) : string {
		$os_separator = DIRECTORY_SEPARATOR;
		$base_dir = __DIR__ . "{$os_separator}views{$os_separator}";

		if ( 'admin' === $module ) {
			return $base_dir ;
		}

		$regex_delimiter = '/';
		$os_separator_escaped = preg_quote( $os_separator, $regex_delimiter );

		$base_dir = preg_replace(
			"{$regex_delimiter}includes{$os_separator_escaped}admin{$os_separator_escaped}views{$os_separator_escaped}{$regex_delimiter}",
			"includes{$os_separator}{$module}",
			$base_dir
		);
		$valid_dirs = array(
			$base_dir . "{$os_separator}views{$os_separator}",
			$base_dir . "{$os_separator}admin{$os_separator}views{$os_separator}",
		);

		foreach ( $valid_dirs as $dir ) {
			if ( is_dir( $dir ) ) {
				return $dir;
			}
		}

		throw new Exception( 'The views directory was not found.' );
	}

	/**
	 * Load a template file
	 *
	 * @param string $file Template file name.
	 * @param string $module Module name.
	 */
	public function load_template_file( $file, $module = 'admin' ) {
		require $this->get_template_path( $module ) . $file;
	}

	/**
	 * Get a template file
	 *
	 * A template can be overwritten by the theme. Add the templates file in `woocommerce/asaas` in your theme.
	 *
	 * @param string  $template_name The template file name.
	 * @param array   $args The template variables.
	 * @param boolean $return Return the template HTML if is true. Otherwise, print the template.
	 * @param string  $module Module name.
	 * @return string
	 */
	public function get_template_file( $template_name, $args = array(), $return = false, $module = 'admin' ) {
		if ( $return ) {
			return wc_get_template_html(
				$template_name,
				$args,
				'woocommerce/asaas/',
				self::get_instance()->get_template_path( $module )
			);
		}

		wc_get_template(
			$template_name,
			$args,
			'woocommerce/asaas/',
			self::get_instance()->get_template_path( $module )
		);
	}
}
