<?php
/*
 Plugin Name:         BuddyPress Better Registration
 Plugin URI:        https://wordpress.org/plugins/better-bp-registration/
 Description:         Make the registration process REALLY simple - reduce user resistance and increase community engagement.
 Version:               1.6
  Author:              sooskriszta, webforza
  Author URI: https://profiles.wordpress.org/sooskriszta#content-plugins
  Text Domain: better-bp-registration
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function sbsr_bp_registration() {

    define('SBSR_PLUGIN_PATH', plugin_dir_path(__FILE__));
    define('SBSR_PLUGIN_URL', plugin_dir_url(__FILE__));

    define('SBSR_TEXT_DOMAIN', 'text_domain');

    require_once('core/trait/trait.php');
    require_once('core/functions.php');

    require_once('core/classes/User.php');

    require_once('core/ajax.php');

    /* add signup handler */
    require_once('core/signup.php');

    /* add activation handler */
    require_once('core/activation.php');
	
	require_once('core/facebook.php');

    function register_template_location() {
        return SBSR_PLUGIN_PATH . 'templates/';
    }

    function sbsr_maybe_replace_template( $templates, $slug, $name  ) {
        if('members/register' != $slug) {
            return $templates;
        }
        return array('members/single.php');
    }

    function sbsr_start() {
        if(function_exists( 'bp_register_template_stack')) {
            bp_register_template_stack('register_template_location');
        }

        // if viewing a member page, overload the template
        if (bp_is_user()) {
            add_filter( 'bp_get_template_part', 'sbsr_maybe_replace_template', 10, 3  );
        } 
    }
    add_action( 'bp_init', 'sbsr_start');
}
add_action( 'bp_include', 'sbsr_bp_registration'  );

add_action( 'set_logged_in_cookie', function($logged_in_cookie) {
    $_COOKIE[LOGGED_IN_COOKIE] = $logged_in_cookie;
});

/* Change recipient.name on recipient.email on email letter */
function sbsr_change_recipient_name($str) {
    $token = '{{recipient.email}}';
    return sprintf( _x( 'Hi %s,', 'recipient salutation', 'buddypress' ), $token );
}
add_filter( 'bp_email_get_salutation', 'sbsr_change_recipient_name');

/* Check activate or not BP plugin */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

add_action('admin_init', function() {
    if ( !is_plugin_active( 'buddypress/bp-loader.php' ) ) {
        deactivate_plugins( plugin_basename( __FILE__ ) );

        add_action( 'admin_notices', function() {
            ?>
            <div class="notice notice-error is-dismissible">
                <p><?php _e( '<a href="https://buddypress.org/" target="_blank">BuddyPress</a> plugin is required for "Better BP Registration" to work', 'SBSR_TEXT_DOMAIN' ); ?></p>
            </div>
            <?php
        });

        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
    } 
});

add_filter('gettext', function($text) {
    if($text == 'Sorry, that username already exists!') {
        $text = 'Sorry, that email is already registered!';
    }

    if($text == 'Sorry, that email address is already used!') {
        $text = 'Sorry, that email is already registered!';
    }
    return $text;
});

add_action( 'sbsr_delete_user_if_not_active_hook', function($user_id, $user_email) {
    global $wpdb;
    $status = $wpdb->get_var("
        SELECT active 
        FROM {$wpdb->prefix}signups 
        WHERE user_email = '{$user_email}'
    ");
    if ($status == 0) {
        $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}signups WHERE user_email = %s", $user_email));
        $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}users WHERE ID = %d", $user_id));
    }
}, 1, 2);

 add_filter( 'bp_attachments_current_user_can', function(){
	return true;
 } ); 