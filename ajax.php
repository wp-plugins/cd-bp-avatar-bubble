<?php
$ID = $_GET[ 'ID' ];
if ( is_numeric( $ID ) ) {
	include '../../../wp-load.php';

	$cd_ab = get_option( 'cd_ab' );
	if ( $cd_ab[ 'access' ] == 'admin' && is_site_admin() ) {
		the_personalinfo( $ID );
	}elseif ( $cd_ab[ 'access' ] == 'logged_in' && is_user_logged_in() ) {
		the_personalinfo( $ID );
	}elseif ( $cd_ab[ 'access' ] == 'all' ) {
		the_personalinfo( $ID );
	}

}else{
	echo 'GET OUT OF HERE, HACKER!!!! I LOGGED YOUR DATA AND WILL USE IT AGAINST YOU.';
}
?>