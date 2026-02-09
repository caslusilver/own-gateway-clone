<?php

namespace WC_Asaas\Split\Data;

class Split_Types {

	private $types;

	public function __construct() {
		$this->types = $this->init_types();
	}

	private function init_types() {
		return [
			'percentage'  => [
				'label' => __( 'Percentage', 'woo-asaas' ),
				'value' => 'percentage',
			],
			'fixed_value' => [
				'label' => __( 'Fixed value', 'woo-asaas' ),
				'value' => 'fixed_value',
			],
		];
	}

	public function type( string $value ) {
		return $this->types[ $value ];
	}

	public function all_types() {
		return $this->types;
	}
}
