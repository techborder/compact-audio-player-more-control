<?php
/*
Plugin Name: Compact Audio Player More Control
Description: Add your shortcode to your theme and control via Dashboard > Settings. Plays a specified audio file (.mp3 or .ogg) using a simple and compact audio player. The audio player is compatible with all major browsers and devices (Android, iPhone). What is different from Compact WP Audio player is there are smaller icons and you have more control via the Dashboard. Totally based on and shortcode attributes are backwards compatible with Compact WP Audio Player 1.7. See http://www.techborder.com/wordpress-plugin-compact-audio-player-more-control for instructons.
Version: 0.1
Author: Tips and Tricks HQ (http://www.tipsandtricks-hq.com/), Techborder.
Author URI: http://www.techborder.com
License: GPL3 
*/

define('C_AUDIO_MORE_CTL_PLUGIN_VERSION', '0.0');
define('C_AUDIO_MORE_CTL_BASE_URL', plugins_url('/',__FILE__));

add_action('init', 'wp_capmc_init');
function wp_capmc_init()
{
	if (!is_admin()){
		wp_register_script('scap.soundmanager2', C_AUDIO_MORE_CTL_BASE_URL .'js/soundmanager2-nodebug-jsmin.js');
		wp_enqueue_script('scap.soundmanager2');
		wp_register_style('scap.flashblock', C_AUDIO_MORE_CTL_BASE_URL .'css/flashblock.css');
		wp_enqueue_style('scap.flashblock');
		wp_register_style('scap.player', C_AUDIO_MORE_CTL_BASE_URL .'css/player.css');
		wp_enqueue_style('scap.player');
	}
}

function capmc_footer_code(){
	$debug_marker = "<!-- Compact Audio Player More Control plugin v" . C_AUDIO_MORE_CTL_PLUGIN_VERSION . " http://www.techborder.com -->";
	echo "\n${debug_marker}\n";
	?>
	<script type="text/javascript">
	soundManager.useFlashBlock = true; // optional - if used, required flashblock.css
	soundManager.url = '<?php echo C_AUDIO_MORE_CTL_BASE_URL ; ?>swf/soundmanager2.swf';
	function play_mp3(flg,ids,mp3url,volume,loops)
	{
	  soundManager.createSound({
		id:'btnplay_'+ids,
		volume: volume,
		url: mp3url
	  });

	  if(flg == 'play'){
		<?php 
		if(get_option('capmc_audio_disable_simultaneous_play') == '1'){
			echo 'stop_all_tracks();';
		}
		?>
		soundManager.play('btnplay_'+ids,{
			onfinish: function() {
				if(loops == 'true'){
					loopSound('btnplay_'+ids);
				}
				else{
					document.getElementById('btnplay_'+ids).style.display = 'inline';
					document.getElementById('btnstop_'+ids).style.display = 'none';
				}
			}
		});
	  }
	  else if(flg == 'stop'){
		//soundManager.stop('btnplay_'+ids);
		soundManager.pause('btnplay_'+ids);
	  }
	}
	function show_hide(flag,ids)
	{
	  if(flag=='play'){
		document.getElementById('btnplay_'+ids).style.display = 'none';
		document.getElementById('btnstop_'+ids).style.display = 'inline';
	  }
	  else if (flag == 'stop'){
		document.getElementById('btnplay_'+ids).style.display = 'inline';
		document.getElementById('btnstop_'+ids).style.display = 'none';
	  }
	}
	function loopSound(soundID) 
	{
		window.setTimeout(function() {
			soundManager.play(soundID,{onfinish:function(){loopSound(soundID);}});
		},1);
	}
	function stop_all_tracks()
	{
		soundManager.stopAll();
		var inputs = document.getElementsByTagName("input");
		for (var i = 0; i < inputs.length; i++) {
			if(inputs[i].id.indexOf("btnplay_") == 0){
				inputs[i].style.display = 'inline';//Toggle the play button
			}
			if(inputs[i].id.indexOf("btnstop_") == 0){
				inputs[i].style.display = 'none';//Hide the stop button
			}
		}
	}
	</script>
	<?php
}
add_action('wp_footer', 'capmc_footer_code');

function capmc_embed_player_settings_handler($atts, $content = null) 
{
	extract(shortcode_atts(array(
		'volume' => '',
		'class' => '',
	), $atts, 'capmc_embed_player_settings'));	

	if(empty($volume)){
		// Let the lower-level function determine the default.
		$volume_attr = "";
	} else {
		$volume = (int) $volume;
		if ( 0 > $volume || 100 < $volume ) {
			// Let the lower-level function determine the default.
			$volume_attr = "";
		}
		$volume_attr = 'volume="'. $volume . '"';
	}
	
	if(empty($class)){
		// Let the lower-level function determine the default.
		$class_attr = "";
	} else {
		$class_attr = 'class="'. $class . '"';
	}
	
	$fileurl = get_option('capmc_file_url');
	if(empty($fileurl)){
		return '<div style="color:red;font-weight:bold;">Compact Audio Player Error! You must enter the mp3 file URL via the "File URL" parameter in the Dashboard Settings. Please check the documentation for more info.</div>';
	}
	
	// Determine autoplay value. Defaults to autoplay.
	$autoplay_attr = "autoplay=true"; // Default value
	if( get_option('capmc_autoplay') !== FALSE ) {
		// Option is set
		if( !get_option('capmc_autoplay') ) {
			// Option is set but unchecked. Do not autoplay.
			// From experience, cannot depend on autoplay=false to stop autoplay.
			// To stop autoplay, we have to completely leave out the
			// shortcode attribute (argument) autoplay.
			$autoplay_attr = "";
		} // else Use default value.
	} // else Option is not set. Use default value.
	
	if( get_option('capmc_loop', "true") ) {
		$loop_attr = "true";
	} else {
		$loop_attr = "false";
	}
	
	echo do_shortcode('[capmc_embed_player '
			. $autoplay_attr .' '
			. $class_attr .' '
			. $volume_attr
			. ' loops="'. $loop_attr
			. '" fileurl="'. $fileurl
			. '"]'
		);
}

function capmc_embed_player_handler($atts, $content = null) 
{
	extract(shortcode_atts(array(
		'fileurl' => '',
		'autoplay' => '',
		'volume' => '',
		'class' => '',
		'loops' => '',
	), $atts));	
	if(empty($fileurl)){
		return '<div style="color:red;font-weight:bold;">Compact Audio Player Error! You must enter the mp3 file URL via the "fileurl" parameter in this shortcode. Please check the documentation and correct the mistake.</div>';
	}
	if(empty($volume)){$volume = '80';}
	if(empty($class)){$class = "sc_player_container1";} //Set default container class
	if(empty($loops)){$loops = "false";}
	$ids = uniqid();

	$player_cont = '<div class="'.$class.'">';
	$player_cont .= '<input type="button" id="btnplay_'.$ids.'" class="myButton_play" onClick="play_mp3(\'play\',\''.$ids.'\',\''.$fileurl.'\',\''.$volume.'\',\''.$loops.'\');show_hide(\'play\',\''.$ids.'\');" />';
	$player_cont .= '<input type="button"  id="btnstop_'.$ids.'" style="display:none" class="myButton_stop" onClick="play_mp3(\'stop\',\''.$ids.'\',\'\',\''.$volume.'\',\''.$loops.'\');show_hide(\'stop\',\''.$ids.'\');" />';
	$player_cont .= '<div id="sm2-container"><!-- flash player ends up here --></div>';
	$player_cont .= '</div>';

	if(!empty($autoplay)){
$path_to_swf = C_AUDIO_MORE_CTL_BASE_URL .'swf/soundmanager2.swf';
$player_cont .= <<<EOT
<script type="text/javascript" charset="utf-8">
soundManager.setup({
	url: '$path_to_swf',
	onready: function() {
		var mySound = soundManager.createSound({
		id: 'btnplay_$ids',
		volume: '$volume',
		url: '$fileurl'
		});
		var auto_loop = '$loops';
		mySound.play({
			onfinish: function() {
				if(auto_loop == 'true'){
					loopSound('btnplay_$ids');
				}
				else{
					document.getElementById('btnplay_$ids').style.display = 'inline';
					document.getElementById('btnplay_$ids').style.display = 'none';
				}
			}
		});
		document.getElementById('btnplay_$ids').style.display = 'none';
		document.getElementById('btnstop_$ids').style.display = 'inline';
	},
	ontimeout: function() {
		// SM2 could not start. Missing SWF? Flash blocked? Show an error.
		alert('Error! Audio player failed to load.');
	}
});
</script>
EOT;
	}//End autopay code

	return $player_cont;
}
add_shortcode('capmc_embed_player', 'capmc_embed_player_handler');
add_shortcode('capmc_embed_player_settings', 'capmc_embed_player_settings_handler');
if (!is_admin()){add_filter('widget_text', 'do_shortcode');}
add_filter('the_excerpt', 'do_shortcode',11);

//Create admin page 
add_action('admin_menu', 'capmc_mp3_player_admin_menu');
function capmc_mp3_player_admin_menu() {
	add_options_page('C Audio Player More Control', 'C Audio Player MC', 8, __FILE__, 'capmc_mp3_options');
}

function capmc_mp3_options() 
{
	echo '<div class="wrap">';
	echo '<div id="poststuff"><div id="post-body">';
	echo '<div id="icon-upload" class="icon32"><br></div><h2>Compact Audio Player More Control</h2>';
	
	echo '<div style="background: #FFF6D5; border: 1px solid #D1B655; color: #3F2502; padding: 15px 10px">Visit the <a href="http://www.techborder.com/wordpress-plugin-compact-audio-player-more-control" target="_blank">Compact Audio Player More Control</a> plugin page for more documentation.</div>';
	echo "<p>This is a simple all browser supported audio player based on Compact Audio Player. There are extra settings if you want to use them. You can just add the shortcode in a WordPress theme, post or page to embed the audio player.</p>";
	echo '<h3>Shortcode Format</h3>';
	echo '<h4>Controlled via Settings</h4>';
	echo "<p><code>[capmc_embed_player_settings]</code></p>";
	echo "<p>For more on how to use the shortcode, go to the website above to learn how to add it to your theme and control the audio player with the settings below.</p>";
	echo "<h4>Legacy</h4>";
	echo "<p>Using the legacy shortcode does not use most of the settings below. The only setting that will have an affect with the legacy shortcode is 'Disable Simultaneous Play'</p>";
	echo '<p><code>[capmc_embed_player fileurl="URL OF THE MP3 FILE"]</code></p>';	
	echo '<p><strong>Example:</strong></p>';
	echo '<p><code>[capmc_embed_player fileurl="http://www.example.com/wp-content/uploads/my-music/mysong.mp3"]</code></p>';

	if(isset($_POST['capmc_player_settings'])){
		update_option('capmc_audio_disable_simultaneous_play', isset($_POST["capmc_audio_disable_simultaneous_play"])?'1':'');
		update_option('capmc_file_url', isset($_POST["capmc_file_url"])?$_POST["capmc_file_url"]:'');
		update_option('capmc_autoplay', isset($_POST["capmc_autoplay"])?'1':'');
		update_option('capmc_loop', isset($_POST["capmc_loop"])?'1':'');
	}
		
	?>
	<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">

	<div class="postbox">
	<h3><label for="title">Compact Audio Player More Control Settings</label></h3>
	<div class="inside">
	
	<table class="form-table">
	
	<tr valign="top"><td width="25%" align="left"><label for="capmc_file_url">
	File URL:
	</label>
	</td><td align="left">    
	<input name="capmc_file_url" type="text" maxlength="100" size="50" <?php if(get_option('capmc_file_url')!='') echo ' value="'. get_option('capmc_file_url') . '"'; ?> />
	<br /><p class="description">File to be played.</p>
	</td></tr>

	<tr valign="top"><td width="25%" align="left"><label for="capmc_autoplay">
	Auto-play: 
	</label></td><td align="left">    
	<input name="capmc_autoplay" type="checkbox"<?php if(get_option('capmc_autoplay')!='') echo ' checked="checked"'; ?> value="1"/>
	<br /><p class="description">Automatically play the audio when a visitor comes to your site. Does not work for iOS devices (Apple doesn't allow it).</p>
	</td></tr>
	
	<tr valign="top"><td width="25%" align="left"><label for="capmc_loop">
	Loop: 
	</label></td><td align="left">    
	<input name="capmc_loop" id="capmc_loop" type="checkbox"<?php if(get_option('capmc_loop')!='') echo ' checked="checked"'; ?> value="1"/>
	<br /><p class="description">If checked, then repeat audio file.</p>
	</td></tr>
	
	<tr valign="top"><td width="25%" align="left">
	Disable Simultaneous Play: 
	</td><td align="left">    
	<input name="capmc_audio_disable_simultaneous_play" type="checkbox"<?php if(get_option('capmc_audio_disable_simultaneous_play')!='') echo ' checked="checked"'; ?> value="1"/>
	<br /><p class="description">Legacy feature if using Compact WP Audio Player shortcode. Check this option if you only want to allow one audio file to be played at a time (helpful if you have multiple audio files on a page). It will automatically stop the audio file that is currently playing when a user plays a new file.</p>
	</td></tr>
	
	</table>
	
	<div class="submit">
	<input type="submit" class="button-primary" name="capmc_player_settings" value="<?php _e('Update'); ?>" />
	</div>
	
	
	</div></div>
	</form>

	<?php
	echo '</div></div>';
	echo '</div>';//end of wrap
}
?>
