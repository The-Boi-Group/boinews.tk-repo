<?php
   /*
   Plugin Name: Remove Admin Bar logo
   Plugin URI: https://www.mdbrx.com/wp-plugin/
   Description: Removes the Wordpress logo from the admin-bar for a cleaner look. No settings needed. Just enable the plugin and it will work instantly.
   Version: 1.0.5
   Author: mdbrna
   Author URI: https://mdbrx.com/wp-plugin
   License: GPLv2 or later
   */
   
	function admin_bar_remove_logo() {
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu( 'wp-logo' );
    }
    add_action('wp_before_admin_bar_render','admin_bar_remove_logo', 0 );
?>