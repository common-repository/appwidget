<?php
/*
Plugin Name: appwidget 
Plugin URI: http://i.appchina.com/wp-plugin/
Description: With appwidget you can easily share your Android app on your blog posts.
Version: 0.3
Author: @濯焰
Author URI: http://weibo.com/dcoupe
License: GPLv2 or later
*/
if ( ! defined('ABSPATH') ) {
	die('Please do not load this file directly.');
}

$tinymce_appwidget_url = WP_PLUGIN_URL.'/'.str_replace(basename(__FILE__), "", plugin_basename(__FILE__)); // get the full url path to your plugin"s directory
define('TinyMCE_Aappwidget_URL', $tinymce_appwidget_url);

add_action('init', 'appwidget_tinymce_addbuttons');
function appwidget_tinymce_addbuttons() {
	if(!current_user_can('edit_posts') && ! current_user_can('edit_pages')) {
		return;
	}
	if(get_user_option('rich_editing') == 'true') {
		add_filter("mce_external_plugins", "appwidget_tinymce_addplugin");
		add_filter('mce_buttons', 'appwidget_tinymce_registerbutton');
	}
}
function appwidget_tinymce_registerbutton($buttons) {
	array_push($buttons, 'separator', 'appwidget');
	return $buttons;
}
function appwidget_tinymce_addplugin($plugin_array) {
	$plugin_array['appwidget'] = TinyMCE_Aappwidget_URL.'tinymce/editor_plugin.js';
	return $plugin_array;
}


?>