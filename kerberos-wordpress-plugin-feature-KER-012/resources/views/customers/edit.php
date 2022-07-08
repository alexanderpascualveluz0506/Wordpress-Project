<div class="wrap">
	<h1><?php _e('Edit a Booking', 'kerberos'); ?>
	</h1>

	<?php $item = ker_get_booking($id); ?>

	<form action="" method="post">

		<table class="form-table">
			<tbody>
				<tr class="row-date">
					<th scope="row">
						<label for="date"><?php _e('Date', 'kerberos'); ?></label>
					</th>
					<td>
						<input type="date" name="date" id="date" class="regular-text"
							placeholder="<?php echo esc_attr('', 'kerberos'); ?>"
							value="<?php echo esc_attr($item->date); ?>"
							required="required" />
					</td>
				</tr>
				<tr class="row-time">
					<th scope="row">
						<label for="time"><?php _e('Time', 'kerberos'); ?></label>
					</th>
					<td>
						<input type="time" name="time" id="time" class="regular-text"
							placeholder="<?php echo esc_attr('', 'kerberos'); ?>"
							value="<?php echo esc_attr($item->time); ?>"
							required="required" />
					</td>
				</tr>
				<tr class="row-notes">
					<th scope="row">
						<label for="notes"><?php _e('Notes', 'kerberos'); ?></label>
					</th>
					<td>
						<textarea name="notes" id="notes" class="regular-text"
							placeholder="<?php echo esc_attr('', 'kerberos'); ?>"
							required="required" /><?php echo esc_attr($item->notes); ?>
						</textarea>
					</td>
				</tr>
				<tr class="row-status">
					<th scope="row">
						<label for="status"><?php _e('Status', 'kerberos'); ?></label>
					</th>
					<td>
						<select name="status" id="status" required="required">
							<option value="1" <?php selected($item->status, '1'); ?>><?php echo __('Pending', 'kerberos'); ?>
							</option>
							<option value="2" <?php selected($item->status, '2'); ?>><?php echo __('Approved', 'kerberos'); ?>
							</option>
							<option value="3" <?php selected($item->status, '3'); ?>><?php echo __('Rescheduled', 'kerberos'); ?>
							</option>
							<option value="4" <?php selected($item->status, '4'); ?>><?php echo __('Cancelled', 'kerberos'); ?>
							</option>
						</select>
					</td>
				</tr>
				<tr class="row-service-id">
					<th scope="row">
						<label for="service_id"><?php _e('Service', 'kerberos'); ?></label>
					</th>
					<td>
						<input type="number" name="service_id" id="service_id" class="regular-text"
							placeholder="<?php echo esc_attr('', 'kerberos'); ?>"
							value="<?php echo esc_attr($item->service_id); ?>"
							required="required" />
					</td>
				</tr>
			</tbody>
		</table>

		<input type="hidden" name="field_id"
			value="<?php echo $item->id; ?>">

		<?php wp_nonce_field(''); ?>
		<?php submit_button(__('Update Booking', 'kerberos'), 'primary', 'submit_booking'); ?>

	</form>
</div>
