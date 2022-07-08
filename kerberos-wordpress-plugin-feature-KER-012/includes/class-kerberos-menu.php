<?php

/**
 * Admin Menu
 */
class Kerberos
{
    /**
     * Kick-in the class
     */
    public function __construct()
    {
        add_action('admin_menu', [ $this, 'admin_menu' ]);
    }

    /**
     * Add menu items
     *
     * @return void
     */
    public function admin_menu()
    {
        /** Top Menu **/
        add_menu_page(__('Kerberos', 'kerberos'), __('Kerberos', 'kerberos'), 'manage_options', 'kerberos', [ $this, 'plugin_page' ], 'dashicons-groups', null);

        /** Sub Menu **/
        add_submenu_page('kerberos',  __('Dashboard', 'kerberos'),  __('Dashboard', 'kerberos'), 'manage_options', 'kerberos');
        add_submenu_page('kerberos', __('Bookings', 'bookings'), __('Bookings', 'bookings'), 'manage_options', 'bookings', [ $this, 'booking_pages' ]);
        add_submenu_page('kerberos', __('Customers', 'customers'), __('Customers', 'kerberos'), 'manage_options', 'customers', [ $this, 'customer_pages' ]);
        add_submenu_page('kerberos', __('Services', 'services'), __('Services', 'services'), 'manage_options', 'services', [ $this, 'service_pages' ]);
        add_submenu_page('kerberos', __('Settings', 'kerberos'), __('Settings', 'kerberos'), 'manage_options', 'settings', [ $this, 'setting_page' ]);
    }

    /**
     * Handles the plugin page
     *
     * @return void
     */
    public function booking_pages()
    {
        $action = isset($_GET['action']) ? $_GET['action'] : 'home';
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        switch ($action) {
            case 'view-booking':

                $template = dirname(__DIR__) . '/resources/views/bookings/show.php';

                break;

            case 'edit-booking':
                $template = dirname(__DIR__) . '/resources/views/bookings/edit.php';

                break;

            case 'new-booking':
                $template = dirname(__DIR__) . '/resources/views/bookings/create.php';

                break;

            default:
                $template = dirname(__DIR__) . '/resources/views/bookings/index.php';

                break;
        }

        if (file_exists($template)) {
            include $template;
        }
    }

    /**
     * Handles the plugin page
     *
     * @return void
     */
    public function service_pages()
    {
        $action = isset($_GET['action']) ? $_GET['action'] : 'home';
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

       

        switch ($action) {
            case 'view-service':

                $template = dirname(__DIR__) . '/resources/views/services/show.php';

                break;

            case 'edit-service':
                $template = dirname(__DIR__) . '/resources/views/services/edit.php';

                break;

            case 'new-service':
                $template = dirname(__DIR__) . '/resources/views/services/create.php';

                break;

            default:
                $template = dirname(__DIR__) . '/resources/views/services/index.php';

                break;
        }

        if (file_exists($template)) {
            include $template;
        }
    }

    /**
     * Handles the plugin page
     *
     * @return void
     */
    public function customer_pages()
    {
        $action = isset($_GET['action']) ? $_GET['action'] : 'home';
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        switch ($action) {
            case 'view-customer':

                $template = dirname(__DIR__) . '/resources/views/customers/show.php';

                break;

            case 'edit-customer':
                $template = dirname(__DIR__) . '/resources/views/customers/edit.php';

                break;

            case 'new-customer':
                $template = dirname(__DIR__) . '/resources/views/customers/create.php';

                break;

            default:
                $template = dirname(__DIR__) . '/resources/views/customers/index.php';

                break;
        }

        if (file_exists($template)) {
            include $template;
        }
    }

    public function setting_page()
    { 
        $template = dirname(__DIR__) . '/resources/views/settings/setting.php';
        if (file_exists($template)) {
            include $template;
        }
    }



    public function plugin_page()
    {
        $action = isset($_GET['action']) ? $_GET['action'] : 'home';
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        switch ($action) {
            case 'view-booking':

                $template = dirname(__DIR__) . '/resources/views/bookings/show.php';

                break;

            case 'edit-booking':
                $template = dirname(__DIR__) . '/resources/views/bookings/edit.php';

                  break;

            case 'new-booking':
                $template = dirname(__DIR__) . '/resources/views/bookings/create.php';

                break;

            case 'list-bookings':
                    $template = dirname(__DIR__) . '/resources/views/bookings/index.php';

                    break;

            default:
                $template = dirname(__DIR__) . '/resources/views/dashboard.php';

                break;
        }

        if (file_exists($template)) {
            include $template;
        }
    }
}
