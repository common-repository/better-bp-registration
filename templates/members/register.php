<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div id="buddypress">

    <?php
    /**
     * Fires at the top of the BuddyPress member registration page template.
     */
    do_action( 'bp_before_register_page' ); ?>

    <div class="page" id="register-page">
    
        <?php

        /** This action is documented in bp-templates/bp-legacy/buddypress/activity/index.php */
        do_action( 'template_notices' ); ?>

        <form action="" name="signup_form" id="signup_form" class="standard-form" method="post">
        

            <?php if ( 'registration-disabled' == bp_get_current_signup_step() ) : ?>

                <?php

                /**
                 * Fires before the display of the registration disabled message.
                 */
                do_action( 'bp_before_registration_disabled' ); ?>

                <p><?php _e( 'User registration is currently not allowed.', 'buddypress' ); ?></p>

                <?php

                /**
                 * Fires after the display of the registration disabled message.
                 */
                do_action( 'bp_after_registration_disabled' ); ?>

            <?php else : ?>

                <?php if ( 'request-details' == bp_get_current_signup_step() ) : ?>
                
                    <?php wp_nonce_field( 'bp_new_signup' ); ?>

                    <p><?php _e( 'Registering for this site is easy. Just fill in the fields below, and we\'ll get a new account set up for you in no time.', 'buddypress' ); ?></p>

                    <div class="register-section" id="basic-details-section">

                        <?php /***** Basic Account Details ******/ ?>

                        <h4><?php _e( 'Account Details', 'buddypress' ); ?></h4>

                        <label for="signup_email"><?php _e( 'Email Address', SBSR_TEXT_DOMAIN ); ?> <?php _e( '(required)', SBSR_TEXT_DOMAIN ); ?></label>
                        
                        <input type="email" name="signup_email" id="signup_email"/>

                        <label for="confirm_email"><?php _e( 'Confirm Email Address', SBSR_TEXT_DOMAIN ); ?> <?php _e( '(required)', SBSR_TEXT_DOMAIN ); ?></label>

                        <input type="email" name="confirm_email" id="confirm_email"/>


                    <!-- #basic-details-section -->
					<input name="signup_profile_field_ids" id="signup_profile_field_ids" value="1,2,6" type="hidden">
                    <div class="submit">
                        <input type="submit" name="signup_submit" id="signup_submit" value="<?php esc_attr_e( 'Complete Sign Up', 'buddypress' ); ?>" />
                    </div>
                <?php
					if ( is_plugin_active( 'wordpress-social-login/wp-social-login.php' ) && get_option('wsl_settings_bouncer_registration_enabled') ) {
						echo '<div id="bpbr-line-register-wrap"><div id="bpbr-line-register">or</div>';
						do_action( 'wordpress_social_login' );
						echo '</div>';
					}
				endif; ?>
					</div>
            <?php endif; // registration-disabled signup step ?>

        </form>

		<?php if ( 'completed-confirmation' == bp_get_current_signup_step() ) : ?>

			<?php

			/**
			 * Fires before the display of the registration confirmed messages.
			 */
			do_action( 'bp_before_registration_confirmed' ); ?>

			<?php if ( bp_registration_needs_activation() ) : ?>
				<p><?php _e( 'You have successfully created your account! To begin using this site you will need to activate your account within 30 minutes via the email we have just sent to your address.', SBSR_TEXT_DOMAIN ); ?></p>

                <?php
                $user_obj = get_user_by('email', sanitize_email($_POST['signup_email']));
                $error_obj =  bp_core_signup_disable_inactive($user_obj, $user_obj->data->user_login, $user_obj->data->user_pass);
                @preg_match('/<a\shref=\"([^\"]*)\">(.*)<\/a>/siU', $error_obj->errors['bp_account_not_activated'][0], $match);

                @$link = $match[1];

                echo '<a href="'. $link .'">';
                _e( 'Click here to resend activation email.', SBSR_TEXT_DOMAIN );
                echo '</a>';
                ?>

			<?php else : ?>
				<p><?php _e( 'You have successfully created your account! Please log in using the username and password you have just created.', 'buddypress' ); ?></p>
			<?php endif; ?>

			<?php

			/**
			 * Fires after the display of the registration confirmed messages.
			 */
			do_action( 'bp_after_registration_confirmed' ); ?>

		<?php endif; // completed-confirmation signup step ?>

    </div>

    <?php

    /**
     * Fires at the bottom of the BuddyPress member registration page template.
     */
    do_action( 'bp_after_register_page' ); ?>

</div><!-- #buddypress -->
