<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="notice notice-<?php echo esc_attr( $notice->status() ); ?> is-dismissible">
	<p>
		<?php
		echo wp_kses_post( $notice->message() );
		?>
	</p>
</div>
