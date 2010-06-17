<?php
function cd_pb_admin() {
	global $bp, $wpdb;
	$cd_pb = get_option( 'cd_pb' );
	$all_fileds_ids = $wpdb->get_results( $wpdb->prepare( "SELECT id, name, type FROM {$bp->profile->table_name_fields} WHERE parent_id = 0 ORDER BY field_order" ) );
	
	if ( isset($_POST['saveFields']) ) {
		$cd_pb = $_POST['cd_pb_display'];
			$cd_pb[ 'color' ] = 'blue';
		$cd_pb[ 'color' ] = $_POST[ 'cd_pb_color' ];
		$cd_pb[ 'access' ] = $_POST[ 'cd_pb_access' ];
		$cd_pb[ 'messages' ] = $_POST[ 'cd_pb_messages' ];
			update_option( 'cd_pb', $cd_pb );
		
		echo "<div id='message' class='updated fade'><p>" . __( 'All changes were saved. Go and check results!', 'cd_pb' ) . "</p></div>";
	}
	
	?>
	<?php //print_r( $cd_pb );?>
	<div class="wrap">
		<h2><?php _e( 'CD Avatar Bubble','cd_pb') ?> <sup><?php echo 'v' . CD_PB_VERSION; ?></sup> &rarr; <?php _e( 'Interactive Avatars', 'cd_pb' ) ?></h2>
		<form action="<?php echo site_url() . '/wp-admin/admin.php?page=cd-pb-admin' ?>" id="cd-pb-form" method="post">
			<p>
			<table id="" class="widefat link-group" style="width:60%">
				<thead>
					<tr class="header">
						<td colspan="2"><?php _e( 'Check here all fields you want to show in a bubble', 'cd_pb' ) ?></td>
					</tr>
				
				</thead>
				<tbody id="the-list">
				<?php foreach ( $all_fileds_ids as $field_obj => $field_data ) { ?>
					<tr>

						<td scope="col" width="10px">
							<input name="cd_pb_display[<?php echo $field_data->id?>][name]" type="checkbox" <?php if ( $cd_pb[ $field_data->id ]['name'] ) { ?>checked="checked" <?php } ?>value="<?php echo $field_data->name?>" />
						</td>
						<td><?php $field_data->name?></td>
						<input name="cd_pb_display[<?php echo $field_data->id?>][type]" type="hidden" value="<?php echo $field_data->type?>" />
					</tr>
				<?php } ?>
				</tbody>
				<tfoot>
					<tr class="header">
						<td colspan="2"><?php _e( 'Remember: the more you choose, the more time needed to process the request.', 'cd_pb' ) ?></td>
					</tr>
				</tfoot>
			</table>
			</p>
			
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="cd_bp_access"><?php _e( 'Whom you would like allow to see those avatar bubble?', 'cd_pb' ) ?></label></th>
					<td>
						<input name="cd_pb_access" type="radio" value="admin"<?php echo( 'admin' == $cd_pb[ 'access' ] ? ' checked="checked"' : '' ); ?> /> <?php _e( 'Admin users only', 'cd_pb' ) ?><br>
						<input name="cd_pb_access" type="radio" value="logged_in"<?php echo( 'logged_in' == $cd_pb[ 'access' ] ? ' checked="checked"' : '' ); ?> /> <?php _e( 'Logged in users only (including admins)', 'cd_pb' ) ?><br>
						<input name="cd_pb_access" type="radio" value="all"<?php echo( 'all' == $cd_pb[ 'access' ] ? ' checked="checked"' : '' ); ?> /> <?php _e( 'All visitors (even not logged in)', 'cd_pb' ) ?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'Which color of bubble border do you prefer to use?', 'cd_pb' ) ?></th>
					<td>
						<input name="cd_pb_color" type="radio" value="blue"<?php echo( ( 'blue' == $cd_pb[ 'color' ] ) ? ' checked="checked"' : '' ); ?> /> <?php _e( 'Light Blue', 'cd_pb' ); ?><br>
						<input name="cd_pb_color" type="radio" value="green"<?php echo( ( 'green' == $cd_pb[ 'color' ] ) ? ' checked="checked"' : '' ); ?> /> <?php _e( 'Dark Green', 'cd_pb' ); ?><br>
						<input name="cd_pb_color" type="radio" value="red"<?php echo( ( 'red' == $cd_pb[ 'color' ] ) ? ' checked="checked"' : '' ); ?> /> <?php _e( 'Light Red', 'cd_pb' ); ?><br>
						<input name="cd_pb_color" type="radio" value="black"<?php echo( ( 'black' == $cd_pb[ 'color' ] ) ? ' checked="checked"' : '' ); ?> /> <?php _e( 'Just Black', 'cd_pb' ); ?><br>
						<input name="cd_pb_color" type="radio" value="grey"<?php echo( ( 'grey' == $cd_pb[ 'color' ] ) ? ' checked="checked"' : '' ); ?> /> <?php _e( 'Simply Grey', 'cd_pb' ); ?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'Do you want to display Mention and Private message links in a bubble?', 'cd_pb' ) ?></th>
					<td>
						<input name="cd_pb_messages" type="radio" value="yes"<?php echo( ( 'yes' == $cd_pb[ 'messages' ] ) ? ' checked="checked"' : '' ); ?> /> <?php _e( 'Yes', 'cd_pb' ); ?><br>
						<input name="cd_pb_messages" type="radio" value="no"<?php echo( ( 'no' == $cd_pb[ 'messages' ] ) ? ' checked="checked"' : '' ); ?> /> <?php _e( 'No', 'cd_pb' ); ?><br>
					</td>
				</tr>
			</table>
			
			<p>
				<input class="button" type="submit" name="saveFields" value="<?php _e( 'Save Selected Fields', 'cd_pb' ) ?>"/>
			</p>
		</form>
	</div>
	<?php
}

?>