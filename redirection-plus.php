<?php
/*
Plugin Name: Redirection Plus
Plugin URI: https://wordpress.org/plugins/redirection-plus/
Description: Redirection Plus plugin help you to creat new redirection and manage them.
Version: 2.0.0
Author: jannatqualitybacklinks.com
Author URI: https://jannatqualitybacklinks.com
Text Domain: redirection-plus
*/


/**
 * Prefix for Redirection Plus: RPP_   rpp_
 */

//Path to this file
if ( !defined('RPP_PLUGIN_FILE') ) {
	define('RPP_PLUGIN_FILE', __FILE__);
}

/** 
 * Initialize the plugin
 */
require 'init-plugin.php';

/** 
 * Redirection links page config
 */
include 'redirection-links-page.php';

/** 
* Geo Redirection links page config
*/
include 'geo-redirection-links-page.php';

/** 
 * Checker options page config
 */ 
include 'checker-options-page.php';

/**
 * Check all links
 */
include 'checker-core.php';
