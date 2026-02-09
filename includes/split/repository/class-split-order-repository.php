<?php

namespace WC_Asaas\Split\Repository;

use WC_Asaas\Split\Adapter\Split_Asaas_Api_Data_To_Order_Data_Adapter;
use WC_Order;

class Split_Order_Repository {

	const ORDER_ASAAS_DATA_KEY = '__ASAAS_ORDER';

	public function split_data( WC_Order $order ) {
		$meta_value     = $order->get_meta( self::ORDER_ASAAS_DATA_KEY );
		$asaas_api_data = json_decode( $meta_value );

		$order_splits = array();
		foreach ( $asaas_api_data->split as $split ) {
			$order_splits[] = ( new Split_Asaas_Api_Data_To_Order_Data_Adapter( $split ) )->adapt();
		}

		return $order_splits;
	}
}
