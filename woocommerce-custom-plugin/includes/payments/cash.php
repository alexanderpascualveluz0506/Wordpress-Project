<?php

/**
 * Custom Payment Method
 */

add_filter( 'woocommerce_payment_gateways', 'add_your_gateway_class' );

function add_your_gateway_class( $methods ) {
    $methods[] = 'WC_Custom_PG'; 
    return $methods;
}
 
add_action( 'plugins_loaded', 'init_wc_custom_payment_gateway' );

function init_wc_custom_payment_gateway(){
    class WC_Custom_PG extends WC_Payment_Gateway {
        function __construct(){
            $this->id = 'wc_custom_pg';
            $this->method_title = 'Custom Payment Gateway';
            $this->title = 'Custom Payment Gateway';
            $this->has_fields = true;
            $this->method_description = 'Your description of the payment gateway';

            //load the settings
            $this->init_form_fields();
            $this->init_settings();
            $this->enabled = $this->get_option('enabled');
            $this->title = $this->get_option( 'title' );
            $this->description = $this->get_option('description');

            //process settings with parent method
            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );

        }
        public function init_form_fields(){
            $this->form_fields = array(
                'enabled' => array(
                    'title'         => 'Enable/Disable',
                    'type'          => 'checkbox',
                    'label'         => 'Enable Custom Payment Gateway',
                    'default'       => 'yes'
                ),
                'title' => array(
                    'title'         => 'Method Title',
                    'type'          => 'text',
                    'description'   => 'This controls the payment method title',
                    'default'       => 'Custom Payment Gatway',
                    'desc_tip'      => true,
                ),
                'description' => array(
                    'title'         => 'Customer Message',
                    'type'          => 'textarea',
                    'css'           => 'width:500px;',
                    'default'       => 'Your Payment Gateway Description',
                    'description'   => 'The message which you want it to appear to the customer in the checkout page.',
                )
            );
        }

        function process_payment( $order_id ) {
            global $woocommerce;

            $order = new WC_Order( $order_id );

            /****

                Here is where you need to call your payment gateway API to process the payment
                You can use cURL or wp_remote_get()/wp_remote_post() to send data and receive response from your API.

            ****/
            $order->add_order_note( 'Hey, your order is paid! Thank you!', true );

            //Based on the response from your payment gateway, you can set the the order status to processing or completed if successful:
            $order->update_status('processing','Additional data like transaction id or reference number');

            //once the order is updated clear the cart and reduce the stock
            $woocommerce->cart->empty_cart();
            $order->reduce_order_stock();
            //if the payment processing was successful, return an array with result as success and redirect to the order-received/thank you page.
            return array(
                'result' => 'success',
                'redirect' => $this->get_return_url( $order )
            );
        }

        public function validate_fields(){
            if( empty( $_POST[ 'credit_card' ]) ) {
                wc_add_notice(  'Credit Card is required!', 'error' );
                return false;
            }
            return true;
        }


        //this function lets you add fields that can collect payment information in the checkout page like card details and pass it on to your payment gateway API through the process_payment function defined above.

        public function payment_fields(){
            ?>
            <fieldset>
                <p class="form-row form-row-wide">
                    <?php echo esc_attr($this->description); ?>
                </p>
                <div class="form-row form-row-wide">
                    <h1>Credit Card Number</h1>
                    <input type="text" id="credit_card" name="credit_card" required>
                </div>                                        
                <?php do_action( 'woocommerce_credit_card_form_end', $this->id ); ?>
                <div class="clear"></div>
            </fieldset>
            <?php
        }

    }
}
