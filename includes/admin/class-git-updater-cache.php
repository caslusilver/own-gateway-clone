<?php
/**
 * Git Updater cache refresh integration.
 *
 * @package WooAsaas
 */

namespace WC_Asaas\Admin;

use Error;
use Exception;
use Fragen\Singleton;

/**
 * Add refresh cache link and AJAX handler.
 */
class Git_Updater_Cache {
	/**
	 * Singleton instance.
	 *
	 * @var self|null
	 */
	protected static $instance = null;

	/**
	 * Get instance.
	 *
	 * @return self
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		add_filter( 'plugin_row_meta', array( $this, 'add_refresh_cache_link' ), 10, 2 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'wp_ajax_gu_refresh_cache', array( $this, 'handle_refresh_cache' ) );
	}

	/**
	 * Add "Atualizar Cache" link on plugins list.
	 *
	 * @param array  $plugin_meta Existing plugin meta.
	 * @param string $plugin_file Plugin file.
	 * @return array
	 */
	public function add_refresh_cache_link( $plugin_meta, $plugin_file ) {
		$expected_basename = plugin_basename( OWN_GATEWAY_CLONE_PLUGIN_FILE );
		if ( $plugin_file !== $expected_basename ) {
			return $plugin_meta;
		}

		if ( ! class_exists( 'Fragen\\Singleton' ) ) {
			return $plugin_meta;
		}

		$nonce = wp_create_nonce( 'gu-refresh-cache' );
		$plugin_meta[] = sprintf(
			'<a href="#" class="gu-refresh-cache-btn" data-nonce="%s">' .
			'<span class="dashicons dashicons-update" style="font-size:16px;vertical-align:middle;margin-right:3px;"></span>' .
			'<span class="gu-refresh-text">Atualizar Cache</span>' .
			'<span class="spinner" style="float:none;margin:0 0 0 5px;visibility:hidden;"></span>' .
			'</a>',
			esc_attr( $nonce )
		);

		return $plugin_meta;
	}

	/**
	 * Enqueue assets for plugins.php.
	 *
	 * @param string $hook Current admin screen hook.
	 */
	public function enqueue_assets( $hook ) {
		if ( 'plugins.php' !== $hook ) {
			return;
		}

		wp_enqueue_script(
			'own-gateway-clone-gu-refresh-cache',
			plugin_dir_url( OWN_GATEWAY_CLONE_PLUGIN_FILE ) . 'assets/dist/own-gateway-clone-refresh-cache.js',
			array( 'jquery' ),
			OWN_GATEWAY_CLONE_VERSION,
			true
		);

		wp_localize_script(
			'own-gateway-clone-gu-refresh-cache',
			'GURefreshCache',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			)
		);
	}

	/**
	 * AJAX handler to refresh Git Updater cache.
	 */
	public function handle_refresh_cache() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Sem permissao para executar esta acao.' );
		}

		check_ajax_referer( 'gu-refresh-cache', '_ajax_nonce' );

		if ( ! class_exists( 'Fragen\\Singleton' ) ) {
			wp_send_json_error( 'Git Updater nao esta instalado ou ativo.' );
		}

		try {
			$settings = Singleton::get_instance(
				'Fragen\\Git_Updater\\Settings',
				new \stdClass()
			);

			if ( ! is_object( $settings ) || ! method_exists( $settings, 'delete_all_cached_data' ) ) {
				wp_send_json_error( 'Metodo delete_all_cached_data nao encontrado no Git Updater.' );
			}

			$settings->delete_all_cached_data();
			wp_cron();

			wp_send_json_success( 'Cache atualizado com sucesso!' );
		} catch ( Exception $e ) {
			wp_send_json_error( 'Erro ao atualizar cache: ' . $e->getMessage() );
		} catch ( Error $e ) {
			wp_send_json_error( 'Erro fatal ao atualizar cache: ' . $e->getMessage() );
		}
	}
}
