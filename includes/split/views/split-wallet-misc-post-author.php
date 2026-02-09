<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="misc-pub-section misc-pub-post-author">
	<?php
	echo esc_html( __( 'Created by', 'woo-asaas' ) );
	?>
	: <span id="post-status-display">
		<?php
		echo esc_html( get_the_author_meta( 'display_name', $author ) );
		?>
	</span>
</div>
