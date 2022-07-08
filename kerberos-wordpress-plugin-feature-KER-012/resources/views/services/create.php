<div class="wrap">
    <h1><?php _e('Add New Service', 'kerberos'); ?>
    </h1>

    <form action="" method="post" enctype="multipart/form-data">

        <table class="form-table">
            <tbody>
                <tr class="row-name">
                    <th scope="row">
                        <label for="name"><?php _e('Name', 'kerberos'); ?></label>
                    </th>
                    <td>
                        <input type="text" name="name" id="date" class="regular-text" placeholder="" value=""
                            required="required" />
                    </td>
                </tr>
                <tr class="row-description">
                    <th scope="row">
                        <label for="time"><?php _e('Description', 'kerberos'); ?></label>
                    </th>
                    <td>
                        <textarea type="text" name="description" id="description" class="regular-text"
                            placeholder=""value="" required="required" />
                        </textarea>
                    </td>
                </tr>
                <tr class="row-image">
                    <th scope="row">
                        <label for="image"><?php _e('Image', 'kerberos'); ?></label>
                    </th>
                    <td>
                        <button class="upload_service_image">Upload Logo</button>
                        <input type="hidden" name="file_image" class="regular-text" id="service-image-url"/>
                        <div id="service-image-render">
                        </div>
                    </td>

                </tr>
                
            </tbody>
        </table>
       
        <input type="hidden" name="field_id" value="0">

        <?php wp_nonce_field(''); ?>
        <?php submit_button(__('Add New Service', 'kerberos'), 'primary', 'submit_service'); ?>

    </form>
</div>
