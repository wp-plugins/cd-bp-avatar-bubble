<?php
/*
Plugin Name: CD BuddyPress Avatar Bubble
Plugin URI: http://cosydale.com/plugin-cd-avatar-bubble.html
Description: After moving your mouse pointer on a BuddyPress user avatar you will see a bubble with the defined by admin information about this user.
Version: 0.9.5.1
Author: slaFFik
Author URI: http://cosydale.com/
Site Wide Only: true
*/
define ( 'CD_PB_VERSION', '0.9.5.1' );

register_activation_hook( __FILE__, 'cd_pb_activation' );
register_deactivation_hook( __FILE__, 'cd_pb_deactivation' );
function cd_pb_activation() {
	$cd_pb[ 'color' ] = 'blue';
	$cd_pb[ 'access' ] = 'all';
	$cd_pb[ 'messages' ] = 'yes';
	$cd_pb[ 'friend' ] = 'no';
	add_option( 'cd_pb', $cd_pb, '', 'yes' );
}
function cd_pb_deactivation() { delete_option( 'cd_pb' ); }

/* LOAD LANGUAGES */
function cd_pb_load_textdomain() {
	$locale = apply_filters( 'buddypress_locale', get_locale() );
	$mofile = dirname( __File__ )   . "/langs/$locale.mo";

	if ( file_exists( $mofile ) )
		load_textdomain( 'cd_pb', $mofile );
}
add_action ( 'plugins_loaded', 'cd_pb_load_textdomain', 7 );

require ( WP_PLUGIN_DIR . '/cd-bp-avatar-bubble/cd-pb-admin.php' );
require ( WP_PLUGIN_DIR . '/cd-bp-avatar-bubble/cd-pb-cssjs.php' );


function cd_pb_add_admin_menu() {
	if ( !is_site_admin() )
		return false;
		
	add_submenu_page( 'bp-general-settings', __( 'CD Avatar Bubble', 'cd_pb' ), __( 'CD Avatar Bubble', 'cd_pb' ), 'manage_options', 'cd-pb-admin', 'cd_pb_admin' );

}
add_action( 'admin_menu', 'cd_pb_add_admin_menu' );

/***
* BUBBLE ENGINE 
***/
add_filter( 'bp_core_fetch_avatar', 'rel_filter', 10, 2 );
function rel_filter( $text, $params ) {
	if ( $params['object'] == 'user' ) {
		return preg_replace( '|<img (.+?) />|i', "<img $1 rel='{$params['item_id']}' />", $text );
	}else{
		return $text;
	}
}
	function cd_pb_get_add_friend_button( $ID = false, $friend_status = false ) {
		global $bp, $friends_template;

		if ( !is_user_logged_in() )
			return false;

		if ( !$ID && $friends_template->friendship->friend )
			$ID = $friends_template->friendship->friend->id;
		else if ( !$ID && !$friends_template->friendship->friend )
			$ID = $bp->displayed_user->id;

		if ( $bp->loggedin_user->id == $ID )
			return false;

		if ( empty( $friend_status ) )
			$friend_status = friends_check_friendship_status( $bp->loggedin_user->id, $ID );

		if ( 'pending' == $friend_status ) {
			$button .= '<a class="requested" href="' . $bp->loggedin_user->domain . $bp->friends->slug . '/">' . __( 'Friendship Requested', 'buddypress' ) . '</a>';
		} else if ( 'is_friend' == $friend_status ) {
			$button .= '<a href="' . wp_nonce_url( $bp->loggedin_user->domain . $bp->friends->slug . '/remove-friend/' . $ID . '/', 'friends_remove_friend' ) . '" title="' . __('Cancel Friendship', 'buddypress') . '" id="friend-' . $ID . '" rel="remove" class="remove">' . __('Cancel Friendship', 'buddypress') . '</a>';
		} else {
			$button .= '<a href="' . wp_nonce_url( $bp->loggedin_user->domain . $bp->friends->slug . '/add-friend/' . $ID . '/', 'friends_add_friend' ) . '" title="' . __('Add Friend', 'buddypress') . '" id="friend-' . $ID . '" rel="add" class="add">' . __('Add Friend', 'buddypress') . '</a>';
		}

		return apply_filters( 'cd_pb_get_add_friend_button', $button );
	}
/* DISPLAY EVERYTHING */
function the_personalinfo( $ID ) {
	global $bp;
	$cd_pb = get_option( 'cd_pb' );
	$i = 1;

	if ( $cd_pb[ 'messages' ] == 'yes' ) {
		$i++;
		if ( is_user_logged_in() ) {
			$mention = '<strong><a href="'. bp_get_send_public_message_link() . bp_core_get_username( $ID, false, false ) . '" title="'. __( 'Mention this User', 'cd_pb' ) .'">@'. bp_core_get_username( $ID, false, false ) .'</a></strong>';
			$message = '<a href="'. bp_get_send_private_message_link() . bp_core_get_username( $ID, false, false ) .'" title="'. __( 'Send a private message to this user', 'cd_pb' ) .'">'. __( 'Private Message', 'cd_pb' ) .'</a>';
		}else{
			$mention = '<strong><a href="' . $bp->root_domain . '/wp-login.php?redirect_to=' . urlencode( $bp->root_domain ) . '" title="You should be logged in to mention this user">@'. bp_core_get_username( $ID, false, false ) .'</a></strong>';
			$message = '<strong><a href="' . $bp->root_domain . '/wp-login.php?redirect_to=' . urlencode( $bp->root_domain ) . '" title="'. __( 'You should be logged in to send a private message', 'cd_pb' ) .'">'. __( 'Private Message', 'cd_pb' ) .'</a></strong>';
		}
		$output .= '<p class="popupLine">'. $mention .' | '. $message .'</p>';
	}

	if ( $cd_pb[ 'friend' ] == 'yes' && $ID != $bp->loggedin_user->id && is_user_logged_in() ) {
		$i++;
		if ( $i != 1 ) $class = ' style="padding-top:6px;"';
		$output .= '<p class="popupLine"'. $class .'>'. cd_pb_get_add_friend_button( $ID, false) .'</p>';
	}
	
	foreach ( $cd_pb as $field_id => $field_data ) {
		if ( $field_data[ 'name' ] && is_numeric( $field_id ) ) {
			$field_value = xprofile_get_field_data( $field_id, $ID );
			if ( $field_value != null ) {
				if ( $field_data[ 'type' ] == 'multiselectbox' || $field_data[ 'type' ] == 'checkbox' ) $field_value = bp_unserialize_profile_field ( $field_value );
				if ( $field_data[ 'type' ] == 'datebox' && $field_value != null ) $field_value = bp_format_time( bp_unserialize_profile_field ( $field_value), true );
				if ( $i != 1 ) $class = ' style="padding-top:6px;"';
				if ( $field_data[ 'link' ] == 'yes' ) {
					$field_link = xprofile_filter_link_profile_data( $field_value, $field_data[ 'type' ] );
				}else{
					$field_link = $field_value;
				}

				$output .= '<p class="popupLine"'. $class .'><strong>' . $field_data[ 'name' ] . '</strong>: ' . $field_link . '</p>';
			}
			$i++;
		}
	}
	if ( $output == '' )
		$output = __( 'Nothing to display. Check a bit later please.', 'cd_pb' );
	echo '<div id="user_'. $ID .'">' . $output . '</div>';
}

?>