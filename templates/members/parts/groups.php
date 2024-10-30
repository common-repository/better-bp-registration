<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="screen groups display-none screen-<?php echo $screen_count_index; ?> <?php if (($tab_count_index - 1) == $screen_count_index) { echo 'finish'; } ?>" data-screen="<?php echo $screen_count_index; ?>">
    <form action="" method="POST">
        <p>
                <h4><?php _e( 'Join Groups', 'buddypress' ); ?></h4>
    
                <?php wp_nonce_field( 'sbsr_step_four' ); ?>
    
                <div class="basic-details-section" id="basic-details-section">
    
    
                    <?php if ( bp_is_active( 'groups' ) ) : ?>
    
                        <?php function tree_builder($branch) { ?>
    
                            <?php if (!empty($branch['sub_groups'])) : ?>
                                <div>
                                    <label for="<?= $branch['slug']; ?>">
                                        <input type="checkbox" name="group_<?= $branch['id']; ?>" id="<?= $branch['slug']; ?>">
                                        <?= $branch['name']; ?>
                                    </label>
                                    <div class="sub-groups" style="margin-left: 25px;">
                                        <?php foreach ($branch['sub_groups'] as $sub_group) : ?>
    
                                            <?php tree_builder($sub_group); ?>
    
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php else : ?>
                                <div>
                                    <label for="<?= $branch['slug']; ?>">
                                        <input type="checkbox" name="group_<?= $branch['id']; ?>" id="<?= $branch['slug']; ?>">
                                        <?= $branch['name']; ?>
                                    </label>
                                </div>
                            <?php endif; ?>
    
                        <?php } 
                            $groups_tree = sbsr_groups_tree();
                            if (!empty($groups_tree)) {
                                foreach ($groups_tree as $branch) {
                                    tree_builder($branch);
                                } 
                            } else {
                                ?>
                                    <p><?php _e('Groups not found', SBSR_TEXT_DOMAIN); ?></p>
                                <?php
                            }
                        ?> 
                    <?php endif; ?>
    
                </div>
        </p>
    
        <?php if (($tab_count_index - 1) == $screen_count_index) : ?>
            <div class="submit">
                <a class="skip" href="#"><?php _e('Skip', SBSR_TEXT_DOMAIN); ?></a>
                <input type="submit" name="signup_step_four_submit" id="signup_step_four_submit" value="<?php esc_attr_e( 'Finish', 'buddypress' ); ?>" />
            </div>
        <?php else : ?>
            <div class="submit">
                <a class="skip" href="#"><?php _e('Skip', SBSR_TEXT_DOMAIN); ?></a>
                <input type="submit" name="signup_step_four_submit" id="signup_step_four_submit" value="<?php esc_attr_e( 'Save and continue', 'buddypress' ); ?>" />
            </div>
        <?php endif; ?>
    
    <form>
</div>
