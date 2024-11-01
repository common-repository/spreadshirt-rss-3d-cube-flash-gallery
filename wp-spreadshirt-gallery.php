<?php
/*
	Plugin Name: WP-RSS-Spreadshirt-3DCube-Gallery
	Plugin URI: http://flashapplications.de/?p=370
	Description: Flash based RSS Gallery for Spreadshirt Shops
	Version: 1.3
	Author: Jörg Sontag
	Author URI: http://www.flashapplications.de
	
	Copyright 2010, Jörg Sontag Flashapplications

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// check for WP context
if ( !defined('ABSPATH') ){ die(); }

//initially set the options
function wp_spreadshirt_install () {
	
    $newoptions = get_option('wpspreadshirt_options');
	$newoptions['width'] = '220';
	$newoptions['height'] = '220';
	$newoptions['tcolor'] = 'ffffff';
	$newoptions['tcolor2'] = '333333';
	$newoptions['bgcolor'] = '333333';
	$newoptions['trans'] = 'false';
	$newoptions['rsspath'] = 'RSS Path';
    $newoptions['cubedelay'] = '30';
	$newoptions['jsf'] = 'JS Function';
	add_option('wpspreadshirt_options', $newoptions);
	
	// widget options
	$widgetoptions = get_option('wpspreadshirt_widget');
	$newoptions['width'] = '220';
	$newoptions['height'] = '220';
	$newoptions['tcolor'] = '333333';
	$newoptions['tcolor2'] = '333333';
	$newoptions['bgcolor'] = 'ffffff';
	$newoptions['trans'] = 'false';
	$newoptions['rsspath'] = 'RSS Path';
    $newoptions['cubedelay'] = '30';
	$newoptions['jsf'] = 'JS Function';
	add_option('wpspreadshirt_widget', $newoptions);
}

// add the admin page
function wp_spreadshirt_add_pages() {
	add_options_page('WP Spreadshirt Gallery', 'WP Spreadshirt Gallery', 8, __FILE__, 'wp_spreadshirt_options');
}

// replace tag in content with SpreadShirt (non-shortcode version for WP 2.3.x)
function wp_spreadshirt_init($content){
	if( strpos($content, '[WP-SPREADSHIRT]') === false ){
		return $content;
	} else {
		$code = wp_spreadshirt_createflashcode(false);
		$content = str_replace( '[WP-SPREADSHIRT]', $code, $content );
		return $content;
	}
}

// template function
function wp_spreadshirt_insert( $atts=NULL ){
	echo wp_spreadshirt_createflashcode( false, $atts );
}

// shortcode function
function wp_spreadshirt_shortcode( $atts=NULL ){
	return wp_spreadshirt_createflashcode( false, $atts );
}

// piece together the flash code
function wp_spreadshirt_createflashcode( $widget=false, $atts=NULL ){
	// get the options
	if( $widget == true ){
		$options = get_option('wpspreadshirt_widget');
		$soname = "widget_so";
		$divname = "wpspreadshirtwidgetcontent";
		// get compatibility mode variable from the main options
		$mainoptions = get_option('wpspreadshirt_options');
	} else if( $atts != NULL ){
		$options = shortcode_atts( get_option('wpspreadshirt_options'), $atts );
		$soname = "shortcode_so";
		$divname = "wpspreadshirtcontent";
	} else {
		$options = get_option('wpspreadshirt_options');
		$soname = "so";
		$divname = "wpspreadshirtcontent";
	}

	// get some paths
	if( function_exists('plugins_url') ){ 
		// 2.6 or better
		$movie = plugins_url('spreadshirt-rss-3d-cube-flash-gallery/ShirtIO.swf');
		$path = plugins_url('spreadshirt-rss-3d-cube-flash-gallery/');
	} else {
		// pre 2.6
		$movie = get_bloginfo('wpurl') . "/wp-content/plugins/spreadshirt-rss-3d-cube-flash-gallery/ShirtIO.swf";
		$path = get_bloginfo('wpurl')."/wp-content/plugins/spreadshirt-rss-3d-cube-flash-gallery/";
	}
	// add random seeds to so name and movie url to avoid collisions and force reloading (needed for IE)
	$soname .= rand(0,9999999);
	$movie .= '?r=' . rand(0,9999999);
	$divname .= rand(0,9999999);
	// write flash tag
	if( $options['compmode']!='true' ){
		$flashtag = '<!-- SWFObject embed by Geoff Stearns geoff@deconcept.com http://blog.deconcept.com/swfobject/ -->';	
		$flashtag .= '<script type="text/javascript" src="'.$path.'swfobject.js"></script>';
		$flashtag .= '<div id="'.$divname.'">';
	
		$flashtag .= '</p><p>WP RSS Spreadshirt Gallery by <a href="http://flashapplications.de/">Joerg Sontag Flashapplications</a> requires <a href="http://www.macromedia.com/go/getflashplayer">Flash Player</a> 9 or better.</p></div>';
		$flashtag .= '<script type="text/javascript">';
		$flashtag .= 'var '.$soname.' = new SWFObject("'.$movie.'", "spreadshirt", "220", "220", "9", "#'.$options['bgcolor'].'");';
		if( $options['trans'] == 'true' ){
			$flashtag .= $soname.'.addParam("wmode", "transparent");';
		}
                $flashtag .= $soname.'.addVariable("serverpath", "'.$path.'");';
		$flashtag .= $soname.'.addVariable("color", "0x'.$options['tcolor'].'");';
                $flashtag .= $soname.'.addVariable("delay", "'.$options['cubedelay'].'");';
		$flashtag .= $soname.'.addVariable("tcolor", "0x' . ($options['tcolor2'] == "" ? $options['tcolor'] : $options['tcolor2']) . '");';
		$flashtag .= $soname.'.addVariable("rsspath", "'.$options['rsspath'].'");';
		$flashtag .= $soname.'.addVariable("jsf", "'.$options['jsf'].'");';
		$flashtag .= $soname.'.write("'.$divname.'");';
		$flashtag .= '</script>';
	} else {
		$flashtag = '<object type="application/x-shockwave-flash" data="'.$movie.'" width="220" height="220">';
		$flashtag .= '<param name="movie" value="'.$movie.'" />';
		$flashtag .= '<param name="bgcolor" value="#'.$options['bgcolor'].'" />';
		$flashtag .= '<param name="AllowScriptAccess" value="always" />';
		if( $options['trans'] == 'true' ){
			$flashtag .= '<param name="wmode" value="transparent" />';
		}
		$flashtag .= '<param name="flashvars" value="';
                $flashtag .= 'serverpath='.$path;
                $flashtag .= 'delay='.$delay;
		$flashtag .= 'color=0x'.$options['tcolor'];
		$flashtag .= '&amp;tcolor=0x'.$options['tcolor2'];
		$flashtag .= '&amp;rsspath='.$options['rsspath'];
		$flashtag .= '&amp;jsf='.$options['jsf'];
		$flashtag .= '" />';
		// alternate content
		$flashtag .= '</p><p>WP RSS Spreadshirt Gallery by <a href="http://flashapplications.de/">Joerg Sontag Flashapplications</a> requires <a href="http://www.macromedia.com/go/getflashplayer">Flash Player</a> 9 or better.</p></div>';
		$flashtag .= '</object>';
	}
	return $flashtag;
}

// options page
function wp_spreadshirt_options() {	
	$options = $newoptions = get_option('wpspreadshirt_options');
	// if submitted, process results
	if ( $_POST["wpspreadshirt_submit"] ) {
		//$newoptions['width'] = strip_tags(stripslashes($_POST["width"]));
		//$newoptions['height'] = strip_tags(stripslashes($_POST["height"]));
		$newoptions['tcolor'] = strip_tags(stripslashes($_POST["tcolor"]));
		$newoptions['tcolor2'] = strip_tags(stripslashes($_POST["tcolor2"]));
	    $newoptions['bgcolor'] = strip_tags(stripslashes($_POST["bgcolor"]));
        $newoptions['trans'] = strip_tags(stripslashes($_POST["trans"]));
		$newoptions['rsspath'] = strip_tags(stripslashes($_POST["rsspath"]));
        $newoptions['cubedelay'] = strip_tags(stripslashes($_POST["cubedelay"]));
		$newoptions['jsf'] = strip_tags(stripslashes($_POST["jsf"]));
	}
	// any changes? save!
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('wpspreadshirt_options', $options);
	}
	// options form
	echo '<form method="post">';
	echo "<div class=\"wrap\"><h2>Display options</h2>";
	echo '<table class="form-table">';
	// text color
	echo '<tr valign="top"><th scope="row">Color of the Cube</th>';
	echo '<td><input type="text" name="tcolor" value="'.$options['tcolor'].'" size="8"></input> Text Color: <input type="text" name="tcolor2" value="'.$options['tcolor2'].'" size="8"></input> 
	<br />These should be 6 character hex color values without the # prefix (000000 for black, ffffff for white)</td></tr>';
	// background color
	echo '<tr valign="top"><th scope="row">Background color</th>';
	echo '<td><input type="text" name="bgcolor" value="'.$options['bgcolor'].'" size="8"></input><br />6 character hex color value</td></tr>';
	// transparent
	echo '<tr valign="top"><th scope="row">Use transparent Mode</th>';
	echo '<td><input type="checkbox" name="trans" value="true"';
	if( $options['trans'] == "true" ){ echo ' checked="checked"'; }
	echo '></input><br />Switches on Flash\'s wmode-transparent setting</td></tr>';
	// RSS Path
	echo '<tr valign="top"><th scope="row">RSS Spreadshirt Shop URL</th>';
	echo '<td><input type="text" name="rsspath" value="'.$options['rsspath'].'" size="200"></input><br /></td></tr>';
        echo '<tr valign="top"><th scope="row">Cube rotation Delay(in Sec.)</th>';
        echo '<td><input type="text" name="cubedelay" value="'.$options['cubedelay'].'" size="4"></input><br /></td></tr>';
		echo '<tr valign="top"><th scope="row">Call a JavaScript on Click at Cube</th>';
	echo '<td><input type="text" name="jsf" value="'.$options['jsf'].'" size="100"></input><br /></td></tr>';
	echo '<input type="hidden" name="wpspreadshirt_submit" value="true"></input>';
	echo '</table>';
	echo '<p class="submit"><input type="submit" value="Update Options &raquo;"></input></p>';
	echo "</div>";
	echo '</form>';
	
}

//uninstall all options
function wp_spreadshirt_uninstall () {
	delete_option('spreadshirt_options');
	delete_option('spreadshirt_widget');
}


// widget
function widget_init_wp_spreadshirt_widget() {
	// Check for required functions
	if (!function_exists('register_sidebar_widget'))
		return;

	function wp_spreadshirt_widget($args){
	    extract($args);
		$options = get_option('wpspreadshirt_widget');
		?>
	        <?php echo $before_widget; ?>
			<?php if( !empty($options['title']) ): ?>
				<?php echo $before_title . $options['title'] . $after_title; ?>
			<?php endif; ?>
			<?php
				if( !stristr( $_SERVER['PHP_SELF'], 'widgets.php' ) ){
					echo wp_spreadshirt_createflashcode(true);
				}
			?>
	        <?php echo $after_widget; ?>
		<?php
	}
	
	function wp_spreadshirt_widget_control() {
		$options = $newoptions = get_option('wpspreadshirt_widget');
		if ( $_POST["wpspreadshirt_widget_submit"] ) {
			$newoptions['title'] = strip_tags(stripslashes($_POST["wpspreadshirt_widget_title"]));
			//$newoptions['width'] = strip_tags(stripslashes($_POST["wpspreadshirt_widget_width"]));
			//$newoptions['height'] = strip_tags(stripslashes($_POST["wpspreadshirt_widget_height"]));
			$newoptions['tcolor'] = strip_tags(stripslashes($_POST["wpspreadshirt_widget_tcolor"]));
			$newoptions['tcolor2'] = strip_tags(stripslashes($_POST["wpspreadshirt_widget_tcolor2"]));
			$newoptions['bgcolor'] = strip_tags(stripslashes($_POST["wpspreadshirt_widget_bgcolor"]));
            $newoptions['trans'] = strip_tags(stripslashes($_POST["wpspreadshirt_widget_trans"]));
            $newoptions['rsspath'] = strip_tags(stripslashes($_POST["wpspreadshirt_widget_rsspath"]));
            $newoptions['cubedelay'] = strip_tags(stripslashes($_POST["wpspreadshirt_widget_cubedelay"]));
			$newoptions['jsf'] = strip_tags(stripslashes($_POST["wpspreadshirt_widget_jsf"]));
		}
		if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option('wpspreadshirt_widget', $options);
		}
		$title = attribute_escape($options['title']);
		//$width = attribute_escape($options['width']);
		//$height = attribute_escape($options['height']);
		$tcolor = attribute_escape($options['tcolor']);
		$tcolor2 = attribute_escape($options['tcolor2']);
		$bgcolor = attribute_escape($options['bgcolor']);
	    $trans = attribute_escape($options['trans']);
		$rsspath = attribute_escape($options['rsspath']);
        $delay = attribute_escape($options['cubedelay']);
		$jsf = attribute_escape($options['jsf']);
		
		?>
			<p><label for="wpspreadshirt_widget_title"><?php _e('Title:'); ?> <input class="widefat" id="wpspreadshirt_widget_title" name="wpspreadshirt_widget_title" type="text" value="<?php echo $title; ?>" /></label></p>
	<p><label for="wpspreadshirt_widget_rsspath"><?php _e('RSS Spreadshirt URL:'); ?> <input class="widefat" id="wpspreadshirt_widget_rsspath" name="wpspreadshirt_widget_rsspath" type="text" value="<?php echo $rsspath; ?>" /></label></p>

	<p><label for="wpspreadshirt_widget_cubedelay"><?php _e('Cube autorotation delay(in Sec.):'); ?> <input class="widefat" id="wpspreadshirt_widget_cubedelay" name="wpspreadshirt_widget_cubedelay" type="text" value="<?php echo $delay; ?>" /></label></p>
					<p><label for="wpspreadshirt_widget_jsf"><?php _e('Cube call JavaScript on Click at Buy Button:'); ?> <input class="widefat" id="wpspreadshirt_widget_jsf" name="wpspreadshirt_widget_jsf" type="text" value="<?php echo $jsf; ?>" /></label></p>
			<p><label for="wpspreadshirt_widget_tcolor"><?php _e('Cube Color:'); ?> <input class="widefat" id="wpspreadshirt_widget_tcolor" name="wpspreadshirt_widget_tcolor" type="text" value="<?php echo $tcolor; ?>" /></label></p>
			<p><label for="wpspreadshirt_widget_tcolor2"><?php _e('Text Color:'); ?> <input class="widefat" id="wpspreadshirt_widget_tcolor2" name="wpspreadshirt_widget_tcolor2" type="text" value="<?php echo $tcolor2; ?>" /></label></p>
			<p><label for="wpspreadshirt_widget_bgcolor"><?php _e('Background Color:'); ?> <input class="widefat" id="wpspreadshirt_widget_bgcolor" name="wpspreadshirt_widget_bgcolor" type="text" value="<?php echo $bgcolor; ?>" /></label></p>
			<p><label for="wpspreadshirt_widget_trans"><input class="checkbox" id="wpspreadshirt_widget_trans" name="wpspreadshirt_widget_trans" type="checkbox" value="true" <?php if( $trans == "true" ){ echo ' checked="checked"'; } ?> > Background Transparency</label></p>
			<input type="hidden" id="wpspreadshirt_widget_submit" name="wpspreadshirt_widget_submit" value="1" />
		<?php
	}
	
	register_sidebar_widget( "WP-Spreadshirt-Gallery", wp_spreadshirt_widget );
	register_widget_control( "WP-Spreadshirt-Gallery", "wp_spreadshirt_widget_control" );
}

// Delay plugin execution until sidebar is loaded
add_action('widgets_init', 'widget_init_wp_spreadshirt_widget');

// add the actions
add_action('admin_menu', 'wp_spreadshirt_add_pages');
register_activation_hook( __FILE__, 'wp_spreadshirt_install' );
register_deactivation_hook( __FILE__, 'wp_spreadshirt_uninstall' );
if( function_exists('add_shortcode') ){
	add_shortcode('wp-spreadshirt', 'wp_spreadshirt_shortcode');
	add_shortcode('WP-SPREADSHIRT', 'wp_spreadshirt_shortcode');
} else {
	add_filter('the_content','wp_spreadshirt_init');
}

?>