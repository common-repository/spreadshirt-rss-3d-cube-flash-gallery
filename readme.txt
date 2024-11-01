=== WP-Spreadshirt-Gallery ===
Contributors: Flashapplications, Joerg Sontag
Info: http://flashapplications.de/?p=370
Tags: spreadshirt, flash, shirt, gallery, rss, 3D, feed, sidebar, widget

Requires at least: 1.3
Tested up to: 1.3
Stable tag: 1.3

WP-Spreadshirt-Gallery displays a 3D Cube of your Spreadshirt Items in the Sidebar of your Blog and link each Item to your Shop.
requires php5!
== Description ==

This Widget shows a 3D-Cube Gallery of your Spreadshirt Shop Items.
::: Requires php5! :::

== Installation ==

1. Make sure you're running WordPress version 2.3 or better. It won't work with older versions.
2. Download the zip file and extract the contents.
3. Upload the 'wp-spreadshirt-gallery' folder to your plugins directory (wp-content/plugins/).
4. Activate the plugin through the 'plugins' page in WP.
5. See 'Options->WP Spreadshirt Gallery' to adjust things like display size, etc...

= In order to actually display the 3D Cube Gallery, you have three options. =
1. Create a page or post and type [wp-spreadshirt] anywhere in the content. This 'tag' will be replaced by the flash movie when viewing the page. See [here](http://flashapplications.de/?p=370) for more info.
2. Add the following code anywhere in your theme to display the Cube. `<?php wp_spreadshirt_insert(); ?>` This can be used to add WP Spreadshirt Gallery to your sidebar.
3. The plugin adds a widget, so you can place it on your sidebar through 'Appearance'->'Widgets'. Open the widget to access it's own set of settings (background color,  etc).

2. 
== Frequently Asked Questions == 
= I'd like to change something in the Flash movie, will you release the .fla? =
Just Mail me : sontag(at)flashapplications.de

== Screenshots ==

1. The Cube Front : You can set colors that match your theme on the plugin's options page.
2. The Widget Options panel.
3. See 'Options->WP Spreadshirt Gallery' to adjust things like display size, etc...

== Options ==

The options page allows you to change the Flash movie's , change the text color as well as the background.

= RSS Shop URL =
Insert the RSS URL of an Spreadshirt Shop.

= Color of the Cube =
Type the hexadecimal color value you'd like to use for the tags, but not the '#' that usually precedes those in HTML. Black (000000) will obviously work well with light backgrounds, white (ffffff) is recommended for use on dark backgrounds. 
= Color of the Text =
Type the hexadecimal color value you'd like to use for the tags, but not the '#' that usually precedes those in HTML. Black (000000) will obviously work well with light backgrounds, white (ffffff) is recommended for use on dark backgrounds. 
= Background color =
The hex value for the background color you'd like to use. This options has no effect when 'Use transparent mode' is selected.

= Use transparent mode =
Turn on/off background transparency. Enabling this might cause issues with some (mostly older) browsers.

= Cube autorotation Delay (in Sec.) =
Allows you to change the Delay of the Cube automatic rotation.


== Changelog ==

= 1.0 =
* Initial release version.

= 1.1 =
* Fixed Memoryleak in Flash for more Performance!
* Errorhandling for more User Information.
* Now you can place the Gallery Cube to Content by tag [wp-spreadshirt]

= 1.2 =
* Fixed Description Text Bug!

= 1.3 =
* Fixed Bug in proxy.php
* Added call a JS Function now you can set a js function to handle the Buy Button you will get a String Path of the clicked Item of Cube!
* if you want to use the Cube in Article just [wp-spreadshirt] insert this
* for PHP Insert <?php wp_spreadshirt_insert(); ?>

== Upgrade Notice ==
-