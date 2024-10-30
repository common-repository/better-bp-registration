<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="screen friends display-none screen-<?php echo $screen_count_index; ?> <?php if (($tab_count_index - 1) == $screen_count_index) { echo 'finish'; } ?>" data-screen="<?php echo $screen_count_index; ?>">
    <form>
        <div class="basic-details-section" id="basic-details-section">

            <h2><?php _e( 'Add friends', SBSR_TEXT_DOMAIN ); ?></h2>

            <?php wp_nonce_field( 'sbsr_step_five' ); ?>

            <p>
                <?php echo sprintf(__('Your friends might already be on "%s". You can search and add them.', SBSR_TEXT_DOMAIN), get_bloginfo('name')); ?>
            </p>

            <p class="search-notice" style="display: none;">
                <?php echo sprintf(__('We have found the following registered users with the name or email %s', SBSR_TEXT_DOMAIN), '<span class="search-word"></span>'); ?>
            </p>

            <div class="search-result">
                <!-- Search result shown here -->   
            </div>

            <h3 class="search-title"><?php _e('Perform search', SBSR_TEXT_DOMAIN); ?></h3>

            <div class="ajax-search">
                <div>
                    <label for="search-by-name">
                        <?php _e('Search by name or email', SBSR_TEXT_DOMAIN); ?>
                        <input type="text" id="search-by-name" name="search_by_name" autocomplete="off">
                    </label>
                </div>
            </div>
        </div>

    
        <div class="submit">
            <a class="skip" href="#"><?php _e('Skip', SBSR_TEXT_DOMAIN); ?></a>
            <input type="submit" class="finish" name="signup_step_five_submit" id="signup_step_one_submit" value="<?php esc_attr_e( 'Finish', 'buddypress' ); ?>" />
        </div>
    
    </form>
</div>
