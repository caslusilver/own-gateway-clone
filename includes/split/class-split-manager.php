<?php

namespace WC_Asaas\Split;

use Exception;
use WC_Asaas\Split\Hook\Split_Assets_Hook;
use WC_Asaas\Split\Hook\Split_Wallet_Save_Hook;
use WC_Asaas\Split\Hook\Split_Settings_Hook;
use WC_Asaas\Split\Hook\Split_Checkout_Hook;
use WC_Asaas\Split\Hook\Split_Plugins_List_Hook;
use WC_Asaas\Split\Hook\Split_Wallet_Admin_Table_Hook;
use WC_Asaas\Split\Hook\Split_Wallet_Edit_Page_Hook;
use WC_Asaas\Split\Hook\Split_Wallet_Post_Type_Hook;

class Split_Manager {

	protected static $instance = null;

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		( new Split_Assets_Hook() );
		( new Split_Checkout_Hook() );
		( new Split_Plugins_List_Hook() );
		( new Split_Settings_Hook() );
		( new Split_Wallet_Admin_Table_Hook() );
		( new Split_Wallet_Edit_Page_Hook() );
		( new Split_Wallet_Post_Type_Hook() );
		( new Split_Wallet_Save_Hook() );
	}

	private function __clone() {
	}

	public function __wakeup() {
		throw new Exception( esc_html__( 'Cannot unserialize singleton', 'woo-asaas' ) );
	}
}
