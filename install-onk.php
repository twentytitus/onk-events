<?php
global $onk2019_db_version;
$onk2019_db_version = '1.1';

function onk2019_install() {
	global $wpdb;
	global $onk2019_db_version;

	$charset_collate = $wpdb->get_charset_collate();
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	
	$table_name = $wpdb->prefix . 'onk2019';
	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL,
		name varchar(255) NOT NULL,
		description varchar(2048) NULL,
		categories varchar(255) NULL,
		organiser varchar(255) NOT NULL,
		day mediumint(2) NOT NULL,
		time_start time NOT NULL,
		time_end time NOT NULL,
		place varchar(255) NULL,
		address varchar(255) NULL,
		coord1 decimal(7,5) NULL,
		coord2 decimal(7,5) NULL,
	        kids tinyint(1) NULL,
		wheelchair tinyint(1) NULL,
		additional varchar(255) NULL,
		link_organiser varchar(1024) NULL,
		link_event varchar(1024) NULL,
		link_social varchar(1024) NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";
	dbDelta( $sql );

	$category_table_name = $wpdb->prefix . 'onk2019_categories';
	$sql = "CREATE TABLE $category_table_name (
		id mediumint(9) NOT NULL,
		name varchar(255) NOT NULL,
		late_arrival tinyint(1) DEFAULT 1 NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";
	dbDelta( $sql );

	$day_table_name = $wpdb->prefix . 'onk2019_days';
	$sql = "CREATE TABLE $day_table_name (
		day mediumint(2) NOT NULL,
		date date NOT NULL,
		name varchar[32] NOT NULL,
		PRIMARY KEY  (day)
	) $charset_collate;";
	dbDelta( $sql );

	add_option( 'onk2019_db_version', $onk2019_db_version );
}

?>
