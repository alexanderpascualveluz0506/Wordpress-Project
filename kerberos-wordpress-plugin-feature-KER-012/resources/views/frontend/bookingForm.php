<?php
if (isset($_POST['submit_booking'])){
    $date = isset($_POST['date']) ? sanitize_text_field($_POST['date']) : '';
    $time = isset($_POST['time']) ? sanitize_text_field($_POST['time']) : '';
    $service_id = isset($_POST['service_id']) ? intval($_POST['service_id']) : 0;
    $provider_id = isset($_POST['provider_id']) ? intval($_POST['provider_id']) : 0;
    $customer_id = isset($_POST['customer_id']) ? intval($_POST['customer_id']) : 0;
    $notes = isset($_POST['notes']) ? sanitize_text_field($_POST['notes']) : '';
    $status = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : '';
    $datetime = $date . '  ' . $time . ':00';


    $insert_result = $wpdb->insert(
        $wpdb->prefix . 'kerberos',
        array(
            'created_at'=>current_time('mysql'),
            'updated_at'=>current_time('mysql'),
            'datetime' => $datetime,
            'service_id' => $service_id,
            'customer_id' => $customer_id,
            'provider_id' => $provider_id,
            'notes' => $notes,
            'status' => $status,
        )
    );

    if (!$insert_result) {
        echo "<div class='sub-failure'>Database error.</div>";			
    }
    
    echo "<p align='center'>successful</p>";
}

?>



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
                    <select name="service_id" id="service_id" required="required">
                        <?php
                        global $wpdb;
                        $results= $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'services');
                            foreach ($results as $result):?>
                                <option value=<?php echo $result->id ?>> <?php echo esc_attr($result->service_name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="customer_id"><?php _e('Customer', 'kerberos'); ?></label>
                </th>
                <td>
                    <select name="customer_id" id="customer_id" required="required">
                        <?php
                        global $wpdb;
                        $results= $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'users');
                            foreach ($results as $result):?>
                                <option value=<?php echo $result->ID ?>> <?php echo esc_attr($result->display_name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="provider_id"><?php _e('Provider', 'kerberos'); ?></label>
                </th>
                <td>
                    <select name="provider_id" id="provider_id" required="required">
                        <?php
                            global $wpdb;
                            $results= $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'users');
                                foreach ($results as $result):?>
                                    <option value=<?php echo $result->ID ?>> <?php echo esc_attr($result->display_name) ?></option>
                        <?php endforeach; ?>
                    </select>
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
   <input type="submit" value="Add New Booking" name="submit_booking" >

</form>
</div>
