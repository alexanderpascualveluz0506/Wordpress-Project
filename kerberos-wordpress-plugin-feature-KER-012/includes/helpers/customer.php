<?php

/**
 * Get all customers
 *
 * @param $args array
 *
 * @return array
 */
function ker_get_all_customers($args = [])
{
    global $wpdb;

    $defaults = [
        'number' => 10,
        'offset' => 0,
        'orderby' => 'id',
        'order' => 'ASC',
    ];

    $args = wp_parse_args($args, $defaults);
    $cache_key = 'customer-all';
    $items = wp_cache_get($cache_key, 'kerberos');

    if (false === $items) {
        $items = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'users ORDER BY ' . $args['orderby'] .' ' . $args['order'] .' LIMIT ' . $args['offset'] . ', ' . $args['number']);

        wp_cache_set($cache_key, $items, 'kerberos');
    }

    return $items;
}

/**
 * Fetch all customers from database
 *
 * @return array
 */
function ker_get_customers_count()
{
    global $wpdb;

    return (int) $wpdb->get_var('SELECT COUNT(*) FROM ' . $wpdb->prefix . 'users');
}

/**
 * Fetch a single customer from database
 *
 * @param int   $id
 *
 * @return array
 */
function ker_get_customer($id = 0)
{
    global $wpdb;

    return $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'users WHERE id = %d', $id));
}


/**
 * Insert a new service
 *
 * @param array $args
 */
//function ker_insert_customer($args = [])
//{
//    global $wpdb;

//    $defaults = [
//        'id' => null,
//        'service_name' => '',
//        'service_description' => '',
//        'service_image' => '',        
//    ];

//    $args = wp_parse_args($args, $defaults);
//    $table_name = $wpdb->prefix . 'services';

    // some basic validation
//    if (empty($args['service_name'])) {
//        return new WP_Error('no-service_name', __('No Name provided.', 'kerberos'));
//    }
//    if (empty($args['service_description'])) {
//        return new WP_Error('no-service_description', __('No Description provided.', 'kerberos'));
//    }
//    if (empty($args['service_image'])) {
//        return new WP_Error('no-service_image', __('No Image provided.', 'kerberos'));
//    }
    
    // remove row id to determine if new or update
    //$row_id = (int) $args['id'];
    //unset($args['id']);

    //if (! $row_id) {
    //    $args['created_at'] = current_time('mysql');
    //    $args['updated_at'] = current_time('mysql');

        // insert a new
    //    if ($wpdb->insert($table_name, $args)) {
    //        return $wpdb->insert_id;
    //    }
    //} else {

        // do update method here
    //    if ($wpdb->update($table_name, $args, [ 'id' => $row_id ])) {
    //        return $row_id;
    //    }
    //}

    //return false;
//}
