<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="screen account-details screen-<?php echo $screen_count_index; ?>" data-screen="<?php echo $screen_count_index; ?>">
    <form>
        <p>
            <div class="basic-details-section" id="basic-details-section">
    
                <?php /***** Basic Account Details ******/ ?>
                
                <h4><?php _e( 'Account setup', SBSR_TEXT_DOMAIN ); ?></h4>
    
                <?php wp_nonce_field( 'sbsr_step_one' ); ?>
                
                <label for="signup_first_name"><?php _e( 'First Name', SBSR_TEXT_DOMAIN ); ?></label>
    
                <input type="text" name="signup_first_name" id="signup_first_name" />
    
                <label for="signup_second_name"><?php _e( 'Last Name', SBSR_TEXT_DOMAIN ); ?></label>
    
                <input type="text" name="signup_second_name" id="signup_second_name" />
    
                <label for="signup_user_name"><?php _e( 'User Name', SBSR_TEXT_DOMAIN ); ?> <?php _e( '(required)', SBSR_TEXT_DOMAIN ); ?></label>
    
                <input type="text" name="signup_user_name" id="signup_user_name" />
    
                <div>
                    <label for="signup_display_name"><?php _e( 'Display Name', SBSR_TEXT_DOMAIN ); ?></label>
                    
                    <select name="signup_display_name" id="signup_display_name">
						<option value=""></option>
                    </select>
                </div>
    
                <label for="signup_password"><?php _e( 'Password', SBSR_TEXT_DOMAIN ); ?> <?php _e( '(required)', SBSR_TEXT_DOMAIN ); ?></label>
    
                <input type="password" name="signup_password" id="signup_password"/>
    
                <label for="signup_confirm_password"><?php _e( 'Confirm password', SBSR_TEXT_DOMAIN ); ?> <?php _e( '(required)', SBSR_TEXT_DOMAIN ); ?></label>
    
                <input type="password" name="signup_confirm_password" id="signup_confirm_password"/>
    
            </div>
        </p>
    
    
        <div class="submit">
            <input type="submit" name="signup_step_one_submit" id="signup_step_one_submit" value="<?php esc_attr_e( 'Save and continue', 'buddypress' ); ?>" />
        </div>
    
    </form>
</div>
