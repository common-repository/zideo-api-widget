<?php
date_default_timezone_set('Europe/Paris');	
error_reporting(E_ALL);
ini_set("display_errors", true);

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

//define("CHANNEL_PAGE", "6c494f566d673d3d");
	
define("URL_ZIDEO_PAGE", "http://www.zideo.nl");
if (!array_key_exists('zideo', $_GET) && !array_key_exists('playzideo', $_GET))
{
	$feedURL = URL_ZIDEO_PAGE . '/gadgets/api/api.php?channel=' . $zideo_channel_id . '&orderby=date&limit=1';
	$sxml = simplexml_load_file($feedURL);
	$zideo = $sxml->entry->zideo;
	$playzideo = $sxml->entry->playzideo;
	echo '<div id="zideoPageCont">';
		echo '<iframe id="zideoPlayerPage" marginwidth="0" marginheight="0" src="' . URL_ZIDEO_PAGE . '/zideomediaplayer.php?zideo=' . $zideo . '&playzideo=' . $playzideo . '" width="' . $zideo_p_player_width . '" frameborder="0" height="' . $zideo_p_player_height . '"></iframe>';
}
elseif(array_key_exists('zideo', $_GET) && array_key_exists('playzideo', $_GET))
{
	$zideo = $_GET['zideo'];
	$playzideo = $_GET['playzideo'];
	echo '<div id="zideoWidgetContPage">';
		echo '<iframe id="zideoPlayerPage" marginwidth="0" marginheight="0" src="' . URL_ZIDEO_PAGE . '/zideomediaplayer.php?zideo=' . $zideo . '&playzideo=' . $playzideo . '" width="' . $zideo_p_player_width . '" frameborder="0" height="' . $zideo_p_player_height . '"></iframe>';
}		
		echo '<a href="' . $zideo_p_link_url_1 . '">'. $zideo_p_link_name_1 . '</a>';
		$feedURL2 = URL_ZIDEO_PAGE . '/gadgets/api/api.php?channel=' . $zideo_channel_id . '&orderby=date&limit=' . $zideo_p_related_num;
		$sxml2 = simplexml_load_file($feedURL2);
		echo '<div id="relatedMoviesPage">';
			foreach ($sxml2->entry as $entry)
			{
				echo '<a href="/wordpress/flearths/video-flearths/?zideo=' . $entry->zideo . '&playzideo=' . $entry->playzideo . '" class="thumbRelated"><img src="' . $entry->thumbnail . '" alt="" width="68" height="50" /></a>';
			}
			echo '</div>';
	echo '<a href="' . $zideo_p_link_url_2 . '">'. $zideo_p_link_name_2 . '</a>';
	echo '</div>';
?>