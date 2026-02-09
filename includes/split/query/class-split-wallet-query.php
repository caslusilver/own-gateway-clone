<?php

namespace WC_Asaas\Split\Query;

use WC_Asaas\Split\Data\Split_Wallet_Ready_Data_WP_Post_Factory;
use WC_Asaas\Split\Post_Type\Asaas_Wallet_Post_Type;
use WP_Query;

class Split_Wallet_Query {

	protected $query;

	public function __construct() {
		$post_type = ( new Asaas_Wallet_Post_Type() )->slug();

		$this->query = new WP_Query();
		$this->query->set( 'post_type', $post_type );
		$this->query->set( 'posts_per_page', -1 );
		$this->query->set( 'no_found_rows', true );
	}

	public function nickname( string $nickname ) {
		$this->query->set( 'title', $nickname );

		return $this;
	}

	public function asaas_wallet_id( string $asaas_wallet_id ) {
		$this->query->set(
			'meta_query', array(
				array(
					'key'     => 'wallet_id',
					'value'   => $asaas_wallet_id,
					'compare' => '=',
				),
			)
		);

		return $this;
	}

	public function ignore_post( $post ) {
		$not_in_key   = 'post__not_in';
		$not_in_value = $this->query->get( $not_in_key );

		if ( '' === $not_in_value ) {
			$not_in_value = array();
		}

		$not_in_value[] = $post->ID;

		$this->query->set( $not_in_key, $not_in_value );

		return $this;
	}

	public function results() {
		$results    = $this->query->get_posts();
		$collection = array();
		foreach ( $results as $post ) {
			$wallet       = ( new Split_Wallet_Ready_Data_WP_Post_Factory() )->create( $post );
			$collection[] = $wallet;
		}

		return $collection;
	}

	public function count() {
		$this->query->set( 'fields', 'ids' );

		$this->query->get_posts();

		return $this->query->post_count;
	}
}
