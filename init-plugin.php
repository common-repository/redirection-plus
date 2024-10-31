<?php

/**
 * Initialize the plugin
 */

/** 
 * Activation configuration for plugin
 */
include 'plugin-activation.php';

/** 
 * Deactivation configuration for plugin
 */
include 'plugin-deactivation.php';

/** 
 * Add admin menu for plugin
 */ 
add_action( 'admin_menu', 'rpp_menu_pages' );
function rpp_menu_pages() {
	// Admin main menu: "Redirection Plus", Page title: "Redirection Plus"
    add_menu_page( 'Redirection Plus', 'Redirection Plus', 'manage_options', 'rpp_check_options', 'rpp_full_page', 'dashicons-redo' );
}

// Add custom css and js
add_action('admin_enqueue_scripts', 'rpp_admin_enqueue');
function rpp_admin_enqueue() {
    global $pagenow; 
    if ( ( 'admin.php' === $pagenow ) && ( 'rpp_check_options' === $_GET['page'] )) {
        rpp_custom_css();
        rpp_custom_js();
    }
}

// Add custom style to page
function rpp_custom_css() {
	wp_enqueue_style( 'rpp_bootstrap_css',      plugins_url( '/css/bootstrap.min.css',        RPP_PLUGIN_FILE ) );
	wp_enqueue_style( 'rpp_custom_css',         plugins_url( '/css/rpp_custom_style.css',     RPP_PLUGIN_FILE ) );
}
// Add custom js to page
function rpp_custom_js() {
	wp_enqueue_script( 'rpp_bootstrap_js',      plugins_url( '/js/bootstrap.min.js',        RPP_PLUGIN_FILE ) );
    wp_enqueue_script( 'rpp_custom_js',         plugins_url( '/js/rpp_custom_js.js',        RPP_PLUGIN_FILE ) );
    
    // Create nonce for ajax
    wp_localize_script('rpp_custom_js', 'rpp_ajax_var', array(
        'rpp_ajax_url' => admin_url('admin-ajax.php'),
        'rpp_ajax_nonce' => wp_create_nonce('rpp-ajax-nonce')
    ));
}

/** 
 * Callback function for rpp plugin menu
 */
function rpp_full_page() {
	// Check option display function
	rpp_check_options();
} 






