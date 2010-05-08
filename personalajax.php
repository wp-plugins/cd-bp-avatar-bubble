<?php
$ID = $_GET[ 'ID' ];
if ( is_numeric( $ID ) ) {
	include( '../../../wp-blog-header.php' );
	
	function the_personalinfo( $ID ) {
		global $bp;
		$cd_pb = get_option( 'cd_pb' );
		$all = count($cd_pb);
		$i = 1;
		foreach ( $cd_pb as $field_id => $field_name ) {
			if ( xprofile_get_field_data( $field_id, $ID ) ) {
				if ( $i != 1 ) $class = ' style="padding-top:5px;"';
				$output .= '<p class="popupLine"'. $class .'><strong>' . $field_name . '</strong>: ' . xprofile_get_field_data( $field_id, $ID ) .'</p>';
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