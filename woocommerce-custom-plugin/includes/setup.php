<?php

defined("KER_EXEC") or die("Silent is golden");

/**
 * Woofu Kerberos
**/
class WoofuKerberos
{
    public function __construct()
    {
        //$customFields = new KerCustomFields();
        $cart = new KerCartShortcode();
        $productShortCode = new KerProductShortcode();
        $roles = new KerRoles();
        $settings = new Ker_Settings_Tab();
        $registration = new KerRegistration();
        $productBundle= new KerProductBundle();
    }
}
