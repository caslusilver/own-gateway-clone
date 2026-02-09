<?php

namespace WC_Asaas\Common\Notice\Notificator;

use WC_Asaas\Common\Notice\Data\Notice_Data;

class Simple_Notificator extends Notificator {

	public function add( Notice_Data $notice_data ) {
		$this->notices[] = $notice_data;
	}
}
