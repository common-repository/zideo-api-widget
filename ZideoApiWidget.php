<?php
/*
Plugin Name: ZideoApiWidget
Plugin URI: 
Description: ZideoApiWidget, enables a widget plugin for the i-frame from zideo, with a latest 4 video's beneath.
Author: Steven Laugs
Version: 0.1
Author URI: http://www.zideo.nl/index.php?option=com_zideoprofile&profile_cid=6c5953556e46773d
*/

function zideo_admin() {  
	include('ZideoApiWidgetAdmin.php');  
}  
  
function zideo_admin_actions() {  
	add_options_page("Zideo Video Admin", "Zideo Video Admin", 1, "Zideo Video Admin", "zideo_admin");  
}  

add_action('admin_menu', 'zideo_admin_actions'); 

/**
 * Returns an URL of the current page.
 * @return string
 */
function currentPage()
{
	$pageURL = 'http';
 	if (array_key_exists('HTTPS', $_SERVER) && $_SERVER["HTTPS"] == "on") 
 	{
 		$pageURL .= "s";
 	}
 	
 	$pageURL .= "://";
	
 	if ($_SERVER["SERVER_PORT"] != "80")
	{
	 	$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	}
	else
	{
	  	$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	$pageURL = substr($pageURL, 0, strpos($pageURL, "?zideo"));
	return $pageURL;
}

function ZideoApiWidget()
{
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
	
	
	//define("CHANNEL", "6c494f566d673d3d");
	
	define("URL_ZIDEO", "http://www.zideo.nl");
	if (!array_key_exists('zideo', $_GET) && !array_key_exists('playzideo', $_GET))
	{
		$feedURL = URL_ZIDEO . '/gadgets/api/api.php?channel=' . $zideo_channel_id . '&orderby=date&limit=1';
		$sxml = simplexml_load_file($feedURL);
		$zideo = $sxml->entry->zideo;
		$playzideo = $sxml->entry->playzideo;
		echo '<div id="zideoWidgetCont">';
			echo '<iframe id="zideoPlayer" marginwidth="0" marginheight="0" src="' . URL_ZIDEO . '/zideomediaplayer.php?zideo=' . $zideo . '&playzideo=' . $playzideo . '" width="' . $zideo_g_player_width . '" frameborder="0" height="' . $zideo_g_player_height . '"></iframe>';
	}
	elseif(array_key_exists('zideo', $_GET) && array_key_exists('playzideo', $_GET))
	{
			$zideo = $_GET['zideo'];
			$playzideo = $_GET['playzideo'];
			echo '<div id="zideoWidgetCont">';
			echo '<iframe id="zideoPlayer" marginwidth="0" marginheight="0" src="' . URL_ZIDEO . '/zideomediaplayer.php?zideo=' . $zideo . '&playzideo=' . $playzideo . '" width="' . $zideo_g_player_width . '" frameborder="0" height="' . $zideo_g_player_height . '"></iframe>';
	}		
		$feedURL2 = URL_ZIDEO . '/gadgets/api/api.php?channel=' . $zideo_channel_id . '&orderby=date&limit=' . $zideo_g_related_num;
		$sxml2 = simplexml_load_file($feedURL2);
		echo '<div id="relatedMovies">';
			foreach ($sxml2->entry as $entry)
			{
				echo '<a href="' . currentPage() . '?zideo=' . $entry->zideo . '&playzideo=' . $entry->playzideo . '" class="thumbRelated"><img src="' . $entry->thumbnail . '" alt="" width="68 height="50" /></a>';
			}
		echo '</div>';
					echo '<a href="' . $zideo_g_link_url_1 . '" class="viewAll">' . $zideo_g_link_name_1 . '</a>';
				echo '</div>';
				echo '<div class="widgetFoot">';
    				echo '<a href="' . $zideo_g_link_url_2 . '">' . $zideo_g_link_name_2 . '</a>';
    			echo '</div>'; 
}

function widget_ZideoApi($args) {
  extract($args);

  $options = get_option("widget_ZideoApi");
  if (!is_array( $options ))
{
$options = array(
      'title' => 'My Widget Title'
      );
  }      

  echo $before_widget;
    echo $before_title;
    	echo "<div class='flearthTitle'>";
      	echo $options['title'];
      	echo "</div>";
    echo $after_title;

    //Our Widget Content
    ZideoApiWidget();
  echo $after_widget;
}

function ZideoApi_control()
{
  $options = get_option("widget_ZideoApi");
  if (!is_array( $options ))
{
$options = array(
      'title' => 'My Widget Title'
      );
  }    

  if (array_key_exists('ZideoApi-Submit', $_POST) && $_POST['ZideoApi-Submit'])
  {
    $options['title'] = htmlspecialchars($_POST['ZideoApi-WidgetTitle']);
    update_option("widget_ZideoApi", $options);
  }

?>
  <p>
    <label for="ZideoApi-WidgetTitle">Widget Title: </label>
    <input type="text" id="ZideoApi-WidgetTitle" name="ZideoApi-WidgetTitle" value="<?php echo $options['title'];?>" />
    <input type="hidden" id="ZideoApi-Submit" name="ZideoApi-Submit" value="1" />
  </p>
<?php
}

function ZideoApi_init()
{
  register_sidebar_widget(__('ZideoApiWidget'), 'widget_ZideoApi');
  register_widget_control(   'ZideoApiWidget', 'ZideoApi_control', 300, 200 );    
}
add_action("plugins_loaded", "ZideoApi_init");
?>