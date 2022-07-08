<?php

if(defined('ABSPATH') && defined('WPINC')) {
    add_action("wp_loaded",array("Ker_Settings_Tab","init"));
}

class Ker_Settings_Tab {

    const VERSION = '1.5.1';

    public static function init() {
        // add tab on woocomerce>settings
        add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
        // add contents on the page
        add_action( 'woocommerce_settings_tabs_settings_tab_demo', __CLASS__ . '::showOptionsPage' );

        add_action('wp_ajax_getImageData', array('Ker_Settings_Tab', 'getImageData'));
        add_action('wp_ajax_displayPreviewImg', array('Ker_Settings_Tab', 'displayPreviewImg'));
        add_action('login_head', array('Ker_Settings_Tab', 'replaceLoginLogo'));
        add_filter('login_headerurl', array('Ker_Settings_Tab', 'replaceLoginUrl'));
        add_filter("login_headertitle", array('Ker_Settings_Tab', 'replaceLoginTitle'));
        add_action('admin_enqueue_scripts', array('Ker_Settings_Tab', 'myAdminScriptsAndStyles'));
       
    }

    /**
     * Load scripts and styles for plugin admin page
     */
    public static function myAdminScriptsAndStyles()
    {
        wp_register_script('custom-login-logo', self::getPluginDir() . '/js/custom-login-logo-min.js', array('jquery','media-upload','thickbox','underscore'), self::VERSION);
        wp_enqueue_media();
        wp_enqueue_script('custom-login-logo');
    }
    
    

    public static function add_settings_tab( $settings_tabs ) {
        $settings_tabs['settings_tab_demo'] = __( 'Company Name', 'woocommerce-settings-tab-demo' );
        return $settings_tabs;
    }


    public static function showOptionsPage()
    {
        ?>

        <div class="wrap custom-login-logo">

            <?php screen_icon('edit-pages'); ?>
            <h2>Upload Login Logo</h2>

            <!-- <div class="updated fade update-status">
                <p><strong><?php //_e('Settings Saved', 'custom-login-logo'); ?></strong></p>
            </div> -->

            <form class="inputfields">
                <input id="upload-input" type="text" size="36" name="upload image" class="upload-image" value="" />
                <input id="upload-button" type="button" value="<?php _e('Upload Image', 'custom-login-logo'); ?>" class="upload-image" />
                <?php wp_nonce_field('custom_login_logo_action','custom_login_logo_nonce'); ?>
            </form>
            <div class="img-holder">
                <p><?php _e('Actual Preview of the image', 'custom-login-logo'); ?></p>
                <div class="img-preview"></div>
            </div>
        </div>

        <?php
    }

    /**
     * Retrieve the img data via AJAX and save as wordpress option
     */
    public static function getImageData()
    {
        if (!empty($_POST) && check_admin_referer('custom_login_logo_action','custom_login_logo_nonce')) {
            if (current_user_can('manage_options')) {
                // sanitize inputs
                $img_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
                $img_size = filter_input(INPUT_POST, 'size', FILTER_SANITIZE_STRING);

                // get the img at the correct size
                $img = wp_get_attachment_image_src($img_id, $img_size);

                // save src + attribs in the DB
                $img_data['id'] = $img_id;
                $img_data['src'] = $img[0];
                $img_data['width'] = $img[1];
                $img_data['height'] = $img[2];

                update_option('custom_login_logo', $img_data);

                $returnval = json_encode(array('src' => $img_data['src'], 'id' => $img_data['id']));
                wp_die($returnval);
            }
        }
    }

    /**
     * Display the currently set login logo img
     */
    public static function displayPreviewImg()
    {
        if (!empty($_POST) && check_admin_referer('custom_login_logo_action','custom_login_logo_nonce')) {
            if (current_user_can('manage_options')) {
                $img_data = get_option('custom_login_logo');
                if ($img_data) {
                    $returnval = json_encode(array('src' => $img_data['src'], 'id' => $img_data['id']));
                }
                else {
                    $returnval = false;
                }
                wp_die($returnval);
            }
        }
    }

    public static function replaceLoginLogo()
    {
        $img_data = get_option('custom_login_logo');

        // use https for background-image if on ssl
        if (is_ssl()) {
            $img_data['src'] = preg_replace( "/^http:/i", "https:", $img_data['src'] );
        }

        if ($img_data) {
            $style = '<style type="text/css">';
            $style .= sprintf('.login h1 a { background: transparent url("%s") no-repeat center top; background-size:%spx %spx; height: %spx; width:auto; }', $img_data['src'], $img_data['width'], $img_data['height'], $img_data['height']);
			$style .= '</style>';
            echo $style;
        }
    }

    /**
     * Retrieve the Home URL
     *
     * @return string Home URL
     */
    public static function replaceLoginUrl()
    {
        return home_url();
    }

    public static function replaceLoginTitle()
    {
        return get_bloginfo('description');
    }

    public static function getBaseName()
    {
        return plugin_basename(__FILE__);
    }

    /**
     * Retrieve the URL to the plugin basename
     *
     * @return string Plugin basename URL
     */
    public static function getPluginDir()
    {
        return WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__));
    }



}

// Ker_Settings_Tab::init();