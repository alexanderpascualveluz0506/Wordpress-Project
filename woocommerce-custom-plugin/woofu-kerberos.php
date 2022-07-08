<?php
/**
 * Plugin Name:     Kerberos Woofu
 * Plugin URI:      https://www.github.com/epie
 * Description:     This plugin can enable and disable certain features
 * Author:          Sydel Palinlin
 * Author URI:      https://www.github.com/epie
 * Text Domain:     woofu-kerberos
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Woofu_Kerberos
 */

if (! defined("WPINC")) {
    die;
}

define("KER_EXEC", true);
define("KER_FILE", __FILE__);
define("KER_PATH", dirname(__FILE__));
define("KER_URL", plugins_url("/", __FILE__));

require KER_PATH . "/includes/setup.php";

//require KER_PATH . "/includes/custom-fields/product-fields.php";

require KER_PATH . "/includes/shortcodes/cart.php";
require KER_PATH . "/includes/shortcodes/product.php";

require KER_PATH . "/includes/auth/roles.php";
require KER_PATH . "/includes/auth/registration.php";

require KER_PATH . "/includes/payments/cash.php";

require KER_PATH . "/includes/filters.php";
require KER_PATH . "/includes/custom-fields/settings.php";
require KER_PATH . "/includes/custom-fields/product-bundle.php";

function runWoofuKerberos()
{
    $plugin = new WoofuKerberos();
}



runWoofuKerberos();
wp_enqueue_script( 'bundle', plugin_dir_url( __FILE__ ) . '/includes/js/bundle.js' );
wp_localize_script( 'bundle', 'ajax_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );


