<?php
/**
 * Plugin Name: 	Kerberos
 * Plugin URI:      https://www.github.com/nerdmonkey
 * Description:     This is a booking or appointment plugin
 * Author:          	Sydel Palinlin
 * Author URI:      https://www.github.com/nerdmonkey
 * Text Domain:     kerberos
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Kerberos
 */

add_action('init', function () {
    include_once plugin_dir_path(__FILE__).'includes/class-kerberos-menu.php';
    include_once plugin_dir_path(__FILE__).'includes/datatables/booking-datatable.php';
    include_once plugin_dir_path(__FILE__).'includes/handlers/booking-form-handler.php';
    include_once plugin_dir_path(__FILE__).'includes/helpers/booking.php';

    //Service DataTable
    include_once plugin_dir_path(__FILE__).'includes/datatables/services-datatable.php';
    include_once plugin_dir_path(__FILE__).'includes/handlers/service-form-handler.php';
    include_once plugin_dir_path(__FILE__).'includes/helpers/service.php';

    //Customers DataTable
    include_once plugin_dir_path(__FILE__).'includes/datatables/customers-datatable.php';
    include_once plugin_dir_path(__FILE__).'includes/helpers/customer.php';

    new Kerberos();
});

function include_style_admin()
{
    if (! did_action('wp_enqueue_media')) {
        wp_enqueue_media();
    }
    wp_enqueue_script('script-login-logo', plugin_dir_url(__FILE__). '/resources/assets/js/image-upload.js', ['jquery'], null, false);
	wp_enqueue_script('script-services-image', plugin_dir_url(__FILE__). '/resources/assets/js/image-services-upload.js', ['jquery'], null, false);
    wp_enqueue_style('style', plugin_dir_url(__FILE__). '/resources/assets/css/style.css');
    wp_enqueue_script('script', plugin_dir_url(__FILE__). '/resources/assets/js/script.js', ['jquery'], null, false);
}
add_action('admin_enqueue_scripts', 'include_style_admin');


function utm_user_scripts()
{
    $plugin_url = plugin_dir_url(__FILE__);

    wp_enqueue_style('style',  $plugin_url . "/resources/assets/css/style.css");
    wp_enqueue_script('script',  $plugin_url . "/resources/assets/js/script.js", true);
    wp_enqueue_style('fc-style', 'https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.css');
    wp_enqueue_script('fc-script',  'https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.js', true);
    wp_enqueue_script('moment-script',  'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js', true);
    wp_enqueue_style('bs-style', 'https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css');
    wp_enqueue_script('bs-script',  'https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js', true);
}

add_action('admin_print_styles', 'utm_user_scripts');



function reg_front()
{
    $plugin_url = plugin_dir_url(__FILE__);
    wp_enqueue_script('jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js', true);
    wp_enqueue_style('stylebn',  $plugin_url . "/try.css");
    wp_enqueue_script('scruptj',  $plugin_url . "/try.js", true);
    //wp_enqueue_script( 'script',  $plugin_url . "/resources/assets/js/script.js", true);
    wp_enqueue_script('script', plugin_dir_url(__FILE__). '/resources/assets/js/calendar.js', ['jquery'], null, false);
    wp_enqueue_style('fc-style', 'https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.css');
    wp_enqueue_script('fc-script',  'https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.js', true);
    wp_enqueue_script('moment-script',  'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js', true);
}

add_action('wp_enqueue_scripts', 'reg_front');



add_shortcode('shortcode_name', 'function_name');
function function_name()
{
    include_once plugin_dir_path(__FILE__).'resources/views/frontend/calendar.php';
}


add_filter('page_template', 'booking_page_template');
function booking_page_template($page_template)
{
    if (is_page('booking')) {
        $page_template = dirname(__FILE__) . '/resources/views/frontend/bookingForm.php';
    }

    return $page_template;
}


//Adding Provider Role
function add_provider_role()
{
    add_role(
        'provider',
        'Provider',
        [
            'read' => true,
            'edit_posts' => true,
            'upload_files' => true,
        ],
    );
}

// Add the add_provide_role.
add_action('init', 'add_provider_role');

function activate_plugin_name()
{
    $role = get_role('provider');
    $role->add_cap('manage_options'); // capability
}
// Register our activation hook
register_activation_hook(__FILE__, 'activate_plugin_name');

function deactivate_plugin_name()
{
    $role = get_role('provider');
    $role->remove_cap('manage_options'); // capability
}
// Register our de-activation hook
register_deactivation_hook(__FILE__, 'deactivate_plugin_name');


//Add Form
add_shortcode('add_form', 'add_booking_form');
function add_booking_form()
{
    include_once plugin_dir_path(__FILE__).'resources/views/add-booking-form-template.php';
}



//Gender Metabox
add_action('cmb2_admin_init', 'yourprefix_register_user_profile_metabox');
function yourprefix_register_user_profile_metabox()
{
    include_once plugin_dir_path(__FILE__).'resources/views/cmb2-metabox.php';
}

//Custom Login/Register
add_shortcode('login_register_forms', 'login_register');
function login_register()
{
    include_once plugin_dir_path(__FILE__).'resources/views/custom-login-register-template.php';
}

//Display Calendar
add_shortcode('calendar', 'display_calendar');
function display_calendar()
{
    include_once plugin_dir_path(__FILE__).'resources/views/front-end-calendar-template.php';    
}


/**
 * Custom login image URL
 */



function wpb_login_logo()
{
    global $wpdb;
    $select = $wpdb->get_results("SELECT icon from  {$wpdb->prefix}settings");
    foreach ($select as $value) {
        ?>
<style type="text/css">
    #login h1 a,
    .login h1 a {
        background-image: url(<?php echo $value->icon ?>);
        height: 100px;
        width: 300px;
        background-size: 300px 100px;
        background-repeat: no-repeat;
        padding-bottom: 10px;
    }
</style>
<?php
    }
}
add_action('login_enqueue_scripts', 'wpb_login_logo');


//ajax caller dropdate
function dragged_date(){
    global $wpdb;
    $table_name = $wpdb->prefix.'kerberos';
    if(isset($_POST['date']) && isset($_POST['id'])){
        $date = $_POST['date'];
        $id = $_POST['id'];                
        echo 'UPDATE '.$table_name.' SET datetime = "'.$date.'" WHERE id = "'.$id.'"';
        $update = $wpdb->query($wpdb->prepare('UPDATE '.$table_name.' SET datetime = "'.$date.'" WHERE id = "'.$id.'"'));
    }    
    die();
}

add_action( "wp_ajax_drag_date", "dragged_date" );
add_action( "wp_ajax_nopriv_drag_date", "dragged_date" );
