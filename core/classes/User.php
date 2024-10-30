<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class SBSR_User
{
    use Singleton;

    private $activation_step;
    private $user_id;

    public function update_user_account($user_id, $first_name = '', $last_name = '', $user_name, $display_name = '', $user_password)
    {
        $userdata = array(
            'ID' => $user_id,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'display_name' => $display_name,
            'user_nicename' => $user_name,
            'nickname' => $user_name,
            'user_pass' => $user_password
        );

        
		
        global $wpdb;
        $result = $wpdb->update($wpdb->users, array('user_login' => $user_name), array('ID' => $user_id));
		$updated_user_id = wp_update_user($userdata);
        if ($result == false) {
            return false;
        }
		//var_dump($user_name);die();
        return true;
    }

    public function set_user_avatar($user_id, $avatar_data)
    {
        $webcam_avatar = false;
        $webcam_avatar = str_replace( array( 'data:image/png;base64,', ' ' ), array( '', '+' ), $avatar_data );
        $webcam_avatar = base64_decode( $webcam_avatar );

        if ( ! bp_avatar_handle_capture( $webcam_avatar, $user_id ) ) {
            return false;
        } else {
            do_action( 'xprofile_avatar_uploaded', (int) $user_id, 'camera' );
        }

        return true;
    }

    public function set_xprofile_fields($user_id, $data_arr)
    {

        // No errors.
        $errors = false;

        // Check to see if any new information has been submitted.
        if ( isset( $data_arr['field_ids'] ) ) {

            // Explode the posted field IDs into an array so we know which
            // fields have been submitted.
            $posted_field_ids = wp_parse_id_list( $data_arr['field_ids'] );
            $is_required      = array();

            // Loop through the posted fields formatting any datebox values
            // then validate the field.
            foreach ( (array) $posted_field_ids as $field_id ) {
                if ( !isset( $data_arr['field_' . $field_id] ) ) {

                    if ( !empty( $data_arr['field_' . $field_id . '_day'] ) && !empty( $data_arr['field_' . $field_id . '_month'] ) && !empty( $data_arr['field_' . $field_id . '_year'] ) ) {
                        // Concatenate the values.
                        $date_value =   $data_arr['field_' . $field_id . '_day'] . ' ' . $data_arr['field_' . $field_id . '_month'] . ' ' . $data_arr['field_' . $field_id . '_year'];

                        // Turn the concatenated value into a timestamp.
                        $data_arr['field_' . $field_id] = date( 'Y-m-d H:i:s', strtotime( $date_value ) );
                    }

                }

                $is_required[ $field_id ] = xprofile_check_is_required_field( $field_id );
                if ( $is_required[$field_id] && empty( $data_arr['field_' . $field_id])  ) {
				//	var_dump($data_arr);
				   $errors = 'Please fill required fields';
                }
            }

            // There are errors.
            if ( !empty( $errors ) ) {
                return $errors;
            } else {

                // Reset the errors var.
                $errors = false;

                // Now we've checked for required fields, lets save the values.
                $old_values = $new_values = array();
                foreach ( (array) $posted_field_ids as $field_id ) {

                    // Certain types of fields (checkboxes, multiselects) may come through empty. Save them as an empty array so that they don't get overwritten by the default on the next edit.
                    $value = isset( $data_arr['field_' . $field_id] ) ? $data_arr['field_' . $field_id] : '';
					
                    $visibility_level = !empty( $data_arr['field_' . $field_id . '_visibility'] ) ? $data_arr['field_' . $field_id . '_visibility'] : 'public';

                    // Save the old and new values. They will be
                    // passed to the filter and used to determine
                    // whether an activity item should be posted.
                    $old_values[ $field_id ] = array(
                        'value'      => xprofile_get_field_data( $field_id, $user_id ),
                        'visibility' => xprofile_get_field_visibility_level( $field_id, $user_id ),
                    );

                    // Update the field data and visibility level.
                    xprofile_set_field_visibility_level( $field_id, $user_id, $visibility_level );
					
					
                    $field_updated = xprofile_set_field_data( $field_id, $user_id, $value, $is_required[ $field_id ] );
                    $value         = xprofile_get_field_data( $field_id, $user_id );

                    $new_values[ $field_id ] = array(
                        'value'      => $value,
                        'visibility' => xprofile_get_field_visibility_level( $field_id, $user_id ),
                    );

                    if ( ! $field_updated ) {
                        $errors = 'Fields error';
                    } else {

                        /**
                         * Fires on each iteration of an XProfile field being saved with no error.
                         *
                         * @since 1.1.0
                         *
                         * @param int    $field_id ID of the field that was saved.
                         * @param string $value    Value that was saved to the field.
                         */
                        do_action( 'xprofile_profile_field_data_updated', $field_id, $value );
                    }
                }

                /**
                 * Fires after all XProfile fields have been saved for the current profile.
                 *
                 * @since 1.0.0
                 *
                 * @param int   $value            Displayed user ID.
                 * @param array $posted_field_ids Array of field IDs that were edited.
                 * @param bool  $errors           Whether or not any errors occurred.
                 * @param array $old_values       Array of original values before updated.
                 * @param array $new_values       Array of newly saved values after update.
                 */

                do_action( 'xprofile_updated_profile', $user_id, $posted_field_ids, $errors, $old_values, $new_values );

                // Set the feedback messages.
                if ( !empty( $errors ) ) {
                    return $errors ;
                } else {
                    return true;
                }
            }
        }
    }

    public function set_groups($user_id, $data_arr)
    {
        $groups = array();

        foreach ($data_arr as $post_key => $post_value) {
            $group_arr = explode('_', $post_key);
            if (count($group_arr) == 2) {
                list($group, $group_id) = $group_arr;
                if ($group == 'group' && $post_value == 'on') {
                    $groups[] = $group_id;
                }
            }
        }

        $result = array();

        foreach ($groups as $group_id) {
            $result[] = groups_join_group($group_id, $user_id);
        }

        return true;
    }

    public function set_current_user_id($user_id)
    {
        $this->user_id = $user_id;
    }

    public function get_user_id()
    {
        return $this->user_id;
    }

    public function get_user_email()
    {
        $userdata = get_userdata($this->user_id);
        return $userdata->user_email;
    }

    public function set_activation_step($step)
    {
        $this->activation_step = $step;
    }

    public function get_activation_step()
    {
        return $this->activation_step;
    }

    public static function check_ajax_user()
    {
        $user_id = intval($_POST['userID']);

		if (!$user_id) {
			wp_die('');
		}

        $user_email = sanitize_email($_POST['userEmail']);

        $user_by_id = get_userdata($user_id);

        if ($user_by_id->user_email !== $user_email) {
            wp_die();
        }
    }
}
