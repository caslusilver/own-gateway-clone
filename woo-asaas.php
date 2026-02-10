<?php
/**
 * Plugin Name:     own-gateway-clone
 * Plugin URI:      https://github.com/caslusilver/own-gateway-clone
 * Description:     Gateway de pagamentos via PIX com webhook para WooCommerce.
 * Author:          Lucas Andrade / AI
 * Author URI:      https://github.com/caslusilver
 * License:         GPL v2 or later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     checkout-tabs-wp-ml
 * Domain Path:     /languages
 * Version: 0.0.2
 * GitHub Plugin URI: caslusilver/own-gateway-clone
 * Primary Branch:  main
 *
 * @package         WooAsaas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'OWN_GATEWAY_CLONE_VERSION', '0.0.2' );
define( 'OWN_GATEWAY_CLONE_PLUGIN_FILE', __FILE__ );

require_once 'autoload.php';

add_action( 'plugins_loaded', array( \WC_Asaas\WC_Asaas::class, 'get_instance' ) );
