<?php

namespace WC_Asaas\Common\Notice;

use WC_Asaas\Common\Notice\Data\Notice_Data;

interface Code_Notice_Provider {
	public function notice_from_code( int $code);
}
