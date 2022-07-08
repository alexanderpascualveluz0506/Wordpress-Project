<?php

/**
 * Get all book
 *
 * @param $args array
 *
 * @return array
 */
function ker_get_all_book($args = [])
{
    global $wpdb;

    $defaults = [
        'number' => 10,
        'offset' => 0,
        'orderby' => 'id',
        'order' => 'ASC',
    ];

    $tables = [
        "kerberos" => $wpdb->prefix . 'kerberos',
        "services" => $wpdb->prefix . 'services',
        "users" => $wpdb->prefix . 'users ',
    ];


    $args = wp_parse_args($args, $defaults);
    $cache_key = 'book-all';
    $items = wp_cache_get($cache_key, 'kerberos');

    if (false === $items) {
        $items = $wpdb->get_results("SELECT  ".$tables["kerberos"].".id,  customer.display_name as customer_id, ".$tables["services"].".service_name as service_id , provider.display_name as provider_id, `datetime`, `status`, `notes`
        FROM  ".$tables["kerberos"]." JOIN ".$tables["services"]." ON ".$tables["kerberos"].".service_id = ".$tables["services"].".id JOIN ".$tables["users"]." customer ON ".$tables["kerberos"].".customer_id = customer.ID
        JOIN ".$tables["users"]." provider ON ".$tables["kerberos"].".provider_id = provider.ID
        ORDER BY ".$args['orderby']." ".$args['order']." LIMIT ".$args['offset'].", ".$args["number"]." ");

        wp_cache_set($cache_key, $items, 'kerberos');
    }

    return $items;
}

/**
 * Fetch all book from database
 *
 * @return array
 */
function ker_get_book_count()
{
    global $wpdb;

    return (int) $wpdb->get_var('SELECT COUNT(*) FROM ' . $wpdb->prefix . 'kerberos');
}

function get_all_services()
{
    global $wpdb;
    $results = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'services');

    return $results;
}

function get_all_customers()
{
    global $wpdb;
    $results = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'users');
    foreach ($results as $result) {
        $html .= '<option value='. $result->ID.'  > '.esc_attr($result->display_name).'</option>';
    }
    echo $html;
}
function get_all_providers()
{
    global $wpdb;
    $results = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'users');
    foreach ($results as $result) {
        $html .= '<option value='. $result->ID.' > '.esc_attr($result->display_name).'</option>';
    }
    echo $html;
}
/**
 * Fetch a single book from database
 *
 * @param int   $id
 *
 * @return array
 */
function ker_get_booking($id = 0)
{
    global $wpdb;

    return $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'kerberos WHERE id = %d', $id));
}

/**
 * Insert a new booking
 *
 * @param array $args
 */
function ker_insert_booking($args = [])
{
    global $wpdb;

    $defaults = [
        'id' => null,
        'datetime' => '',
        'service_id' => '',
        'customer_id' => '',
        'provider_id' => '',
        'notes' => '',
        'status' => '',
    ];

    $args = wp_parse_args($args, $defaults);
    $table_name = $wpdb->prefix . 'kerberos';

    // some basic validation
    if (empty($args['datetime'])) {
        return new WP_Error('no-date', __('No Datetime provided.', 'kerberos'));
    }
    if (empty($args['notes'])) {
        return new WP_Error('no-notes', __('No Notes provided.', 'kerberos'));
    }
    if (empty($args['status'])) {
        return new WP_Error('no-status', __('No Status provided.', 'kerberos'));
    }
    if (empty($args['service_id'])) {
        return new WP_Error('no-service_id', __('No Service provided.', 'kerberos'));
    }
    if (empty($args['customer_id'])) {
        return new WP_Error('no-customer_id', __('No Customer provided.', 'kerberos'));
    }
    if (empty($args['provider_id'])) {
        return new WP_Error('no-provider_id', __('No Provider provided.', 'kerberos'));
    }

    // remove row id to determine if new or update
    $row_id = (int) $args['id'];
    unset($args['id']);

    if (! $row_id) {
        $args['created_at'] = current_time('mysql');
        $args['updated_at'] = current_time('mysql');

        // insert a new
        if ($wpdb->insert($table_name, $args)) {
            return $wpdb->insert_id;
        }
    } else {

        // do update method here
        if ($wpdb->update($table_name, $args, [ 'id' => $row_id ])) {
            return $row_id;
        }
    }

    return false;
}
