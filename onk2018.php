<?php
/*
Plugin Name: ONK 2018
Plugin URI:  ...
Description: The event list for Offenes Neukölln 2018
Version:     1.0
Author:      Titus Laska
Author URI:  
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: onk2018
*/

setlocale (LC_TIME, 'de_DE');

include 'install-onk.php';
include 'menupage.php';
include 'shortcode.php';

register_activation_hook( __FILE__, 'onk_install' );
register_uninstall_hook(__FILE__, 'onk_uninstall');

function onk_add_menu () {
	$menu_string = esc_html__('ONK-Programm', 'onk-programme');
	$submenu_string = esc_html__('Kategorien', 'onk-categories');
	$submenu_string2 = esc_html__('Tage', 'onk-days');
	add_menu_page ( $menu_string, $menu_string, 'publish_posts', 'onk2018', 'onk_menupage', 'dashicons-chart-pie', 40 );
}
add_action( 'admin_menu', 'onk_add_menu' );

function onk_shortcodes_init()
{
    add_shortcode('onk2018', 'onk_shortcode');
}
add_action('init', 'onk_shortcodes_init');

?>
