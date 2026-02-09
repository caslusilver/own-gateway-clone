<?php

namespace WC_Asaas\Common\Metabox;

abstract class Meta_Box {

	abstract public function title();

	abstract public function render();

	public function context() {
		return 'advanced';
	}
}
