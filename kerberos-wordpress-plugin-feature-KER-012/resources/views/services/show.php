<div class="wrap">
    <h1><?php _e('Service Information', 'kerberos'); ?>
    </h1>

    <?php $item = ker_get_service($id); ?>

    <form action="" method="post">

        <table class="form-table">
            <tbody>
            <tr class="row-name">
                    <th scope="row">
                        <label for="name"><?php _e('Name', 'kerberos'); ?></label>
                    </th>
                    <td>
                        <input type="text" name="name" id="name" class="regular-text"
                            placeholder="<?php echo esc_attr('', 'kerberos'); ?>"
                            value="<?php echo esc_attr($item->service_name); ?>"
                            required="required" />
                    </td>
                </tr>
                <tr class="row-description">
                    <th scope="row">
                        <label for="description"><?php _e('Description', 'kerberos'); ?></label>
                    </th>
                    <td>
                        <textarea name="description" id="description" class="regular-text"
                            placeholder="<?php echo esc_attr('', 'kerberos'); ?>"
                            required="required" /><?php echo esc_attr($item->service_description); ?>
                        </textarea>
                    </td>
                </tr>
                <tr class="row-image">
                    <th scope="row">
                        <label for="image"><?php _e('Image', 'kerberos'); ?></label>
                    </th>
                    <td>
                    <button class="upload_service_image">Upload Logo</button>
                        <input type="hidden" name="file_image" class="regular-text" id="service-image-url" value="<?php echo esc_attr($item->service_image); ?>"/>
                        <div id="service-image-render">
                            <?php if (!empty($item->service_image)){ ?>
                                <img src="<?php echo esc_attr($item->service_image); ?>" width="140" height="140">
                            <?php } ?> 
                        </div>
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
