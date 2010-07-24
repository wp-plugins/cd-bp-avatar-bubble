<?php
function cd_ab_add_js() {
	$cd_ab = get_option( 'cd_ab' );
	if ( $cd_ab[ 'action' ] == 'click' ) {
		wp_enqueue_script( 'CD_AB_JS', WP_PLUGIN_URL . '/cd-bp-avatar-bubble/_inc/bubble-click.js', array( 'jquery' ) );
	}else{
		wp_enqueue_script( 'CD_AB_JS', WP_PLUGIN_URL . '/cd-bp-avatar-bubble/_inc/bubble-hover.js', array( 'jquery' ) );
	}
}
add_action( 'wp_print_scripts', 'cd_ab_add_js' );

function cd_ab_add_css() {
	$cd_ab = get_option( 'cd_ab' );
	if ( $cd_ab[ 'color' ] == 'blue' ) {
		$bubbleUrl = WP_PLUGIN_URL . '/cd-bp-avatar-bubble/_inc/css/bubble-blue.css';
		$bubbleFile = WP_PLUGIN_DIR . '/cd-bp-avatar-bubble/_inc/css/bubble-blue.css';
	}elseif ( $cd_ab[ 'color' ] == 'red' ) {
		$bubbleUrl = WP_PLUGIN_URL . '/cd-bp-avatar-bubble/_inc/css/bubble-red.css';
		$bubbleFile = WP_PLUGIN_DIR . '/cd-bp-avatar-bubble/_inc/css/bubble-red.css';
	}elseif ( $cd_ab[ 'color' ] == 'black' ) {
		$bubbleUrl = WP_PLUGIN_URL . '/cd-bp-avatar-bubble/_inc/css/bubble-black.css';
		$bubbleFile = WP_PLUGIN_DIR . '/cd-bp-avatar-bubble/_inc/css/bubble-black.css';
	}elseif ( $cd_ab[ 'color' ] == 'grey' ) {
		$bubbleUrl = WP_PLUGIN_URL . '/cd-bp-avatar-bubble/_inc/css/bubble-grey.css';
		$bubbleFile = WP_PLUGIN_DIR . '/cd-bp-avatar-bubble/_inc/css/bubble-grey.css';
	}else{
		$bubbleUrl = WP_PLUGIN_URL . '/cd-bp-avatar-bubble/_inc/css/bubble-green.css';
		$bubbleFile = WP_PLUGIN_DIR . '/cd-bp-avatar-bubble/_inc/css/bubble-green.css';
	}
	
	if ( file_exists($bubbleFile) ) {
		wp_register_style('bubbleSheets', $bubbleUrl);
		wp_enqueue_style( 'bubbleSheets');
	}
}
add_action( 'wp_print_styles', 'cd_ab_add_css' );
?>