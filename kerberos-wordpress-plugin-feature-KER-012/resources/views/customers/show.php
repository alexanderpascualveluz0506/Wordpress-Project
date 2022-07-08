<div class="wrap">
	<h1><?php _e('Customer Information', 'kerberos'); ?>
	</h1>

	<?php $item = ker_get_customer($id); ?>

	<form action="" method="post">

		<table class="form-table">
			<tbody>
				<tr class="row-display_name">
					<th scope="row">
						<label for="display_name"><?php _e('Name', 'kerberos'); ?></label>
					</th>
					<td>
						<input type="text" name="display_name" id="display_name" class="regular-text"
							placeholder="<?php echo esc_attr('', 'kerberos'); ?>"
							value="<?php echo esc_attr($item->display_name); ?>"
							required="required" />
					</td>
				</tr>
				<tr class="row-user-email">
					<th scope="row">
						<label for="user_email"><?php _e('Email', 'kerberos'); ?></label>
					</th>
					<td>
						<input type="text" name="user_email" id="user_email" class="regular-text"
							placeholder="<?php echo esc_attr('', 'kerberos'); ?>"
							value="<?php echo esc_attr($item->user_email); ?>"
							required="required" />
					</td>
				</tr>
				
			</tbody>
		</table>

		<input type="hidden" name="field_id"
			value="<?php echo $item->id; ?>">

		<?php wp_nonce_field(''); ?>
		<?php submit_button(__('Update Customer', 'kerberos'), 'primary', 'submit_booking'); ?>

	</form>
</div>
