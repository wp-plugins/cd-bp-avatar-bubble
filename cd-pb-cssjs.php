<?php
function cd_pb_add_js() {
	wp_enqueue_script( 'CD_PB_JS', '/wp-content/plugins/cd-bp-avatar-bubble/bubble.js', array( 'jquery' ) );
}
add_action( 'wp_print_scripts', 'cd_pb_add_js' );

function cd_pb_add_css() {
	$color = get_option( 'cd_pb' );
	if ( $color[ 'pb_color' ] == 'blue' ) {
		$bubbleUrl = WP_PLUGIN_URL . '/cd-bp-avatar-bubble/bubble-blue.css';
		$bubbleFile = WP_PLUGIN_DIR . '/cd-bp-avatar-bubble/bubble-blue.css';
	}else{
		$bubbleUrl = WP_PLUGIN_URL . '/cd-bp-avatar-bubble/bubble-green.css';
		$bubbleFile = WP_PLUGIN_DIR . '/cd-bp-avatar-bubble/bubble-green.css';
	}
	if ( file_exists($bubbleFile) ) {
		wp_register_style('bubbleSheets', $bubbleUrl);
		wp_enqueue_style( 'bubbleSheets');
	}
}
add_action( 'wp_print_styles', 'cd_pb_add_css' );
?>