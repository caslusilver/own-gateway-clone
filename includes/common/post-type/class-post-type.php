<?php

namespace WC_Asaas\Common\Post_Type;

abstract class Post_Type {

	abstract public function slug(): string;

	public function args(): array {
		return array();
	}
}
