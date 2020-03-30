<?php
/*
Plugin Name: ONK 2019
Plugin URI:  ...
Description: The event list for Offenes Neukölln 2019
Version:     1.1
Author:      Titus Laska
Author URI:  https://github.com/twentytitus
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: onk2019
*/

setlocale (LC_TIME, 'de_DE');

include 'install-onk.php';
include 'menupage.php';
include 'shortcode.php';

register_activation_hook( __FILE__, 'onk2019_install' );
register_uninstall_hook(__FILE__, 'onk2019_uninstall');

function onk2019_add_menu () {
	$menu_string = esc_html__('ONK 2019', 'onk-programme');
	$submenu_string = esc_html__('Kategorien', 'onk-categories');
	$submenu_string2 = esc_html__('Tage', 'onk-days');
	add_menu_page ( $menu_string, $menu_string, 'publish_posts', 'onk2019', 'onk2019_menupage', 'dashicons-chart-pie', 40 );
}
add_action( 'admin_menu', 'onk2019_add_menu' );

function onk2019_shortcodes_init()
{
    add_shortcode('onk2019', 'onk2019_shortcode');
}
add_action('init', 'onk2019_shortcodes_init');

?>
