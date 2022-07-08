<?php

/**
 * Handle the form submissions
 *
 * @package Package
 * @subpackage Sub Package
 */
class BookingFormHandler
{
    /**
     * Hook 'em all
     */
    public function __construct()
    {
        add_action('admin_init', [ $this, 'handle' ]);
    }

    /**
     * Handle the booking new and edit form
     *
     * @return void
     */
    public function handle()
    {
        if (! isset($_POST['submit_booking'])) {
            return;
        }

        if (! wp_verify_nonce($_POST['_wpnonce'], '')) {
            die(__('Are you cheating?', 'kerberos'));
        }

        if (! current_user_can('read')) {
            wp_die(__('Permission Denied!', 'kerberos'));
        }

        $errors = [];
        $page_url = admin_url('admin.php?page=kerberos');
        $field_id = isset($_POST['field_id']) ? intval($_POST['field_id']) : 0;

        $date = isset($_POST['date']) ? sanitize_text_field($_POST['date']) : '';
        $time = isset($_POST['time']) ? sanitize_text_field($_POST['time']) : '';
        $service_id = isset($_POST['service_id']) ? intval($_POST['service_id']) : 0;
        $provider_id = isset($_POST['provider_id']) ? intval($_POST['provider_id']) : 0;
        $customer_id = isset($_POST['customer_id']) ? intval($_POST['customer_id']) : 0;
        $notes = isset($_POST['notes']) ? sanitize_text_field($_POST['notes']) : '';
        $status = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : '';

        // some basic validation
        if (! $date) {
            $errors[] = __('Error: Date is required', 'kerberos');
        }

        if (! $time) {
            $errors[] = __('Error: Time is required', 'kerberos');
        }

        if (! $service_id) {
            $errors[] = __('Error: Service is required', 'kerberos');
        }

        if (! $provider_id) {
            $errors[] = __('Error: Service is required', 'kerberos');
        }

        if (! $customer_id) {
            $errors[] = __('Error: Service is required', 'kerberos');
        }

        if (! $notes) {
            $errors[] = __('Error: Notes is required', 'kerberos');
        }

        if (! $status) {
            $errors[] = __('Error: Status is required', 'kerberos');
        }

        // bail out if error found
        if ($errors) {
            $first_error = reset($errors);
            $redirect_to = add_query_arg([ 'error' => $first_error ], $page_url);
            wp_safe_redirect($redirect_to);
            exit;
        }

        $datetime = $date . '  ' . $time . ':00';

        $fields = [
            'datetime' => $datetime,
            'service_id' => $service_id,
            'customer_id' => $customer_id,
            'provider_id' => $provider_id,
            'notes' => $notes,
            'status' => $status,
        ];

        // New or edit?
        if (! $field_id) {
            $insert_id = ker_insert_booking($fields);
        } else {
            $fields['id'] = $field_id;

            $insert_id = ker_insert_booking($fields);
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

new BookingFormHandler();
