<?php

/*
Plugin Name: EXP Admin Branding
Plugin URI: https://expand.sk
Description: Expand admin branding.
Author: Andrej Srna
Version: 0.1
Author URI: htts://andrej.space 
*/
if ( include(dirname(__FILE__) . '/includes/version-conflict-check.php') ) {
	return;
}

if ( !defined('AME_ROOT_DIR') ) {
	define('AME_ROOT_DIR', dirname(__FILE__));
}

//Are we running in the Dashboard?
if ( is_admin() ) {

    //Load the plugin
    require 'includes/menu-editor-core.php';
    $wp_menu_editor = new WPMenuEditor(__FILE__, 'ws_menu_editor');

}//is_admin()

function slate_files() {
  wp_enqueue_style( 'slate-admin-theme', plugins_url('css/theme.css', __FILE__), array(), '1.1.6' );
  wp_enqueue_script( 'slate', plugins_url( "js/slate.js", __FILE__ ), array( 'jquery' ), '1.1.6' );
}
add_action( 'admin_enqueue_scripts', 'slate_files' );
add_action( 'login_enqueue_scripts', 'slate_files' );

function slate_add_editor_styles() {
    add_editor_style( plugins_url('css/editor-style.css', __FILE__ ) );
}
add_action( 'after_setup_theme', 'slate_add_editor_styles' );

add_filter('admin_footer_text', 'slate_admin_footer_text_output');
function slate_admin_footer_text_output($text) {
	$text = 'Powered by <a href="https://expand.sk">Expand.sk</a>';
  return $text;
}

add_action( 'admin_head', 'slate_colors' );
add_action( 'login_head', 'slate_colors' );
function slate_colors() {
	include( 'css/dynamic.php' );
}
function slate_get_user_admin_color(){
	$user_id = get_current_user_id();
	$user_info = get_userdata($user_id);
	if ( !( $user_info instanceof WP_User ) ) {
		return;
	}
	$user_admin_color = $user_info->admin_color;
	return $user_admin_color;
}

// Remove the hyphen before the post state
add_filter( 'display_post_states', 'slate_post_state' );
function slate_post_state( $post_states ) {
	if ( !empty($post_states) ) {
		$state_count = count($post_states);
		$i = 0;
		foreach ( $post_states as $state ) {
			++$i;
			( $i == $state_count ) ? $sep = '' : $sep = '';
			echo "<span class='post-state'>$state$sep</span>";
		}
	}
}

?>
