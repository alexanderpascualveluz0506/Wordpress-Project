<?php

function woocommerce_wp_multi_checkbox($field)
{
    global $thepostid, $post;

    if (! $thepostid) {
        $thepostid = $post->ID;
    }

    $field['value'] = get_post_meta($thepostid, $field['id'], true);

    $thepostid = empty($thepostid) ? $post->ID : $thepostid;
    $field['class'] = isset($field['class']) ? $field['class'] : 'select short';
    $field['style'] = isset($field['style']) ? $field['style'] : '';
    $field['wrapper_class'] = isset($field['wrapper_class']) ? $field['wrapper_class'] : '';
    $field['value'] = isset($field['value']) ? $field['value'] : [];
    $field['name'] = isset($field['name']) ? $field['name'] : $field['id'];
    $field['desc_tip'] = isset($field['desc_tip']) ? $field['desc_tip'] : false;

    echo '<fieldset class="form-field ' . esc_attr($field['id']) . '_field ' . esc_attr($field['wrapper_class']) . '">
    <legend>' . wp_kses_post($field['label']) . '</legend>';

    if (! empty($field['description']) && false !== $field['desc_tip']) {
        echo wc_help_tip($field['description']);
    }

    echo '<ul class="wc-radios">';

    foreach ($field['options'] as $key => $value) {
        echo '<li><label><input
                name="' . esc_attr($field['name']) . '"
                value="' . esc_attr($key) . '"
                type="checkbox"
                class="' . esc_attr($field['class']) . '"
                style="' . esc_attr($field['style']) . '"
                ' . (is_array($field['value']) && in_array($key, $field['value']) ? 'checked="checked"' : '') . ' /> ' . esc_html($value) . '</label>
        </li>';
    }
    echo '</ul>';

    if (! empty($field['description']) && false === $field['desc_tip']) {
        echo '<span class="description">' . wp_kses_post($field['description']) . '</span>';
    }

    echo '</fieldset>';
}

add_action('plugins_loaded', 'wcpt_register_availiability_type');
function wcpt_register_availiability_type()
{
    // declare the product class
    class WC_Product_Avaliability extends WC_Product
    {
        public function __construct($product)
        {
            $this->product_type = 'availiability';
            parent::__construct($product);
            // add additional functions here
        }
    }
}

add_action('woocommerce_process_product_meta', 'save_product_options_custom_fields', 30, 1);
function save_product_options_custom_fields($post_id)
{
    if (isset($_POST['_avaliability_days'])) {
        $post_data = $_POST['_avaliability_days'];
        $opening_time = $_POST['opening_time'];
        $closing_time = $_POST['closing_time'];
        $cancellation = isset($_POST['cancellation']) ? 'yes' : 'no';
        $confirmation = isset($_POST['confirmation']) ? 'yes' : 'no';
        $booking_unit = $_POST['booking_unit'];
        $min_booking_duration = $_POST['min_booking_duration'];
        $max_booking_duration = $_POST['max_booking_duration'];


        // Data sanitization
        $sanitize_data = [];
        if (is_array($post_data) && sizeof($post_data) > 0) {
            foreach ($post_data as $value) {
                $sanitize_data[] = esc_attr($value);
            }
        }
        update_post_meta($post_id, '_avaliability_days', $sanitize_data);
        update_post_meta($post_id, 'opening_time', $opening_time);
        update_post_meta($post_id, 'closing_time', $closing_time);
        update_post_meta($post_id, 'booking_unit', $booking_unit);
        update_post_meta($post_id, 'min_booking_duration', $min_booking_duration);
        update_post_meta($post_id, 'max_booking_duration', $max_booking_duration);
        update_post_meta($post_id, 'cancellation', $cancellation);
        update_post_meta($post_id, 'confirmation', $confirmation);
    }
}


/**
 * Process the checkout
 */
add_action('woocommerce_checkout_process', 'custom_checkout_fields_process');
function custom_checkout_fields_process()
{
    // Check if set, if its not set add an error.
    if (! $_POST['schedule_date']) {
        wc_add_notice(__('Please enter something into this new shiny field.'), 'error');
    }
    // Check if set, if its not set add an error.
    if (! $_POST['schedule_time']) {
        wc_add_notice(__('Please enter something into this new shiny field.'), 'error');
    }
}

add_action('woocommerce_before_add_to_cart_button', 'addFieldsBeforeAddToCart');
function addFieldsBeforeAddToCart()
{
    echo '<div id="custom-single-product-fields-1">';

    woocommerce_form_field('booking_dateTime', [
        'type' => 'datetime-local',
        'class' => ['my-field-class form-row-wide'],
        'label' => 'Date and Time',
        'placeholder' => __('Enter the Booking Date and Time'),
        'required' => true,
        ], );

    echo '</div>';

    echo '<div id="custom-single-product-fields-1">';

    woocommerce_form_field('doctor', [
        'type' => 'number',
        'class' => ['my-field-class form-row-wide'],
        'label' => 'Doctor',
        'placeholder' => __('eq. Tooth cleaning'),
        'required' => true,
        ], );

    echo '</div>';
}

add_filter('woocommerce_product_single_add_to_cart_text', 'woo_custom_cart_button_text');
function woo_custom_cart_button_text()
{
    return __('Book Now', 'woofu-kerberos');
}

//save the booking time and date
function add_custom_field_to_cart_item($cart_item_data, $product_id, $variation_id)
{
    $bookingSched = filter_input(INPUT_POST, 'booking_dateTime');
    $date = new DateTime($bookingSched);
    $guest = filter_input(INPUT_POST, 'guest');
    if (empty($bookingSched)) {
        return $cart_item_data;
    }
    if (empty($guest)) {
        return $cart_item_data;
    }

    $cart_item_data['booking_dateTime'] = $date->format('d-m-Y g:i A');
    $cart_item_data['guest'] = $guest;

    return $cart_item_data;
}
add_filter('woocommerce_add_cart_item_data', 'add_custom_field_to_cart_item', 10, 3);

//show the date and time booking save in cart page
function display_custom_field_value_cart($item_data, $cart_item)
{
    if (empty($cart_item['booking_dateTime'])) {
        return $item_data;
    }

    $item_data[] = [
        'key' => __('Time and Date', 'woofu-kerberos'),
        'value' => wc_clean($cart_item['booking_dateTime']),
    ];
    $item_data[] = [
        'key' => __('Guest', 'woofu-kerberos'),
        'value' => wc_clean($cart_item['guest']),
    ];

    return $item_data;
}
add_filter('woocommerce_get_item_data', 'display_custom_field_value_cart', 10, 2);

function custom_fields_to_order_items($item, $cart_item_key, $values, $order)
{
    if (empty($values['booking_dateTime'])) {
        return;
    }
    if (empty($values['guest'])) {
        return;
    }

    $item->add_meta_data(__('booking_dateTime', 'woofu-kerberos'), $values['booking_dateTime']);
    $item->add_meta_data(__('guest', 'woofu-kerberos'), $values['guest']);
}
add_action('woocommerce_checkout_create_order_line_item', 'custom_fields_to_order_items', 10, 4);


// -------------------
// 1. Split product quantities into multiple cart items
// Note: this is not retroactive - empty cart before testing

function tal_split_product_individual_cart_items($cart_item_data, $product_id)
{
    $unique_cart_item_key = uniqid();
    $cart_item_data['unique_key'] = $unique_cart_item_key;

    return $cart_item_data;
}

  add_filter('woocommerce_add_cart_item_data', 'tal_split_product_individual_cart_items', 10, 2);

  // -------------------
  // 2. Force add to cart quantity to 1 and disable +- quantity input
  // Note: product can still be added multiple times to cart

  add_filter('woocommerce_is_sold_individually', '__return_true');
