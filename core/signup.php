<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Handle the loading of the signup screen.
 */
function sbsr_screen_signup() {
	$bp = buddypress();

	if ( ! bp_is_current_component( 'register' ) || bp_current_action() )
		return;

	// Not a directory.
	bp_update_is_directory( false, 'register' );

    add_action('wp_enqueue_scripts', function() {
        wp_enqueue_script('sbsr-register', SBSR_PLUGIN_URL . 'js/sbsr-register.js', array('jquery'), date('m-d-Y-h-i-s-a', time()));
        wp_enqueue_style('sbsr-register', SBSR_PLUGIN_URL . 'css/sbsr-register-email.css');
    });

	// If the user is logged in, redirect away from here.
	if ( is_user_logged_in() ) {

		$redirect_to = bp_is_component_front_page( 'register' )
			? bp_get_members_directory_permalink()
			: bp_get_root_domain();

		/**
		 * Filters the URL to redirect logged in users to when visiting registration page.
		 *
		 * @since 1.5.1
		 *
		 * @param string $redirect_to URL to redirect user to.
		 */
		bp_core_redirect( apply_filters( 'bp_loggedin_register_page_redirect_to', $redirect_to ) );

		return;
	}

	$bp->signup->step = 'request-details';


	if ( !bp_get_signup_allowed() ) {
		$bp->signup->step = 'registration-disabled';

		// If the signup page is submitted, validate and save.
	} elseif ( isset( $_POST['signup_submit'] ) && bp_verify_nonce_request( 'bp_new_signup' ) ) {
	
	    /**
		 * Fires before the validation of a new signup.
		 *
		 * @since 2.0.0
		 */
		do_action( 'bp_signup_pre_validate' );

        $bp->signup->username = $username = sanitize_email($_POST['signup_email']);
        $bp->signup->email = $useremail = sanitize_email($_POST['signup_email']);

		// Add any errors to the action for the field in the template for display.
		if ( !empty( $bp->signup->errors ) ) {

		} else {
			$bp->signup->step = 'save-details';

			// No errors! Let's register those deets.
			$active_signup = bp_core_get_root_option( 'registration' );

			if ( 'none' != $active_signup ) {
				

				if ( bp_is_active( 'xprofile' ) ) {
					// Let's compact any profile field info into usermeta.
					$profile_field_ids = explode( ',', $_POST['signup_profile_field_ids'] );

					/*
					 * Loop through the posted fields, formatting any
					 * datebox values, then add to usermeta.
					 */
					foreach ( (array) $profile_field_ids as $field_id ) {
						bp_xprofile_maybe_format_datebox_post_data( $field_id );

						if ( !empty( $_POST['field_' . $field_id] ) )
							$usermeta['field_' . $field_id] = $_POST['field_' . $field_id];

						if ( !empty( $_POST['field_' . $field_id . '_visibility'] ) )
							$usermeta['field_' . $field_id . '_visibility'] = $_POST['field_' . $field_id . '_visibility'];
					}

					// Store the profile field ID's in usermeta.
					$usermeta['profile_field_ids'] = $_POST['signup_profile_field_ids'];
				}

				// Hash and store the password.
                $password = wp_generate_password();
				$usermeta['password'] = wp_hash_password( $password );


                $wp_user_id = bp_core_signup_user( $username, $password, $useremail, $usermeta );
			
                /* add to sheduler action delete user if not activate after 30min */
                wp_schedule_single_event( time() + 60*60*24, 'sbsr_delete_user_if_not_active_hook', array($wp_user_id, $useremail) );

				// Finally, sign up the user and/or blog.
				if ( is_wp_error( $wp_user_id ) ) {
					$bp->signup->step = 'request-details';
					bp_core_add_message( $wp_user_id->get_error_message(), 'error' );
				} else {
					$bp->signup->step = 'completed-confirmation';
				}

			}
			//die('aa');
			
			/**
			 * Fires after the completion of a new signup.
			 */
			do_action( 'bp_complete_signup' );
		}

	}


	/**
	 * Fires right before the loading of the Member registration screen template file.
	 */
	do_action( 'bp_core_screen_signup' );

	/**
	 * Filters the template to load for the Member registration page screen.
	 *
	 * @param string $value Path to the Member registration template to load.
	 */
	bp_core_load_template( apply_filters( 'bp_core_template_register', array( 'register', 'registration/register' ) ) );
}
add_action( 'wp_loaded', function(){
	remove_action( 'bp_screens', 'bp_core_screen_signup'); 
} );

add_action( 'bp_screens', 'sbsr_screen_signup');
