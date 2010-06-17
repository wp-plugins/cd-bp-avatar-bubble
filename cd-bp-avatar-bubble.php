<?php
/*
Plugin Name: CD BuddyPress Avatar Bubble
Plugin URI: http://cosydale.com/plugin-cd-avatar-bubble.html
Description: After moving your mouse pointer on a BuddyPress user avatar you will see a bubble with the defined by admin information about this user.
Version: 0.9.1
Author: slaFFik
Author URI: http://cosydale.com/
Site Wide Only: true
*/
define ( 'CD_PB_VERSION', '0.9.1' );

register_activation_hook( __FILE__, 'cd_pb_activation' );
function cd_pb_activation() {
	$cd_pb[ 'color' ] = 'blue';
	$cd_pb[ 'access' ] = 'all';
	$cd_pb[ 'messages' ] = 'yes';
	add_option( 'cd_pb', $cd_pb, '', 'yes' );
}

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


add_filter( 'bp_core_fetch_avatar', 'rel_filter', 10, 2 );
function rel_filter( $text, $params ) {
	if ( $params['object'] == 'user' ) {
		return preg_replace( '|<img (.+?) />|i', "<img $1 rel='{$params['item_id']}' />", $text );
	}else{
		return $text;
	}
}

register_deactivation_hook( __FILE__, 'cd_pb_deactivation' );
function cd_pb_deactivation() {
	delete_option( 'cd_pb' );
}
?>