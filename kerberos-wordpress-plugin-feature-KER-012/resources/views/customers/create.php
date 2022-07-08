<div class="wrap">
	<h1><?php _e('Add new Booking', 'kerberos'); ?>
	</h1>

	<form action="" method="post">

		<table class="form-table">
			<tbody>
				<tr class="row-date">
					<th scope="row">
						<label for="datetime"><?php _e('Date', 'kerberos'); ?></label>
					</th>
					<td>
						<input type="date" name="date" id="date" class="regular-text" placeholder="" value=""
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
							value="" required="required" />
					</td>
				</tr>
				<tr class="row-service-id">
					<th scope="row">
						<label for="service_id"><?php _e('Service', 'kerberos'); ?></label>
					</th>
					<td>
						<input type="number" name="service_id" id="service_id" class="regular-text"
							placeholder="<?php echo esc_attr('Service', 'kerberos'); ?>"
							value="" required="required" />
					</td>

				</tr>
				<tr>
					<th scope="row">
						<label for="customer_id"><?php _e('Customer', 'kerberos'); ?></label>
					</th>
					<td>
						<input type="number" name="customer_id" id="customer_id" class="regular-text"
							placeholder="<?php echo esc_attr('Customer', 'kerberos'); ?>"
							value="" required="required" />
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="service_id"><?php _e('Provider', 'kerberos'); ?></label>
					</th>
					<td>
						<input type="number" name="provider_id" id="provider_id" class="regular-text"
							placeholder="<?php echo esc_attr('Provider', 'kerberos'); ?>"
							value="" required="required" />
					</td>
				</tr>
				<tr class="row-notes">
					<th scope="row">
						<label for="notes"><?php _e('Notes', 'kerberos'); ?></label>
					</th>
					<td>
						<textarea type="text" name="notes" id="notes" class="regular-text"
							placeholder="<?php echo esc_attr('Notes', 'kerberos'); ?>"
							value="" required="required" />
						</textarea>
					</td>
				</tr>
				<tr class="row-status">
					<th scope="row">
						<label for="status"><?php _e('Status', 'kerberos'); ?></label>
					</th>
					<td>
						<select name="status" id="status" required="required">
							<option value="pending"><?php echo __('Pending', 'kerberos'); ?>
							</option>
							<option value="approved"><?php echo __('Approved', 'kerberos'); ?>
							</option>
							<option value="rescheduled"><?php echo __('Rescheduled', 'kerberos'); ?>
							</option>
							<option value="cancelled"><?php echo __('Cancelled', 'kerberos'); ?>
							</option>
						</select>
					</td>
				</tr>
			</tbody>
		</table>

		<input type="hidden" name="field_id" value="0">

		<?php wp_nonce_field(''); ?>
		<?php submit_button(__('Add New Booking', 'kerberos'), 'primary', 'submit_booking'); ?>

	</form>
</div>
