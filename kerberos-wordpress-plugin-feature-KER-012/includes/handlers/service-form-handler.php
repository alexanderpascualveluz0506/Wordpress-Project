<?php

/**
 * Handle the form submissions
 *
 * @package Package
 * @subpackage Sub Package
 */
class ServiceFormHandler
{
    /**
     * Hook 'em all
     */
    public function __construct()
    {
        add_action('admin_init', [ $this, 'handle' ]);
    }

    /**
     * Handle the service new and edit form
     *
     * @return void
     */
    public function handle()
    {
        if (! isset($_POST['submit_service'])) {
            return;
        }

        if (! wp_verify_nonce($_POST['_wpnonce'], '')) {
            die(__('Are you cheating?', 'kerberos'));
        }

        if (! current_user_can('read')) {
            wp_die(__('Permission Denied!', 'kerberos'));
        }

        $errors = [];
        $page_url = admin_url('admin.php?page=services');
        $field_id = isset($_POST['field_id']) ? intval($_POST['field_id']) : 0;

        $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $description = isset($_POST['description']) ? sanitize_text_field($_POST['description']) : '';
        $image = isset($_POST['file_image']) ? sanitize_text_field($_POST['file_image']) : '';

        // some basic validation
        if (! $name) {
            $errors[] = __('Error: Name is required', 'kerberos');
        }

        if (! $description) {
            $errors[] = __('Error: Description is required', 'kerberos');
        }

        if (! $image) {
            $errors[] = __('Error: Image is reqasASasAuired', 'kerberos');
        }

        // bail out if error found
        if ($errors) {
            $first_error = reset($errors);
            $redirect_to = add_query_arg([ 'error' => $first_error ], $page_url);
            wp_safe_redirect($redirect_to);
            exit;
        }

        
        $fields = [
            'service_name' => $name,
            'service_description' => $description,
            'service_image' => $image,
        ];

        // New or edit?
        if (! $field_id) {
            $insert_id = ker_insert_service($fields);
        } else {
            $fields['id'] = $field_id;

            $insert_id = ker_insert_service($fields);
        }

        if (is_wp_error($insert_id)) {
            $redirect_to = add_query_arg([ 'message' => 'error' ], $page_url);
        } else {
            $redirect_to = add_query_arg([ 'message' => 'success' ], $page_url);
        }

        wp_safe_redirect($redirect_to);
        exit;
    }
}

new ServiceFormHandler();
