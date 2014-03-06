== Compact Audio Player More Control ==
Contributors: Tips and Tricks HQ, Techborder
Tags: audio, audio player, embed, media, media player, mp3, mp3 player, music, music player, player, podcast, sound
Requires at least: 3.0
Tested up to: 3.8
Stable tag: 0.1 
License: GPLv2 or later

Navigate to Dashboard > Settings to control. A compact audio player that is compatible with all major browsers and devices (Android, iPhone, iPad). 

== Description ==

Similar to Compact WP Audio Player but with more settings in the Dashboard, this means after you setup your theme, you can easily change the audio file or auto-play settings in the WordPress Dashboard.

This audio player plugin Supports .mp3 and .ogg file formats

= Features =
* The audio player is compact so it does not take a lot of real estate on your webpage
* HTML5 compatible so the audio files embedded with this plugin will play on iOS devices
* Works on all major browsers - IE7, IE8, IE9, Safari, Firefox, Chrome
* If you do podcasting then this audio player can be used to embed the audio files on your WordPress posts or pages
* If you are selling audio files from your site then you can use this plugin to offer a preview
* Add the audio player to any post/page using shortcode
* Use autoplay option to play an audio/mp3 file as soon as the page loads

More details can be found on the [Compact Audio Player More Control plugin page](http://www.techborder.com/wordpress-plugin-compact-audio-player-more-control)

== Installation ==

1. Upload the "cap_more_control.zip" file via the WordPress's plugin uploader (Plugins -> Add New -> Upload)
2. Activate the plugin through the "Plugins" menu in Wordpress.
3. Update the File URL in Dashboard settings (in Dashboard > Settings > C Audio Player MC).
4. Create a page or post to show the player using shortcode. 
5. Add shortcode anywhere in the post/page like: [capmc_embed_player_settings]

== Usage ==

See http://www.techborder.com/wordpress-plugin-compact-audio-player-more-control.

== Screenshots ==
Visit the following page for screenshots
http://www.techborder.com/wordpress-plugin-compact-audio-player-more-control.

== Frequently Asked Questions ==
None

== Upgrade Notice ==
None

== Changelog ==

v0.0

= How to add to your theme =

See http://www.techborder.com/wordpress-plugin-compact-audio-player-more-control.

== Download ==

On github: https://github.com/techborder/compact-audio-player-more-control.git.

== Configuration ==

Once the code has been added to your theme, set your File URL (audio file) in Dashboard > Settings > C Audio Player MC.

In the settings, in Audio File, add full URL to the song or audio.

== Settings controlled in Dashboard ==

Audio file (File URL).
Loop.
Auto-play.
If given as attributes in the shortcode capmc_embed_player_settings, they will be ignored and overridden by the settings in the Dashboard.

== Pass-through attributes ==

Just as in Compact Audio Player, you can still control attributes of volume and CSS class through the shortcode capmc_embed_player_settings.

<?php
    echo do_shortcode('[capmc_embed_player_settings volume="90"]');
?>

== Pass-through shortcode attributes ==

 1. volume.
 2. class.

== License ==

GPLv3

== Details ==

Even though the icon shows a mute button, the action that takes place is not muting the volume but stopping the audio file from playing. A couple reasons for this:

  * Most visitors that do not want any sound so their first instinct is to go for the mute button.
  * Many times there is already a photo gallery slider control that uses the play and pause symbol. Adding another play and pause icon would confuse the visitor.

== Support ==

Code is free under GPLv3. Support is provided for a fee as a consulting service - Contact to hire us.
