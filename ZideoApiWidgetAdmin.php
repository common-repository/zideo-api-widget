<?php
/**
 * Makes sure there are records in the database to correnspond with the ZideoApiWidget
 * If not they are created, if they exist this function moves on
 * @param 
 * @return mixed
 */		
function check_for_zideo_widget()
{
	$query = "SELECT option_name FROM wp_options WHERE option_name = 'zideo_check'";
	$result = mysql_query($query) or die ("Error in query: $query. ".mysql_error());
	$row = mysql_fetch_assoc($result);

	if (! $row)
	{
		$query = "INSERT INTO wp_options (option_name, option_value) VALUES ('zideo_check', 'yes'),('zideo_channel_id', '6d596554'),('zideo_g_player_width', '640'),('zideo_g_player_height', '480'),('zideo_g_related_num', '4'),('zideo_p_player_width', '640'),('zideo_p_player_height', '480'),('zideo_p_related_num', '4'),('zideo_g_link_name_1', ''),('zideo_g_link_url_1', ''),('zideo_g_link_name_2', ''),('zideo_g_link_url_2', ''),('zideo_p_link_name_1', ''),('zideo_p_link_url_1', ''),('zideo_p_link_name_2', ''),('zideo_p_link_url_2', '')";		
		$result = mysql_query($query) or die ("Error in query: $query. ".mysql_error());
	}
}

function get_siteUrl()
{
	$query = "SELECT option_name, option_value FROM wp_options WHERE option_name = 'siteurl'";
	$result = mysql_query($query) or die ("Error in query: $query. ".mysql_error());
	$row = mysql_fetch_assoc($result);
	if ($row)
	{
		$siteUrl= $row['option_value'];
		return $siteUrl;
	}
}


check_for_zideo_widget();

if (array_key_exists('submit', $_POST))
{
	$field_array = array('zideo_channel_id',
					'zideo_g_player_width',
					'zideo_g_player_height',
					'zideo_g_related_num',
					'zideo_g_link_name_1',
					'zideo_g_link_url_1',
					'zideo_g_link_name_2',
					'zideo_g_link_url_2',
					'zideo_p_player_width',
					'zideo_p_player_height',
					'zideo_p_related_num',
					'zideo_p_link_name_1',
					'zideo_p_link_url_1',
					'zideo_p_link_name_2',
					'zideo_p_link_url_1');
	
	foreach($field_array as $field)
	{
		$var = $_POST[$field];
		$querySubmit = "UPDATE wp_options SET option_value = '" . $var . "' WHERE wp_options.option_name = '$field'";
		$resultSubmit = mysql_query($querySubmit) or die ("Error in query: $querySubmit. ".mysql_error());
		//$message = 'Channel ID succesfully updated';
		//echo $message;
	}
	$search_in = "'zideo_channel_id',
					'zideo_g_player_width',
					'zideo_g_player_height',
					'zideo_g_related_num',
					'zideo_g_link_name_1',
					'zideo_g_link_url_1',
					'zideo_g_link_name_2',
					'zideo_g_link_url_2',
					'zideo_p_player_width',
					'zideo_p_player_height',
					'zideo_p_related_num',
					'zideo_p_link_name_1',
					'zideo_p_link_url_1',
					'zideo_p_link_name_2',
					'zideo_p_link_url_2'";
	$query = "SELECT option_name, option_value FROM wp_options WHERE option_name IN ($search_in)";
	$result = mysql_query($query) or die ("Error in query: $query. ".mysql_error());
	while($row = mysql_fetch_assoc($result))
	{
		$$row['option_name'] = $row['option_value'];
	}
}
elseif(!array_key_exists('submit', $_POST))
{
	$search_in = "'zideo_channel_id',
					'zideo_g_player_width',
					'zideo_g_player_height',
					'zideo_g_related_num',
					'zideo_g_link_name_1',
					'zideo_g_link_url_1',
					'zideo_g_link_name_2',
					'zideo_g_link_url_2',
					'zideo_p_player_width',
					'zideo_p_player_height',
					'zideo_p_related_num',
					'zideo_p_link_name_1',
					'zideo_p_link_url_1',
					'zideo_p_link_name_2',
					'zideo_p_link_url_2'";
	$query = "SELECT option_name, option_value FROM wp_options WHERE option_name IN ($search_in)";
	$result = mysql_query($query) or die ("Error in query: $query. ".mysql_error());
	while($row = mysql_fetch_assoc($result))
	{
		$$row['option_name'] = $row['option_value'];
	}
}
?>

<div class="wrap">
	<?php    echo "<h2><img src='" . get_siteUrl() . "/wp-content/plugins/zideo-api-widget/images/logo.jpg' alt='Zideo Logo' width='35px' align='left' />&nbsp;" . __( 'Zideo Video Admin', 'zideo_trdom' ) . "</h2>"; ?>
	
	<form name="zideo_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<?php    echo "<h3>" . __( 'Zideo Video General Settings', 'zideo_trdom' ) . "</h3>"; ?>
		<p>Channel ID: <input type="text" name="zideo_channel_id" value="<?php echo $zideo_channel_id;?>" size="20"><?php _e(" ex: <i>http://www.zideo.nl/index.php?option=com_channel&channel=</i> <strong>6d596554</strong> (default)" ); ?></p>
		<hr/>
		<?php    echo "<h3>" . __( 'Zideo Video Gadget Settings', 'zideo_trdom' ) . "</h3>"; ?>
		<p>Player-width: <input type="text" name="zideo_g_player_width" value="<?php echo $zideo_g_player_width; ?>" size="5"><?php _e(" ex: <strong>640</strong>px (default)" ); ?></p>
		<p>Player-height: <input type="text" name="zideo_g_player_height" value="<?php echo $zideo_g_player_height; ?>" size="5"><?php _e(" ex: <strong>480</strong>px (default)" ); ?></p>
		<p>Number of related movies: <input type="text" name="zideo_g_related_num" value="<?php echo $zideo_g_related_num; ?>" size="2"><?php _e("default = 4, 0 = no limit. Will display given number of small icons below your player with other movies from the specified channel." ); ?></p>
		<p>Link 1  Name: <input type="text" name="zideo_g_link_name_1" value="<?php echo $zideo_g_link_name_1; ?>" size="30"> URL: <input type="text" name="zideo_g_link_url_1" value="<?php echo $zideo_g_link_url_1; ?>" size="70"><?php _e("ex: Name: [Google]  URL: [http://google.com]") ; ?></p>
		<p>Link 2  Name: <input type="text" name="zideo_g_link_name_2" value="<?php echo $zideo_g_link_name_2; ?>" size="30"> URL: <input type="text" name="zideo_g_link_url_2" value="<?php echo $zideo_g_link_url_2; ?>" size="70"><?php _e("ex: Name: [Google]  URL: [http://google.com]") ; ?></p>
		<hr />
		<?php    echo "<h3>" . __( 'Zideo Video Page Settings', 'zideo_trdom' ) . "</h3>"; ?>
		<p>Player-width: <input type="text" name="zideo_p_player_width" value="<?php echo $zideo_p_player_width; ?>" size="5"><?php _e(" ex: <strong>640</strong>px (default)" ); ?></p>
		<p>Player-height: <input type="text" name="zideo_p_player_height" value="<?php echo $zideo_p_player_height; ?>" size="5"><?php _e(" ex: <strong>480</strong>px (default)" ); ?></p>
		<p>Number of related movies: <input type="text" name="zideo_p_related_num" value="<?php echo $zideo_p_related_num; ?>" size="2"><?php _e("default = 4, 0 = no limit. Will display given number of small icons below your player with other movies from the specified channel.") ; ?></p>
		<p>Link 1  Name: <input type="text" name="zideo_p_link_name_1" value="<?php echo $zideo_p_link_name_1; ?>" size="30"> URL: <input type="text" name="zideo_p_link_url_1" value="<?php echo $zideo_p_link_url_1; ?>" size="70"><?php _e("ex: Name: [Google]  URL: [http://google.com]") ; ?></p>
		<p>Link 2  Name: <input type="text" name="zideo_p_link_name_2" value="<?php echo $zideo_p_link_name_2; ?>" size="30"> URL: <input type="text" name="zideo_p_link_url_2" value="<?php echo $zideo_p_link_url_2; ?>" size="70"><?php _e("ex: Name: [Google]  URL: [http://google.com]") ; ?></p>
		<p><?php _e("Paste the following into your page to get the video player on the page:"); ?></p>
		<p><?php _e("[include file=wp-content\plugins\ZideoApiWidget\ZideoApiPage.php]"); ?></p>
		<p><?php _e("<strong>NOTE:</strong> In order to use this feature you will need to have the plugin <a href='http://www.satollo.net/plugins/include-it'>Include It</a> installed"); ?></p>

		<p class="submit">
		<input type="submit" class="button-primary" name="submit" value="<?php _e('Save Changes', 'zideo_trdom' ) ?>" />
		</p>
	</form>
</div>
	