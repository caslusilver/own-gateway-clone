<?php

use WC_Asaas\Admin\View;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<tr valign="top">
	<th scope="row" class="titledesc">
		<label for="<?php echo esc_attr( $field_config['title'] ); ?>">
			<?php echo esc_html( $field_config['title'] ); ?>
		</label>
	</th>
	<td class="forminp">
		<table class="wc_gateways widefat" cellspacing="0">
			<thead>
				<tr>
					<th class="name"><?php esc_html_e( 'Wallet', 'woo-asaas' ); ?></th>
					<?php if ( ! $is_global ) : ?>
						<th class="name"><?php esc_html_e( 'Application type', 'woo-asaas' ); ?></th>
					<?php endif; ?>
					<th class="name"><?php esc_html_e( 'Value', 'woo-asaas' ); ?></th>
					<th class="name"></th>
				</tr>
			</thead>
			<tbody id="split-wallet-table-list">
				<?php $no_wallet_hide_class = count( $registered_wallets ) > 0 ? ' object-setting-table__no-wallets--hidden' : ''; ?>
				<tr class="object-setting-table__no-wallets<?php echo esc_attr( $no_wallet_hide_class ); ?>">
					<td colspan="3"><?php esc_html_e( 'No wallet registered. Split will not be applied to purchases.', 'woo-asaas' ); ?></td>
				</tr>
				<?php foreach ( $registered_wallets as $key => $register ) : ?>
					<?php
					$args = array(
						'key'       => $key,
						'field_key' => $field_key,
						'wallets'   => $wallets,
						'register'  => $register,
					);

					View::get_instance()->get_template_file( 'object-setting/object-setting-row.php', $args, false, 'split' );
					?>
				<?php endforeach; ?>
			</tbody>
		</table>
		<template id="wallet-row-template">
			<?php
			$args = array(
				'key'       => '{index}',
				'field_key' => $field_key,
				'wallets'   => $wallets,
				'register'  => null,
			);

			View::get_instance()->get_template_file( 'object-setting/object-setting-row.php', $args, false, 'split' );
			?>
		</template>
		<button id="add-wallet" class="button-secondary" style="margin-top:10px"><?php esc_html_e( 'Add new wallet', 'woo-asaas' ); ?></button>
	</td>
</tr>
