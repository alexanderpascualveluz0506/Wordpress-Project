<?php

defined("KER_EXEC") or die("Silent is golden");


class KerRegistration
{
    public function __construct()
    {
        add_action('init', [$this, 'update_anyone_can_register']);
        add_filter('pre_option_default_role', [$this, 'setCashierRole']);
    }

    public function update_anyone_can_register()
    {
        update_option('users_can_register', true);
    }

    public function setCashierRole()
    {
        return 'cashier';
    }
}
