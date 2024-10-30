<?php

add_action( 'wsl_hook_process_login_before_wp_set_auth_cookie', function( $user_id, $provider, $hybridauth_user_profile ){
    // $user_id is the ID of the WP User Object or in other words the users ID on your site.
    // $provider is the provider in which you are working with
    // $hybridauth_user_profile is a instance of the social media profile information: IE: $hybridauth_user_profile->email. This variable stores all profile information for the adapter, such as facebook.


    include_once( WORDPRESS_SOCIAL_LOGIN_ABS_PATH . '/hybridauth/Hybrid/Auth.php' );

    try
    {
		
		if(!$provider) 
			return;

		global $wpdb;
		
		$_SESSION['f_user']=$hybridauth_user_profile;
		$email = $hybridauth_user_profile->email;
		$salt = wp_generate_password(20); // 20 character "random" string
		$key = sha1($salt . $email . uniqid(time(), true));
		update_user_meta($user_id,'activation_key',$key);
		//update_user_meta($user_id,'first_name',$email);
		$wpdb->query("update {$wpdb->prefix}users set user_status=2 where ID=$user_id");
		//$wpdb->query("delete from {$wpdb->prefix}usermeta where (meta_key='wp_user_level' or meta_key='wp_capabilities') and user_id=$user_id");
		$zap="insert into {$wpdb->prefix}signups(user_login,user_email,registered,activation_key) VALUES('$email','$email','".date('Y-m-d H:i:s')."','$key')";
		$wpdb->query("update {$wpdb->prefix}users set user_login='$email' where ID=$user_id");
		//var_dump($zap);die();
		$wpdb->query($zap);
		$main_pages=get_option('bp-pages');
		
		$url=get_permalink($main_pages['activate']).$key.'/';
		//var_dump($url);die();
		//var_dump($hybridauth_user_profile);die();
		wp_redirect($url);
		die();
		//Requests::post( 'https://seo.sovabarmak.in.ua/activate/aaa/', $headers, $data);
		
    }
    catch( Exception $e )
    {
        // Do some logging or something, in this example we echo the error.
        echo "Ooophs, we got an error: " . $e->getMessage();
    }
}, 10, 3 );

?>