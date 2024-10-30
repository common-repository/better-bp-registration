<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function sbsr_screen_activation() {

	// Bail if not viewing the activation page.
	if ( ! bp_is_current_component( 'activate' ) ) {
		return false;
	}

	// If the user is already logged in, redirect away from here.
	if ( is_user_logged_in() ) {

		// If activation page is also front page, set to members directory to
		// avoid an infinite loop. Otherwise, set to root domain.
		$redirect_to = bp_is_component_front_page( 'activate' )
			? bp_get_members_directory_permalink()
			: bp_get_root_domain();

		// Trailing slash it, as we expect these URL's to be.
		$redirect_to = trailingslashit( $redirect_to );

		/**
		 * Filters the URL to redirect logged in users to when visiting activation page.
		 *
		 * @param string $redirect_to URL to redirect user to.
		 */
		$redirect_to = apply_filters( 'bp_loggedin_activate_page_redirect_to', $redirect_to );

		// Redirect away from the activation page.
		bp_core_redirect( $redirect_to );
	}

	// Grab the key (the old way).
	$key = isset( $_GET['key'] ) ? $_GET['key'] : '';

	// Grab the key (the new way).
	if ( empty( $key ) ) {
		$key = bp_current_action();
	}

	// Get BuddyPress.
	$bp = buddypress();

    $user = SBSR_User::getInstance();

	// We've got a key; let's attempt to activate the signup.
	if (!empty($key)) {
    
        /**
         * Filters the activation signup.
         *
         * @param bool|int $value Value returned by activation.
         *                        Integer on success, boolean on failure.
         */
		
		$signups = BP_Signup::get( array(
			'activation_key' => $key,
		) );

		if ( empty( $signups['signups'] ) ) {
			return new WP_Error( 'invalid_key', __( 'Invalid activation key.', 'buddypress' ) );
		}

		$signup = $signups['signups'][0];

		if ( $signup->active ) {
			if ( empty( $signup->domain ) ) {
				return new WP_Error( 'already_active', __( 'The user is already active.', 'buddypress' ), $signup );
			} else {
				return new WP_Error( 'already_active', __( 'The site is already active.', 'buddypress' ), $signup );
			}
		}
		$user_id = username_exists( $signup->user_login );
		
		if(get_user_meta( $user_id, 'wsl_current_provider',true  )=='Facebook'){
			global $wpdb;
			$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->users} SET user_status = 0 WHERE ID = %d", $user_id ));
			bp_delete_user_meta( $user_id, 'activation_key' );
			if ( apply_filters( 'bp_core_send_user_registration_admin_notification', true ) ) {
				wp_new_user_notification( $user_id );
			}
				
		}else{
			$user_id = apply_filters('bp_core_activate_account', bp_core_activate_signup($key));
		}

        if (is_int($user_id)) {

            $user->set_current_user_id($user_id);

            $user->set_activation_step(1);

            // If there were errors, add a message and redirect.
            if ( ! empty( $user->errors ) ) {
                bp_core_add_message( $user->get_error_message(), 'error' );
                bp_core_redirect( trailingslashit( bp_get_root_domain() . '/' . $bp->pages->activate->slug ) );
            }

            $bp->activation_complete = true;
			

        } else {
            if (!is_user_logged_in()) {
                $user->set_activation_step(0);
            }
        }
    }

    $step = $user->get_activation_step();

    if ($step == 1) {
        add_action('wp_enqueue_scripts', function() {
            wp_enqueue_style('sbsr-register', SBSR_PLUGIN_URL . 'css/sbsr-register.css', date('m-d-Y-h-i-s-a', time()));
            wp_enqueue_style('agplmodal', SBSR_PLUGIN_URL . 'css/jquery.sgplmodal.css');
            wp_enqueue_style('croppie', SBSR_PLUGIN_URL . 'css/croppie.css');

            $user = SBSR_User::getInstance();
            wp_enqueue_script('sbsr-account', SBSR_PLUGIN_URL . 'js/account.js', array('jquery', 'jquery-typewatch'), date('m-d-Y-h-i-s-a', time()));
            wp_localize_script( 'sbsr-account', 'user_object', array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'userID' => $user->get_user_id(),
                'userEmail' => $user->get_user_email()
            ));

            wp_enqueue_script('webcam', SBSR_PLUGIN_URL . 'js/webcam.min.js', array('jquery'));
            wp_enqueue_script('sgplmodal', SBSR_PLUGIN_URL . 'js/jquery.sgplmodal.js', array('jquery'));
            wp_enqueue_script('croppie', SBSR_PLUGIN_URL . 'js/croppie.min.js', array('jquery'));
            wp_enqueue_script('sbsr-webcam', SBSR_PLUGIN_URL . 'js/sbsr-webcam.js', array('jquery','sgplmodal','croppie', 'webcam'), date('m-d-Y-h-i-s-a', time()));
            wp_enqueue_script('jquery-typewatch', SBSR_PLUGIN_URL . 'js/jquery.typewatch.js', array('jquery'));
            wp_enqueue_script('sbsr-friends', SBSR_PLUGIN_URL . 'js/sbsr-friends.js', array('jquery', 'jquery-typewatch'), date('m-d-Y-h-i-s-a', time()));
        });
    }


	/**
	 * Filters the template to load for the Member activation page screen.
	 *
	 * @param string $value Path to the Member activation template to load.
	 */
	bp_core_load_template( apply_filters( 'bp_core_template_activate', array( 'activate', 'registration/activate' ) ) );
}
remove_action( 'bp_screens', 'bp_core_screen_activation' );
add_action( 'bp_screens', 'sbsr_screen_activation' );


