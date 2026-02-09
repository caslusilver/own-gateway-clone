<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<table class="form-table" role="presentation">
	<tbody>
	<tr>
		<th scope="row">
			<label for="wallet_id">
				<?php
				echo esc_html__( 'Wallet ID:', 'woo-asaas' );
				?>
			</label>
		</th>
		<td>
			<input type="text" name="wallet_id" id="wallet_id" class="regular-text" value="<?php echo esc_attr( $wallet_id ); ?>" />
		</td>
	</tr>
	</tbody>
</table>
