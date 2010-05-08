<?php
function cd_pb_admin() {
	global $bp, $wpdb;
	$cd_pb = get_option( 'cd_pb' );
	$all_fileds_ids = $wpdb->get_results( $wpdb->prepare( "SELECT id, name FROM {$bp->profile->table_name_fields} WHERE parent_id = 0" ) );
	
	if ( isset($_POST['saveFields']) ) {
		$cd_pb = $_POST['cd_pb_display'];
		$cd_pb[ 'pb_color' ] = 'blue';
		if ( $_POST[ 'cd_pb_color' ] == 'green' )
			$cd_pb[ 'pb_color' ] = 'green';
		update_option( 'cd_pb', $cd_pb );
		
		echo "<div id='message' class='updated fade'><p>" . __( 'Fields were saved. Go and check results!', 'cd_pb' ) . "</p></div>";
	}
	
	?>
	<div class="wrap">
		<h2><?php _e( 'CD Avatar Bubble','cd_pb') ?> <sup><?php echo 'v' . CD_PB_VERSION; ?></sup> &rarr; <?php _e( 'Interactive Avatars', 'cd_pb' ) ?></h2>

		<form action="<?php echo site_url() . '/wp-admin/admin.php?page=cd-pb-admin' ?>" id="cd-pb-form" method="post">
			<p>
			<table id="" class="widefat link-group" style="width:60%">
				<thead>
					<tr class="header">
						<td colspan="2"><?php _e( 'Check here all the fields you want to show in the bubble', 'cd_pb' ) ?></td>
					</tr>
				
				</thead>
				<tbody id="the-list">
				<?php foreach ( $all_fileds_ids as $field_obj => $field_id ) { ?>
					<tr>
						<td scope="col" width="10px"><input name="cd_pb_display[<?=$field_id->id?>]" type="checkbox" <?php if ( $cd_pb[ $field_id->id ] ) { ?>checked="checked" <?php } ?>value="<?=$field_id->name?>" />
						<td><?=$field_id->name?></td>
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
			
			<p><?php _e( 'Which color of bubble border do you prefer to use?', 'cd_pb' ) ?></p>
			<p>
			<select name="cd_pb_color" id="cd_pb_color" style="height: auto;">
						<option value="blue"<?php echo( ( 'blue' == $cd_pb[ 'pb_color' ] ) ? ' selected="selected"' : '' ); ?>><?php _e( 'Light Blue', 'cd_pb' ); ?></option>
						<option value="green"<?php echo( ( 'green' == $cd_pb[ 'pb_color' ] ) ? ' selected="selected"' : '' ); ?>><?php _e( 'Dark Green', 'cd_pb' ); ?></option>
					</select>
			</p>
			
			
			<p>
				<input class="button" type="submit" name="saveFields" value="<?php _e( 'Save Selected Fields', 'cd_pb' ) ?>"/>
			</p>
		</form>
	</div>
	<?php
}

?>