<?php

namespace WC_Asaas\Common\Notice\Notificator;

use WC_Asaas\Admin\View;

abstract class Notificator {

	protected $notices = array();

	public function render() {
		foreach ( $this->ordered_notices() as $notice ) {
			View::get_instance()->get_template_file(
				'notice.php',
				array(
					'notice' => $notice,
				),
				false,
				'common/notice'
			);
		}
	}

	public function ordered_notices() {
		$notices = $this->notices;

		uasort(
			$notices, function ( $a, $b ) {
				return $a->priority() <=> $b->priority();
			}
		);

		return $notices;
	}
}
