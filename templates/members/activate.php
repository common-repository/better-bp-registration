<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div id="buddypress">

	<?php

	/**
	 * Fires before the display of the member activation page.
	 */
	do_action( 'bp_before_activation_page' ); ?>

	<div class="page" id="activate-page">

		<?php

		/** This action is documented in bp-templates/bp-legacy/buddypress/activity/index.php */
		do_action( 'template_notices' ); ?>

		<?php

		/**
		 * Fires before the display of the member activation page content.
		 */
		do_action( 'bp_before_activate_content' ); ?>

        <?php
            $user = SBSR_User::getInstance();
            $activation_step = $user->get_activation_step();

        ?>

        <?php if ($activation_step == 1) : ?>

            <div class="step-panel">
                <?php $tab_count_index = 1; ?>
				<?php if(@!$_SESSION['f_user']) { ?>
                <div class="account-details active tab-index-<?php echo $tab_count_index;?>">
                    <?php _e('Step ' . $tab_count_index++ , 'SBSR_TEXT_DOMAIN'); ?>
                </div>
				
                <div class="create-avatar tab-index-<?php echo $tab_count_index;?>">
                    <?php _e('Step ' . $tab_count_index++ , 'SBSR_TEXT_DOMAIN'); ?>
                </div>
				<?php } ?>
                <?php if (bp_is_active( 'xprofile' )) : ?>
                    <div class="xprofile-fields tab-index-<?php echo $tab_count_index;?>">
                        <?php _e('Step ' . $tab_count_index++ , 'SBSR_TEXT_DOMAIN'); ?>
                    </div>
                <?php endif; ?>

                <?php if (bp_is_active( 'groups' )) : ?>
                    <div class="groups tab-index-<?php echo $tab_count_index;?>">
                        <?php _e('Step ' . $tab_count_index++ , 'SBSR_TEXT_DOMAIN'); ?>
                    </div>
                <?php endif; ?>

                <?php if (bp_is_active( 'friends' )) : ?>
                    <div class="friends tab-index-<?php echo $tab_count_index;?>">
                        <?php _e('Step ' . $tab_count_index++ , 'SBSR_TEXT_DOMAIN'); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="screens">

                <?php $screen_count_index = 1; ?>

				<?php if(@!$_SESSION['f_user']) { ?>
                <?php require_once('parts/account-details.php'); ?>
				
				
                <?php $screen_count_index++; ?>

                <?php require_once('parts/create-avatar.php'); ?>
				<?php }else{
					$screen_count_index = 0;
				} ?>
                <?php if (bp_is_active( 'xprofile' )) : ?>
                    <?php $screen_count_index++; ?>
                    <?php require_once('parts/xprofile-fields.php'); ?>
                <?php endif; ?>

                <?php if (bp_is_active( 'groups' )) : ?>
                    <?php $screen_count_index++; ?>
                    <?php require_once('parts/groups.php'); ?>
                <?php endif; ?>

                <?php if (bp_is_active( 'friends' )) : ?>
                    <?php $screen_count_index++; ?>
                    <?php require_once('parts/friends.php'); ?>
                <?php endif; ?>
            </div>

        <?php endif; ?>

        <?php if ($activation_step == 0) : ?>

			<p><?php _e( 'Please provide a valid activation key.', 'buddypress' ); ?></p>

			<form action="" method="get" class="standard-form" id="activation-form">

				<label for="key"><?php _e( 'Activation Key:', 'buddypress' ); ?></label>
				<input type="text" name="key" id="key" value="" />

				<p class="submit">
					<input type="submit" name="submit" value="<?php esc_attr_e( 'Activate', 'buddypress' ); ?>" />
				</p>

			</form>

        <?php endif; ?>

		<?php

		/**
		 * Fires after the display of the member activation page content.
		 */
		do_action( 'bp_after_activate_content' ); ?>

	</div><!-- .page -->

	<?php

	/**
	 * Fires after the display of the member activation page.
	 */
	 unset($_SESSION['f_user']);
	do_action( 'bp_after_activation_page' ); ?>

</div><!-- #buddypress -->
