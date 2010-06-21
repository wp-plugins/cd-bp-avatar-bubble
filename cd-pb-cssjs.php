<?php
function cd_pb_add_js() {
	wp_enqueue_script( 'CD_PB_JS', WP_PLUGIN_URL . '/cd-bp-avatar-bubble/_inc/bubble.js', array( 'jquery' ) );
}
add_action( 'wp_print_scripts', 'cd_pb_add_js' );

function cd_pb_add_css() {
	$color = get_option( 'cd_pb' );
	if ( $color[ 'color' ] == 'blue' ) {
		$bubbleUrl = WP_PLUGIN_URL . '/cd-bp-avatar-bubble/_inc/bubble-blue.css';
		$bubbleFile = WP_PLUGIN_DIR . '/cd-bp-avatar-bubble/_inc/bubble-blue.css';
	}elseif ( $color[ 'color' ] == 'red' ) {
		$bubbleUrl = WP_PLUGIN_URL . '/cd-bp-avatar-bubble/_inc/bubble-red.css';
		$bubbleFile = WP_PLUGIN_DIR . '/cd-bp-avatar-bubble/_inc/bubble-red.css';
	}elseif ( $color[ 'color' ] == 'black' ) {
		$bubbleUrl = WP_PLUGIN_URL . '/cd-bp-avatar-bubble/_inc/bubble-black.css';
		$bubbleFile = WP_PLUGIN_DIR . '/cd-bp-avatar-bubble/_inc/bubble-black.css';
	}elseif ( $color[ 'color' ] == 'grey' ) {
		$bubbleUrl = WP_PLUGIN_URL . '/cd-bp-avatar-bubble/_inc/bubble-grey.css';
		$bubbleFile = WP_PLUGIN_DIR . '/cd-bp-avatar-bubble/_inc/bubble-grey.css';
	}else{
		$bubbleUrl = WP_PLUGIN_URL . '/cd-bp-avatar-bubble/_inc/bubble-green.css';
		$bubbleFile = WP_PLUGIN_DIR . '/cd-bp-avatar-bubble/_inc/bubble-green.css';
	}
	
	if ( file_exists($bubbleFile) ) {
		wp_register_style('bubbleSheets', $bubbleUrl);
		wp_enqueue_style( 'bubbleSheets');
	}
}
add_action( 'wp_print_styles', 'cd_pb_add_css' );
?>