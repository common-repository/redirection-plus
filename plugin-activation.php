<?php

/**
 * Plugin activation
 */

/**
 * Create database for plugin
 */
register_activation_hook( RPP_PLUGIN_FILE, 'rpp_create_table_for_plugin', 15 );

function rpp_create_table_for_plugin() {
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();

	$redirection_links_sql = "
		CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}rpp_redirection_links` (
			`redirection_link_id`  int(20) unsigned  NOT NULL AUTO_INCREMENT,
			`source_url`           text    CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
			`target_url`           text    CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
			`redirection_url_name` text    CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
			`hits_count`           int(4)  unsigned  NOT NULL DEFAULT '0',
			`unique_hits_count`    int(4)  unsigned  NOT NULL DEFAULT '0',
			`redirection_type`     smallint(6)       NOT NULL DEFAULT '0',
			`created_date`         datetime          NOT NULL DEFAULT '0000-00-00 00:00:00',
			PRIMARY KEY (`redirection_link_id`)
		) {$charset_collate};";

	$geo_redirection_links_sql = "
		CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}rpp_geo_redirection_links` (
			`geo_redirection_link_id` int(20) unsigned  NOT NULL AUTO_INCREMENT,
			`country`                 text    CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
			`target_url`              text    CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
			`destination_url`         text    CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
			`hits_count`              int(4)  unsigned  NOT NULL DEFAULT '0',
			`unique_hits_count`       int(4)  unsigned  NOT NULL DEFAULT '0',
			`destination_type`        smallint(6) NOT NULL DEFAULT '0',
			`created_date`            datetime    NOT NULL DEFAULT '0000-00-00 00:00:00',
			PRIMARY KEY (`geo_redirection_link_id`)
		) {$charset_collate};";

	$link_clicks_sql = "
		CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}rpp_link_clicks` (
			`id`           int(20) unsigned  NOT NULL AUTO_INCREMENT,
			`ip`           text    CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
			`anchor_text`  text    CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
			`target_url`   text    CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,			
			`browser_name` text    CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
			`country_code` text    CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
			`country_name` text    CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
			`date`         date    NOT NULL DEFAULT '0000-00-00',
			PRIMARY KEY (`id`)
		) {$charset_collate};";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	dbDelta( $redirection_links_sql );
	dbDelta( $geo_redirection_links_sql );
	dbDelta( $link_clicks_sql );
}


