<?php

defined("KER_EXEC") or die("Silent is golden");

add_action( 'init', 'login_check' );
function login_check() {
    global $wp;
    if( ! is_user_logged_in() && ! admin_is_login_page() ) {
        wp_redirect( wp_login_url( site_url() ) );
        exit;
    }
    else{
        //show_admin_bar(false);        	
        /* Disable WordPress Admin Bar for all users */
        add_filter( 'show_admin_bar', '__return_false' );
    }
}

function admin_is_login_page() {
    return in_array($GLOBALS['pagenow'], array('wp-login.php'));
}

function wpdocs_my_login_redirect( $url, $request, $user ) {
    if ( $user && is_object( $user ) && is_a( $user, 'WP_User' ) ) {
        if ( $user->has_cap( 'administrator' ) ) {
            $url = home_url();
        } else {
            $url = home_url();
        }
    }
    return $url;
}
 
add_filter( 'login_redirect', 'wpdocs_my_login_redirect', 10, 3 );


	
//Class not working

//class KerHideHeader
//{
//    public function __construct(){
//        add_action( 'init', 'login_check' );
//    }
//
//    public function login_check() {
//        global $wp;
//        if( ! is_user_logged_in() && ! admin_is_login_page() ) {
//            wp_redirect( wp_login_url( site_url( $wp->request ) ) );
//            exit;
//        }
//        else{
//            //show_admin_bar(false);        	
//            /* Disable WordPress Admin Bar for all users */
//            add_filter( 'show_admin_bar', '__return_false' );
//        }
//    }
//    
//    public function admin_is_login_page() {
//        return in_array($GLOBALS['pagenow'], array('wp-login.php'));
//    }
//}