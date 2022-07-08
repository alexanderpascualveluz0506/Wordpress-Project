<?php

defined("KER_EXEC") or die("Silent is golden");


class KerRoles{
    public function __construct(){
        // Register our de-activation hook
        register_deactivation_hook(__FILE__, 'deactivate_provider_role');
        // Add the add_provide_role.
        add_action('init', [$this, 'add_provider_role']);
       // Register our activation hook
        register_activation_hook(__FILE__, 'activate_provider_role');

        // Register our de-activation hook
        register_deactivation_hook(__FILE__, 'deactivate_cashier_role');
        // Add the add_cashier_role.
        add_action('init', [$this, 'add_cashier_role']);
       // Register our activation hook
        register_activation_hook(__FILE__, 'activate_cashier_role');
    }

    //Add Provider Role
    public function add_provider_role()
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

    public function activate_provider_role()
    {
        $role = get_role('provider');
        $role->add_cap('manage_options'); // capability
    }
    

    public function deactivate_provider_role()
    {
        $role = get_role('provider');
        $role->remove_cap('manage_options'); // capability
    }

    //Add Cashier Role
    public function add_cashier_role()
    {
        add_role(
            'cashier',
            'Cashier',
            [
                'read' => true,
                'edit_posts' => true,
                'upload_files' => true,
            ],
        );
    }

    public function activate_cashier_role()
    {
        $role = get_role('cashier');
        $role->add_cap('manage_options'); // capability
    }
    

    public function deactivate_cashier_role()
    {
        $role = get_role('cashier');
        $role->remove_cap('manage_options'); // capability
    }
    
}
