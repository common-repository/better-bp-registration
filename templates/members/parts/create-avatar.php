<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="screen create-avatar display-none screen-<?php echo $screen_count_index; ?> <?php if (($tab_count_index - 1) == $screen_count_index) { echo 'finish'; } ?>" data-screen="<?php echo $screen_count_index; ?>">
<form action="" method="POST" enctype="multipart/form-data">
        <p>
            <div class="basic-details-section" id="basic-details-section">
    
                <h4><?php _e( 'Set your profile picture', SBSR_TEXT_DOMAIN ); ?></h4>
    
                <?php wp_nonce_field( 'sbsr_step_two' ); ?>
    
                <?php $user = SBSR_User::getInstance(); ?>
                <div class="wrapp-ava-img">
                    <img class="avatar-result" src="<?php echo get_avatar_url( $user->get_user_id(),225 ); ?>"></img>
                </div>
    
                <div class="wrapp-ava-link">
                    <div>
                        <input type='file' class="file-field hidden" id="imgInp" />
                        <a class="upload-photo" href="#">
                            <span><?php _e('Upload a photo', SBSR_TEXT_DOMAIN); ?></span>
                        </a>
                        <br>
                        <?php _e('from you computer', SBSR_TEXT_DOMAIN);?>
                    </div>
                    <div class="">
                        <span class="divider-line">&mdash;&mdash;&mdash;&mdash;</span>&nbsp;OR&nbsp;<span class="divider-line">&mdash;&mdash;&mdash;&mdash;</span>
                    </div>
                    <div>
                        <a class="fancybox webcam" id="inline" href="#webcam"><?php _e('Take a photo', SBSR_TEXT_DOMAIN);?></a>
                        <br>
                        <?php _e('with your webcam/mobile camera', SBSR_TEXT_DOMAIN);?>
                    </div>
                </div>

                <div class="clear-fix"></div>
                
                <input type="hidden" name="avatar" id="avatar" value="">
    
            </div>
        </p>
    
    
        <?php if (($tab_count_index - 1) == $screen_count_index) : ?>
            <div class="submit">
                <a class="skip" href="#"><?php _e('Skip', SBSR_TEXT_DOMAIN); ?></a>
                <input type="submit" name="signup_step_two_submit" id="signup_step_two_submit" value="<?php esc_attr_e( 'Finish', 'buddypress' ); ?>" />
            </div>
        <?php else : ?>
            <div class="submit">
                <a class="skip" href="#"><?php _e('Skip', SBSR_TEXT_DOMAIN); ?></a>
                <input type="submit" name="signup_step_two_submit" id="signup_step_two_submit" value="<?php esc_attr_e( 'Save and continue', 'buddypress' ); ?>" />
            </div>
        <?php endif; ?>
    
    </form>
    
    <div style="display:none;">
        <div id="local">
            <div class="image-wrapper">
                <!-- <img id="imgraw" src="#" class="raw-image"/> -->
                <div id="imgraw"></div>
            </div>
            
            <div class="action">
                
                <button id="crop-button"><?php _e('Save', SBSR_TEXT_DOMAIN);?></button>
            </div>
        </div>
    </div>
    
    <div style="display:none;">
        <div id="webcam">
            <div class="webcam-result"> </div>
            <div class="webcam-stream"> </div>
            
            <div class="action">
                <button id="webcam-snapshot-button"><?php _e('Take photo', SBSR_TEXT_DOMAIN); ?></button>
                <button id="webcam-crop-button" style="display: none;"><?php _e('Save', SBSR_TEXT_DOMAIN);?></button>
            </div>
        </div>
    </div>
</div>
