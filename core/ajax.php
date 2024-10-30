<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function sbsr_search_friend_by_name() {
    SBSR_User::check_ajax_user();
    if (empty($_POST['search_word'])) {
        echo json_encode(false);
        wp_die();
    }

    $users = new WP_User_Query( array(
        'search'         => '*'.sanitize_text_field( $_POST['search_word'] ).'*',
        'search_columns' => array(
            'user_login',
            'user_nicename',
            'user_email',
        ),
    ));

    $users_found = $users->get_results();

    $result = array();

    foreach ($users_found as $user) {

        if ($user->data->ID == get_current_user_id()) {
            break;
        }

        $user = array(
            'user_id' => $user->data->ID,
            'name' => $user->data->display_name,
            'avatar_url' => bp_core_fetch_avatar(array(
                'item_id' => $user->data->ID,
                'html' => false,
                'type' => 'full'
            ))
        );

        $result[] = $user;
    }

    echo json_encode($result);

    wp_die();
}
add_action('wp_ajax_sbsr_search_friend_by_name', 'sbsr_search_friend_by_name');
add_action('wp_ajax_nopriv_sbsr_search_friend_by_name', 'sbsr_search_friend_by_name');

function sbsr_send_friend_request() {
    //var_dump($result);die();
	//SBSR_User::check_ajax_user();

	$user_id = intval( $_POST['userID'] );

	if (!$user_id) {
		wp_die('wrong id');
	}

    $result = friends_add_friend(get_current_user_id(), $user_id);

    echo json_encode($result);
    wp_die();
}
add_action('wp_ajax_sbsr_send_friend_request', 'sbsr_send_friend_request');
add_action('wp_ajax_nopriv_sbsr_send_friend_request', 'sbsr_send_friend_request');

function sbsr_update_user_profile() {
    SBSR_User::check_ajax_user();

    $user_id = intval($_POST['userID']);

	if (!$user_id) {
		wp_die();
	}

    $data = $_POST['data'];
    $data = stripslashes($data);
    $data = json_decode($data);

    $user_info = array();


    foreach ($data as $field) {
        switch ($field->name) {
        case 'signup_first_name':
            $user_info['first_name'] = sanitize_text_field($field->value);
            break;
        case 'signup_second_name':
            $user_info['last_name'] = sanitize_text_field($field->value);
            break;
        case 'signup_user_name':
            $user_info['user_name'] = sanitize_user($field->value);
            break;
        case 'signup_display_name':
            $user_info['display_name'] = sanitize_text_field($field->value);
            break;
        case 'signup_password':
            $user_info['user_password'] = $field->value;
            break;
        case 'signup_confirm_password':
            $user_info['user_password_confirm'] = $field->value;
            break;
        }
    }

    /* update_user_account($user_id, $first_name, $last_name, $user_name, $display_name, $user_password) */
    $user = SBSR_User::getInstance();
    $result = $user->update_user_account($user_id, $user_info['first_name'], $user_info['last_name'], $user_info['user_name'], $user_info['display_name'], $user_info['user_password']);

    if (bp_is_active('xprofile')) {
        $result = $user->set_xprofile_fields( $user_id,
            array(
                'field_1' => $user_info['display_name'],
                'field_ids' => '1',
            )
        );
    }
        
    echo json_encode($result);
    wp_die();
}
add_action('wp_ajax_sbsr_update_user_profile', 'sbsr_update_user_profile');
add_action('wp_ajax_nopriv_sbsr_update_user_profile', 'sbsr_update_user_profile');

function sbsr_set_avatar() {
    SBSR_User::check_ajax_user();
	$safe_user_id = intval( $_POST['userID'] );

	if ( !$safe_user_id ) {
		wp_die('aaa');
	}

    $user_id = $safe_user_id;

    $data = $_POST['data'];

    $data = stripslashes($data);
    $data = json_decode($data);

    foreach ($data as $field) {
        switch ($field->name) {
		case 'avatar':
            $avatar_data = $field->value;
			/*if ( !base64_decode( $avatar_data ) ) {
				wp_die('bbb');
			}*/
            break;
        }
    }

    /* public function set_user_avatar($user_id, $avatar_data) */
    $user = SBSR_User::getInstance();
    $result = $user->set_user_avatar($user_id, $avatar_data);
	//var_dump($result);
    echo json_encode($result);
    wp_die();
}
add_action('wp_ajax_sbsr_set_avatar', 'sbsr_set_avatar');
add_action('wp_ajax_nopriv_sbsr_set_avatar', 'sbsr_set_avatar');

function sbsr_set_xprofile_fields() {
    SBSR_User::check_ajax_user();

    $safe_user_id = intval( $_POST['userID'] );

	if ( !$safe_user_id ) {
		wp_die();
	}

    $data = $_POST['data'];


    $data = stripslashes($data);
    $data = json_decode($data);

    $data_arr = array();
	

    foreach ($data as $field) {
		
		if(strpos($field->name,'[]')){
			$field->name=str_replace('[]','',$field->name);
			$data_arr[$field->name][] = $field->value;
		}else{
			$data_arr[$field->name] = $field->value;
		}
    }
	
    $user = SBSR_User::getInstance();
    $result = $user->set_xprofile_fields($safe_user_id, $data_arr);

    echo json_encode($result);
    wp_die();
}
add_action('wp_ajax_sbsr_set_xprofile_fields', 'sbsr_set_xprofile_fields');
add_action('wp_ajax_nopriv_sbsr_set_xprofile_fields', 'sbsr_set_xprofile_fields');

function sbsr_set_groups() {
    SBSR_User::check_ajax_user();
    $user_id = intval($_POST['userID']);

	if (!$user_id) {
		wp_die();
	}

    $data = $_POST['data'];
    $data = stripslashes($data);
    $data = json_decode($data);

    $data_arr = array();

    foreach ($data as $field) {
        $data_arr[$field->name] = $field->value;
    }

    $user = SBSR_User::getInstance();
    $result = $user->set_groups($user_id, $data_arr);

    echo json_encode($result);
    wp_die();
}
add_action('wp_ajax_sbsr_set_groups', 'sbsr_set_groups');
add_action('wp_ajax_nopriv_sbsr_set_groups', 'sbsr_set_groups');

function sbsr_login_user() {
    $user_id = intval($_POST['userID']);

	if (!$user_id) {
		wp_die();
	}
    
    wp_set_auth_cookie($user_id); 
    
    bp_core_add_message( __( 'Your account is now active!', 'buddypress' ) );

    $redirect_to = bp_is_component_front_page( 'activate' )
        ? bp_get_members_directory_permalink()
        : bp_get_root_domain();

    $redirect_to = trailingslashit( $redirect_to );

    $redirect_to = apply_filters( 'bp_loggedin_activate_page_redirect_to', $redirect_to );

    echo json_encode($redirect_to);
    wp_die();
}
add_action('wp_ajax_sbsr_login_user', 'sbsr_login_user');
add_action('wp_ajax_nopriv_sbsr_login_user', 'sbsr_login_user');

function sbsr_check_user_name() {
    $result = get_user_by('login', sanitize_user($_POST['user_name']));
    /* if $result == false then login_name is free */
    if ($result == false) {
        echo json_encode(true);
    } else {
        echo json_encode(false);
    }
    wp_die();
}
add_action('wp_ajax_sbsr_check_user_name', 'sbsr_check_user_name');
add_action('wp_ajax_nopriv_sbsr_check_user_name', 'sbsr_check_user_name');
