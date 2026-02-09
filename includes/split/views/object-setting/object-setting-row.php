<tr class="object-setting-table__row" data-index="<?php echo esc_attr( $key ); ?>">
	<td>
		<select style="min-width: 100%"
			name="<?php echo esc_attr( $field_key ); ?>[<?php echo esc_attr( $key ); ?>][walletPostId]"
			id="<?php echo esc_attr( $field_key ); ?>[<?php echo esc_attr( $key ); ?>][walletPostId]"
			class="select short">
			<option value=""></option>
			<?php foreach ( $wallets as $wallet ) : ?>
				<option value="<?php echo esc_attr( $wallet->post()->ID ); ?>"
					<?php echo ( ! is_null( $register ) && $register->wallet_id() === $wallet->post()->ID ) ? 'selected' : ''; ?>>
					<?php echo esc_html( $wallet->nickname() ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</td>
	<td>
		<input style="min-width: 95%"
			name="<?php echo esc_attr( $field_key ); ?>[<?php echo esc_attr( $key ); ?>][percentualValue]"
			id="<?php echo esc_attr( $field_key ); ?>[<?php echo esc_attr( $key ); ?>][percentualValue]"
			class="small-input"
			type="text"
			placeholder="0"
			value="<?php echo ! is_null( $register ) ? esc_attr( $register->value() ) : ''; ?>" />
		<span class="wallet-value-after">%</span>
	</td>
	<td>
		<button style="min-width: 100%" class="button-secondary remove-wallet-row"
			data-index="<?php echo esc_attr( $key ); ?>"><?php esc_html_e( 'Delete row', 'woo-asaas' ); ?></button>
	</td>
</tr>
