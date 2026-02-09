<?php

namespace WC_Asaas\Split\Notice;

use WC_Asaas\Common\Notice\Code_Notice_Provider;
use WC_Asaas\Common\Notice\Data\Notice_Data;
use WC_Asaas\Split\Split_Message_List;

class Wallet_Notice_Provider implements Code_Notice_Provider {

	private $message_list;

	private $prefixer;

	public function __construct() {
		$this->message_list = new Split_Message_List();
		$this->prefixer     = new Prefixer();
	}

	public function notice_from_code( int $code ) {
		$messages = $this->notices();

		if ( isset( $messages[ $code ] ) ) {
			return $messages[ $code ];
		}

		$message = $this->prefixer->prefix( $this->message_list->message_from_code( $code ) );

		return new Notice_Data( Notice_Data::STATUS_ERROR, $message, 1 );
	}

	private function notices(): array {
		return array(
			Split_Message_List::SAVED_SUCCESSFULLY => new Notice_Data(
				Notice_Data::STATUS_SUCCESS,
				$this->prefixer->prefix( $this->message_list->message_from_code( Split_Message_List::SAVED_SUCCESSFULLY ) ),
				99
			),
			Split_Message_List::KEPT_AS_DRAFT      => new Notice_Data(
				Notice_Data::STATUS_WARNING,
				$this->prefixer->prefix( $this->message_list->message_from_code( Split_Message_List::KEPT_AS_DRAFT ) ),
				10
			),
			Split_Message_List::ERROR_ON_UPDATE    => new Notice_Data(
				Notice_Data::STATUS_ERROR,
				$this->prefixer->prefix( $this->message_list->message_from_code( Split_Message_List::ERROR_ON_UPDATE ) ),
				1
			),
		);
	}
}
