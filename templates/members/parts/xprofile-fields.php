<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

$is_required = false;
$display_none='';
if(@!$_SESSION['f_user'])
	$display_none='display-none';

?>

<div class="screen xprofile-fields <?php echo $display_none; ?> screen-<?php echo $screen_count_index; ?> <?php if (($tab_count_index - 1) == $screen_count_index) { echo 'finish'; } ?>" data-screen="<?php echo $screen_count_index; ?>">
    <form action="" method="POST">
        <p>
            <div class="basic-details-section" id="basic-details-section">
    
                <?php wp_nonce_field( 'sbsr_step_three' ); ?>
    
                <?php /***** Extra Profile Details ******/ ?>
    
                <?php if ( bp_is_active( 'xprofile' ) ) : ?>
    
                    <?php
    
                    /**
                     * Fires before the display of member registration xprofile fields.
                     */
                    do_action( 'bp_before_signup_profile_fields' ); ?>
    
                    <div class="register-section" id="profile-details-section">
    
                        <?php /* Use the profile field loop to render input fields for the 'base' profile field group */ ?>
                        <?php if ( bp_is_active( 'xprofile' ) ) : if ( bp_has_profile( array( 'profile_group_id' => 1, 'fetch_field_data' => false ) ) ) : while ( bp_profile_groups() ) : bp_the_profile_group(); ?>

    
                        <?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>
    
                            <div<?php bp_field_css_class( 'editfield' ); ?>>
    
                            <?php if (bp_get_the_profile_field_id() != 1) : ?>

                                <?php
                                $field_type = bp_xprofile_create_field_type( bp_get_the_profile_field_type() );
                                $field_type->edit_field_html();
    
                                if(bp_get_the_profile_field_is_required()){
									$is_required = true;
								}
								/**
                                 * Fires before the display of the visibility options for xprofile fields.
                                 */
                                do_action( 'bp_custom_profile_edit_fields_pre_visibility' );
    
                                if ( bp_current_user_can( 'bp_xprofile_change_field_visibility' ) ) : ?>
                                    <p class="field-visibility-settings-toggle" id="field-visibility-settings-toggle-<?php bp_the_profile_field_id() ?>">
                                        <?php
                                        printf(
                                            __( 'This field can be seen by: %s', 'buddypress' ),
                                            '<span class="current-visibility-level">' . bp_get_the_profile_field_visibility_level_label() . '</span>'
                                        );
                                        ?>
                                        <a href="#" class="visibility-toggle-link"><?php _ex( 'Change', 'Change profile field visibility level', 'buddypress' ); ?></a>
                                    </p>
    
                                    <div class="field-visibility-settings" id="field-visibility-settings-<?php bp_the_profile_field_id() ?>">
                                        <fieldset>
                                            <legend><?php _e( 'Who can see this field?', 'buddypress' ) ?></legend>
    
                                            <?php bp_profile_visibility_radio_buttons() ?>
    
                                        </fieldset>
                                        <a class="field-visibility-settings-close" href="#"><?php _e( 'Close', 'buddypress' ) ?></a>
    
                                    </div>
                                <?php else : ?>
                                    <p class="field-visibility-settings-notoggle" id="field-visibility-settings-toggle-<?php bp_the_profile_field_id() ?>">
                                        <?php
                                        printf(
                                            __( 'This field can be seen by: %s', 'buddypress' ),
                                            '<span class="current-visibility-level">' . bp_get_the_profile_field_visibility_level_label() . '</span>'
                                        );
                                        ?>
                                    </p>
                                <?php endif ?>
    
                                <?php
    
                                /**
                                 * Fires after the display of the visibility options for xprofile fields.
                                 */
                                do_action( 'bp_custom_profile_edit_fields' ); ?>
    
                                <p class="description"><?php bp_the_profile_field_description(); ?></p>
    
                            <?php endif; ?>
                            </div>
    
                        <?php endwhile; ?>

                        <input type="hidden" name="field_ids" id="field_ids" value="<?php echo substr(bp_get_the_profile_field_ids(), 2); ?>" />
    
                        <?php endwhile; endif; endif; ?>
    
                        <?php
    
                        /**
                         * Fires and displays any extra member registration xprofile fields.
                         */
                        do_action( 'bp_signup_profile_fields' ); ?>
    
                    <?php endif; ?>
    
                    </div><!-- #profile-details-section -->
    
            </div>
        </p>
    
    
        <?php if (($tab_count_index - 1) == $screen_count_index) : ?>
            <div class="submit">
			<?php if(!$is_required) { ?>
                <a class="skip" href="#"><?php _e('Skip', SBSR_TEXT_DOMAIN); ?>a</a>
				<?php } ?>
                <input type="submit" name="signup_step_three_submit" id="signup_step_three_submit" value="<?php esc_attr_e( 'Finish', 'buddypress' ); ?>" />
            </div>
        <?php else : ?>
            <div class="submit">
			<?php if(!$is_required) { ?>
                <a class="skip" href="#"><?php _e('Skip', SBSR_TEXT_DOMAIN); ?></a>
			<?php } ?>
                <input type="submit" name="signup_step_three_submit" id="signup_step_three_submit" value="<?php esc_attr_e( 'Save and continue', 'buddypress' ); ?>" />
            </div>
        <?php endif; ?>
    
    </form>
</div>
