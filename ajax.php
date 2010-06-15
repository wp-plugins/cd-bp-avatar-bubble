<?php
$ID = $_GET[ 'ID' ];
if ( is_numeric( $ID ) ) {
	include( '../../../wp-blog-header.php' );
	
	function the_personalinfo( $ID ) {
		global $bp;
		$cd_pb = get_option( 'cd_pb' );
		$i = 1;
		if ( $cd_pb[ 'messages' ] == 'yes' ) { 
			if ( is_user_logged_in() ) {
				$mention = '<strong><a href="'. bp_get_send_public_message_link() . bp_core_get_username( $ID, false, false ) . '" title="'. __( 'Mention this User', 'cd_pb' ) .'">@'. bp_core_get_username( $ID, false, false ) .'</a></strong>';
				$message = '<a href="'. bp_get_send_private_message_link() . bp_core_get_username( $ID, false, false ) .'" title="'. __( 'Send a private message to this user', 'cd_pb' ) .'">'. __( 'Private Message', 'cd_pb' ) .'</a>';
			}else{
				$mention = '<strong><a href="' . $bp->root_domain . '/wp-login.php?redirect_to=' . urlencode( $bp->root_domain ) . '" title="You should be logged in to mention this user">@'. bp_core_get_username( $ID, false, false ) .'</a></strong>';
				$message = '<strong><a href="' . $bp->root_domain . '/wp-login.php?redirect_to=' . urlencode( $bp->root_domain ) . '" title="'. __( 'You should be logged in to send a private message', 'cd_pb' ) .'">'. __( 'Private Message', 'cd_pb' ) .'</a></strong>';
			}
			$output .= '<p class="popupLine">'. $mention .' | '. $message .'</p>';
		}
		
		foreach ( $cd_pb as $field_id => $field_data ) {
			if ( $field_data[ 'name' ] && is_numeric( $field_id ) ) {
				$field_value = xprofile_get_field_data( $field_id, $ID );
				if ( $field_value != null ) {
					if ( $field_data[ 'type' ] == 'multiselectbox' ) $field_value = bp_unserialize_profile_field ( $field_value );
					if ( $field_data[ 'type' ] == 'datebox' && $field_value != null ) $field_value = bp_format_time( bp_unserialize_profile_field ( $field_value), true );
					if ( $i != 1 ) $class = ' style="padding-top:5px;"';
					
					$output .= '<p class="popupLine"'. $class .'><strong>' . $field_data[ 'name' ] . '</strong>: ' . xprofile_filter_link_profile_data( $field_value, $field_data[ 'type' ] ) . '</p>';
				}
				$i++;
			}
		}
		if ( $output == '' )
			$output = __( 'Nothing to display. Check a bit later please.', 'cd_pb' );
		echo '<div id="user_'. $ID .'">' . $output . '</div>';
	}
	
	$access = get_option( 'cd_pb' );
	if ( $access[ 'access' ] == 'admin' && is_site_admin() ) {
		the_personalinfo( $ID );
	}elseif ( $access[ 'access' ] == 'logged_in' && is_user_logged_in() ) {
		the_personalinfo( $ID );
	}elseif ( $access[ 'access' ] == 'all' ) {
		the_personalinfo( $ID );
	}

	
}else{
	echo 'GET OUT OF HERE, HACKER!!!! I LOGGED YOUR DATA AND WILL USE IT AGAINST YOU.';
}
?>