<?php

defined("KER_EXEC") or die("Silent is golden");


class KerCartShortcode
{
    public function __construct()
    {
        add_shortcode('cart_shortcode', [$this, 'miniCart']);
        add_shortcode('pay_function_shortcode', [$this,'payFunction']);
    }

    public function miniCart()
    {
        ?>

<table>
	<thead>
		<th colspan="3">Room</th>
		<th>Price</th>
		<thead>

			<?php
        global $woocommerce;
        foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item) {
            $product = $cart_item['data'];
            $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
            $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key); ?>
			<tr>
				<td class="product-remove">
					<?php
                    echo apply_filters(
                'woocommerce_cart_item_remove_link',
                sprintf(
                    '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
                    esc_url(wc_get_cart_remove_url($cart_item_key)),
                    esc_html__('Remove this item', 'woocommerce'),
                    esc_attr($product_id),
                    esc_attr($_product->get_sku())
                ),
                $cart_item_key
            ); ?>
				</td>
				<td>
					<?php echo $product->get_image(); ?>
				</td>

				<td><?php echo $product->name ?>
					<br>
					<?php $meta = wc_get_formatted_cart_item_data($cart_item);
            echo $meta; ?>
				</td>

				<td>
					<?php echo WC()->cart->get_product_price($product); ?>
				</td>
			</tr>

			<?php
        } ?>
</table>

<?php

        echo 'SUBTOTAL: '.WC()->cart->get_cart_subtotal();
        echo "<br>";
        echo "VAT: ". WC()->cart->get_cart_contents_tax();
        echo "<br>";
        echo "TO PAY: ".WC()->cart->get_total();
    }

    public function wc_order_complete()
    {
        global $woocommerce;
        $order = new WC_Order();
        if ($order->status != 'failed') {
            wp_redirect(home_url());
            exit;
        }
    }

    public function payFunction()
    {
        if (isset($_POST['payBtn'])) {
            $order = wc_create_order();
            global $woocommerce;
            foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item) {
                $product_id = $cart_item['product_id'];
                $quantity = $cart_item['quantity'];
                $meta = wc_get_formatted_cart_item_data($cart_item);


                $order->add_product(get_product($product_id), $quantity);
            }
            $order->calculate_totals();
            $order->save();
        }
        // WC()->cart->empty_cart( true );?>

<div>
	<form
		action="<?php echo $_SERVER['PHP_SELF']; ?>"
		method="POST">
		<button type="submit" name="payBtn" style="background-color:yellow">Pay</button>
	</form>
</div>

<?php
    }
}
