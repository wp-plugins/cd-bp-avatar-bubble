<?php
$ID = $_GET[ 'ID' ];
if ( is_numeric( $ID ) ) {
	include( '../../../wp-blog-header.php' );
	
	function the_personalinfo( $ID ) {
		$cd_pb = get_option( 'cd_pb' );
		$i = 1;
		foreach ( $cd_pb as $field_id => $field_data ) {
			if ( $field_data[ 'name' ] && is_numeric( $field_id ) ) {
				$field_value = xprofile_get_field_data( $field_id, $ID );
				if ( $field_value != null ) {
					if ( $field_data[ 'type' ] == 'multiselectbox' ) $field_value = bp_unserialize_profile_field ( $field_value );
					if ( $field_data[ 'type' ] == 'datebox' && $field_value != null ) $field_value = bp_format_time( bp_unserialize_profile_field ( $field_value), true );
					if ( $i != 1 ) $class = ' style="padding-top:5px;"';
					
					$output .= '<p class="popupLine"'. $class .'><strong>' . $field_data[ 'name' ] . '</strong>: ' . $field_value .'</p>';
				}
				$i++;
			}
		}
		if ( $output == '' )
			$output = __( 'This user didn\'t enter required personal information.', 'cd_pb' );
		echo '<div id="user_'. $ID .'">' . $output . '</div>';
	}
	
	the_personalinfo( $ID );

}else{
	echo 'GET OUT OF HERE, HACKER!!!! I LOGGED YOUR DATA AND WILL USE IT AGAINST YOU.';
}
?>