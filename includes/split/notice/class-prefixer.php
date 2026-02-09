<?php

namespace WC_Asaas\Split\Notice;

class Prefixer {

	public function prefix( $message ) {
		return '<strong>' . __( 'Split:', 'woo-asaas' ) . '</strong> ' . $message;
	}
}
