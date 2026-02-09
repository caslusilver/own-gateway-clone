<?php

namespace WC_Asaas\Split\Service;

use WC_Asaas\Split\Repository\Split_Order_Repository;
use WC_Order;

class Split_WC_Order_Service {

	public function add_split_info_note( WC_Order $wc_order ) {
		$split_order_data = ( new Split_Order_Repository() )->split_data( $wc_order );

		if ( 0 === count( $split_order_data ) ) {
			return;
		}

		$note_message = __( 'Split', 'woo-asaas' ) . PHP_EOL;
		foreach ( $split_order_data as $data ) {
			$note_message .= sprintf(
				// translators: %1$s%% is the percentual value, %2$s is the wallet nickname, %3$s is the wallet ID.
				__( '%1$s%% for wallet %2$s Wallet ID: %3$s', 'woo-asaas' ),
				$data->value(),
				$data->wallet()->nickname(),
				$data->wallet()->asaas_id()
			) . PHP_EOL;
		}

		$wc_order->add_order_note( $note_message );
	}
}
