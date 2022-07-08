<?php

defined("KER_EXEC") or die("Silent is golden");

class KerProductShortcode
{
    public function __construct()
    {
        add_shortcode('ker_products', [$this, "getProducts"]);
    }

    public function getProducts()
    {
        // if (isset($_POST['prod_id'])) {
        //     $product_id = $_POST['prod_id'];
        //     WC()->cart->add_to_cart($product_id);
        // }

        $args = array(
            'taxonomy'   => "product_cat",
            'number'     => $number,
            'orderby'    => $orderby,
            'order'      => $order,
            'hide_empty' => $hide_empty,
            'include'    => $ids
        );
        $product_categories = get_terms($args);
        

        require KER_PATH . "/views/product.php";
    }
}
