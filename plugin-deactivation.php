<?php

/**
 * Plugin deactivation
 */

/**
 * Drop database for plugin
 */
register_deactivation_hook( RPP_PLUGIN_FILE, 'rpp_drop_table_for_plugin');
function rpp_drop_table_for_plugin() {
    global $wpdb;
    $table_array = array(
        $wpdb->prefix . "rpp_redirection_links",
        $wpdb->prefix . "rpp_geo_redirection_links",
        $wpdb->prefix . "rpp_link_clicks",
    );
    foreach ($table_array as $tablename) {
        $wpdb->query("DROP TABLE IF EXISTS `{$tablename}`;");
    }
}

/**
 * Remove option for plugin
 */
register_deactivation_hook( RPP_PLUGIN_FILE, 'rpp_remove_option_for_plugin' );
function rpp_remove_option_for_plugin() {
	delete_option( 'rpp_options' );
    delete_option( 'rpp_link_options' );
    delete_option( 'rpp_sec_options' );
}

