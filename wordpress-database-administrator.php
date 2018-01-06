<?php
/**
* Plugin Name: Wordpress Database Administrator
* Plugin URI: 
* Description: This plugin create for manage database
* Version: 1.0
* Author: Nishit Manjarawala
* Author URI: 
* License: 
*/



//adding my styles for admin pages of my plugin
function WDA_admin_css_all_page() {    
    wp_register_style($handle = 'WDA_admin-css-all', $src = plugins_url('css/WDA_style.css', __FILE__), $deps = array(), $ver = '1.0.0', $media = 'all');
    wp_enqueue_style('WDA_admin-css-all');
}
add_action('admin_print_styles', 'WDA_admin_css_all_page');

add_action('admin_menu', 'WDA_Menu_Pages');
function WDA_Menu_Pages(){
    add_menu_page('Wordpress Database Administrator', 'WDA', 'manage_options', 'WDA', 'WDA_main_page' );
    /*add_submenu_page('my-menu', 'Submenu Page Title', 'Whatever You Want', 'manage_options', 'my-menu' );
    add_submenu_page('my-menu', 'Submenu Page Title2', 'Whatever You Want2', 'manage_options', 'my-menu2' );*/
	
	add_submenu_page('WDA_browse_table',__( 'Page title', 'Browse' ),'','manage_options','WDA_browse_table','WDA_browse_table');
}

function WDA_main_page(){	
	require_once 'WDA_main_page.php';
}

function WDA_browse_table(){
	require_once 'WDA_browse_table.php';
}