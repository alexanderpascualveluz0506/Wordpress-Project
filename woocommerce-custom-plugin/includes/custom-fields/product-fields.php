<?php

defined("KER_EXEC") or die("Silent is golden");


class KerCustomFields
{
    public function __construct()
    {
        add_action('woocommerce_before_add_to_cart_button', [$this, "addFieldsBeforeAddToCart"]);
        add_filter('woocommerce_product_single_add_to_cart_text', [$this, "customButtonAddToCart"]);
        add_filter('woocommerce_add_cart_item_data', [$this, "addCustomFieldToCartItem"], 10, 3);
        add_filter('woocommerce_get_item_data', [$this, "displayCustomerFieldValueToCart"], 10, 2);

        //add_action('woocommerce_checkout_create_order_line_item', [$this, "customerFieldToOrderItems"], 10, 4);
        add_filter('woocommerce_add_cart_item_data', [$this, "splitCartToIndividualItems"], 10, 2);
        add_filter('woocommerce_is_sold_individually', '__return_true');
       // add_action( 'woocommerce_add_order_item_meta', array( $this,'add_order_item_meta'), 10, 2 );
    
        // add_filter( 'woocommerce_add_to_cart_validation',[$this,"filter_add_to_cart_validation"], 20, 3 );
    }

    public function addFieldsBeforeAddToCart()
    {
        $date = new DateTime(); // Date object using current date and time
        $dt = $date->format('Y-m-d\TH:s');

        echo '<div id="custom-single-product-fields-1">';

        woocommerce_form_field('started_at', [
        'type' => 'datetime-local',
        'class' => ['my-field-class form-row-wide'],
        'label' => 'Start',
        'required' => true,
        ], );

        echo '</div>';

        echo '<div id="custom-single-product-fields-1">';

        woocommerce_form_field('ended_at', [
        'type' => 'datetime-local',
        'class' => ['my-field-class form-row-wide'],
        'label' => 'End',
        'required' => true,
        ], );

        global $wpdb;
        $results = $wpdb->get_results("SELECT display_name, ID FROM {$wpdb->prefix}users s INNER JOIN {$wpdb->prefix}usermeta m ON s.ID=m.user_id WHERE m.meta_key = 'wp_capabilities'
        AND m.meta_value LIKE '%provider%' ");
        $options[''] = __('Select Provider', ' woofu-kerberos');

        foreach ($results as $result) {
            $options[$result->ID] = $result->display_name;
        }

        woocommerce_form_field('provider_id', [
            'type' => 'select',
            'class' => ['my-field-class form-row-wide'],
            'label' => 'Doctor',
            'options' => $options,
            'required' => true,
        ], );

        echo '</div>';
    }
    // Field validation (Checking)

    public function filter_add_to_cart_validation($passed, $product_id, $quantity)
    {

    // Get the custom field values to check
        $started_at = (array) get_post_meta($product_id, 'started_at', true);
        $ended_at = (array) get_post_meta($product_id, 'ended_at', true);

        $date = new DateTime(); // Date object using current date and time
        $dt = $date->format('Y-m-d\TH:s');

        // Check that the value if not empty
        if (in_array($_POST['started_at'], $started_at)) {
            $passed = false ; // Set as false when the value exist

            // Displaying a custom error message
            $message = sprintf(__('Please select a start date and time', 'woocommerce'), filter_input(INPUT_POST, 'started_at'));
            wc_add_notice($message, 'error');
        }

        if (in_array($_POST['ended_at'], $ended_at)) {
            $passed = false ; // Set as false when the value exist

            $message = sprintf(__('Please select an end date and time', 'woocommerce'), filter_input(INPUT_POST, 'ended_at'));
            wc_add_notice($message, 'error');
        }


        if (filter_input(INPUT_POST, 'started_at') <= $dt) {
            $passed = false ; // Set as false when the value exist

            $message = sprintf(__('Please input a valid start time', 'woocommerce'));
            wc_add_notice($message, 'error');
        }

        if (filter_input(INPUT_POST, 'ended_at') <= filter_input(INPUT_POST, 'started_at')) {
            $passed = false ; // Set as false when the value exist

            $message = sprintf(__('Please input a valid end booking date and time', 'woocommerce'));
            wc_add_notice($message, 'error');
        }


        return $passed;
    }

    public function customButtonAddToCart()
    {
        return __('Book Now', 'woofu-kerberos');
    }

    public function addCustomFieldToCartItem($cartItemData, $productId, $variationId)
    {
        $started_at = filter_input(INPUT_POST, 'started_at');
        $started_at = new DateTime($started_at);
        $ended_at = filter_input(INPUT_POST, 'ended_at');
        $ended_at = new DateTime($ended_at);
        if (empty($started_at)) {
            return $cartItemData;
        }

        if (empty($ended_at)) {
            return $cartItemData;
        }

        $cartItemData['started_at'] = $started_at->format('d-m-Y g:i A');
        $cartItemData['ended_at'] = $ended_at->format('d-m-Y g:i A');

        return $cartItemData;
    }

    public function displayCustomerFieldValueToCart($item_data, $cart_item)
    {
        if (empty($cart_item['started_at'])) {
            return $item_data;
        }

        $item_data[] = [
            'key' => __('started_at', 'woofu-kerberos'),
            'value' => wc_clean($cart_item['started_at']),
        ];

        $item_data[] = [
            'key' => __('ended_at', 'woofu-kerberos'),
            'value' => wc_clean($cart_item['ended_at']),
        ];

        return $item_data;
    }

    public function customerFieldToOrderItems($item, $cart_item_key, $values, $order)
    {
        if (empty($values['started_at'])) {
            return;
        }
        if (empty($values['ended_at'])) {
            return;
        }

        $item->add_meta_data(__('started_at', 'woofu-kerberos'), $values['started_at']);
        $item->add_meta_data(__('ended_at', 'woofu-kerberos'), $values['ended_at']);
    }

    public function splitCartToIndividualItems($cartItemData, $productId)
    {
        $unique_cart_item_key = uniqid();
        $cartItemData['unique_key'] = $unique_cart_item_key;

        return $cartItemData;
    }
    public function add_order_item_meta($item_id, $values) {
       
      
        $value = 'AAA'; // Get your value here
     
        wc_update_order_item_meta($item_id, 'started_at', $value);
        wc_update_order_item_meta($item_id, 'ended_at', $value);
    }
}




add_action('plugins_loaded', 'wcpt_register_bookable_type');
add_filter('product_type_selector', 'wcpt_add_bookable_type');
add_filter('woocommerce_product_data_tabs', 'bookable_tab');
add_action('woocommerce_product_data_panels', 'wcpt_bookable_options_product_tab_content');
add_action('woocommerce_process_product_meta', 'save_bookable_options_field');
add_filter('woocommerce_product_data_tabs', 'booking_availability_tabs');
add_action('woocommerce_product_data_panels', 'wcpt_availability_options_tab_content');
add_action('woocommerce_process_product_meta', 'save_product_options_custom_fields', 30, 1);


/**
 * Booking Cost
 */

function wcpt_register_bookable_type()
{
    // declare the product class
    class WC_Product_Bookable_Card extends WC_Product
    {
        public function __construct($product)
        {
            $this->product_type = 'bookable';
            $this->product_type = 'availiability';
            parent::__construct($product);
            // add additional functions here
        }
    }
}

function wcpt_add_bookable_type($type)
{
    // Key should be exactly the same as in the class product_type
    $type[ 'bookable' ] = __('Bookable Product');

    return $type;
}

function bookable_tab($tabs)
{
    $tabs['bookable'] = [
        'label' => __('Booking Cost', 'wcpt'),
        'target' => 'bookable_options',
        'class' => ('show_if_bookable'),
        'priority' => 1,
    ];

    return $tabs;
}

function wcpt_bookable_options_product_tab_content()
{

    // Dont forget to change the id in the div with your target of your product tab?>
<div id='bookable_options' class='panel woocommerce_options_panel'>
	<b> &nbsp;Pricing</b>
	<div class='options_group'>

		<?php

            woocommerce_wp_text_input([
                'id' => '_bed_count',
                'label' => __('Number of Beds', 'wcpt'),
                   'placeholder' => '',
                'type' => 'number',
                   'desc_tip' => 'true',
                   'description' => __('Enter Number of Beds', 'wcpt'),
            ]);

    woocommerce_wp_text_input([
                'id' => '_price_per_hour',
                'label' => __('Price per Hour', 'wcpt'),
                   'placeholder' => '',
                'type' => 'number',
                'desc_tip' => 'true',
                   'description' => __('Enter Price per Hour', 'wcpt'),
            ]); ?>

	</div>
	<!-- end of standard prices -->
</div>
<?php
}


function save_bookable_options_field($post_id)
{
    if (isset($_POST['_bed_count'])) :
        update_post_meta($post_id, '_bed_count', sanitize_text_field($_POST['_bed_count']));
    endif;

    if (isset($_POST['_price_per_hour'])) :
        update_post_meta($post_id, '_price_per_hour', sanitize_text_field($_POST['_price_per_hour']));
    endif;
}




/**
 * Booking Availability
 */

function booking_availability_tabs($tabs)
{

    //unset( $tabs['inventory'] );
    $tabs['availability'] = [
        'label' => 'Booking Availability',
        'target' => 'availability_options',
        'class' => ('show_if_bookable'),
        'priority' => 21,
    ];

    return $tabs;
}

// New Multi Checkbox field for woocommerce backend
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

function wcpt_availability_options_tab_content()
{

    // Dont forget to change the id in the div with your target of your product tab?>
<div id='availability_options' class='panel woocommerce_options_panel'>

	<h4>Availability Days</h4>
	<?php
        woocommerce_wp_multi_checkbox([
            'id' => '_availability_days',
            'name' => '_availability_days[]',
            'options' => [
                'Sunday' => 'Sunday',
                'Monday' => 'Monday',
                'Tuesday' => 'Tuesday',
                'Wednesday' => 'Wednesday',
                'Thursday' => 'Thursday',
                'Friday' => 'Friday',
                'Saturday' => 'Saturday',
            ],
        ]);

    woocommerce_wp_text_input(
        [
                'id' => 'opening_time',
                'label' => 'Opening Time',
                'type' => 'time',
            ]
    );

    woocommerce_wp_text_input(
        [
                'id' => 'closing_time',
                'label' => 'Closing Time',
                'type' => 'time',
            ]
    ); ?>
	<h4>Booking Terms</h4><?php

        woocommerce_wp_text_input(
            [
                'id' => 'booking_unit',
                'label' => 'Max Booking Per Unit',
                'type' => 'number',
            ]
        );

    woocommerce_wp_text_input(
        [
                'id' => 'min_booking_duration',
                'label' => 'Minimum Booking Duration (days)',
                'type' => 'number',
            ]
    );

    woocommerce_wp_text_input(
        [
                'id' => 'max_booking_duration',
                'label' => 'Maximum Booking Duration (days)',
                'type' => 'number',
            ]
    );
    woocommerce_wp_checkbox(
        [
                'id' => 'confirmation',
                'label' => 'Require Confirmation',

            ]
    );
    woocommerce_wp_checkbox(
        [
                'id' => 'cancellation',
                'label' => 'Allow Cancellation',
            ]
    );
}


function save_product_options_custom_fields($post_id)
{
    if (isset($_POST['_availability_days'])) {
        $post_data = $_POST['_availability_days'];
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
        update_post_meta($post_id, '_availability_days', $sanitize_data);
        update_post_meta($post_id, 'opening_time', $opening_time);
        update_post_meta($post_id, 'closing_time', $closing_time);
        update_post_meta($post_id, 'booking_unit', $booking_unit);
        update_post_meta($post_id, 'min_booking_duration', $min_booking_duration);
        update_post_meta($post_id, 'max_booking_duration', $max_booking_duration);
        update_post_meta($post_id, 'cancellation', $cancellation);
        update_post_meta($post_id, 'confirmation', $confirmation);
    }


 


}

