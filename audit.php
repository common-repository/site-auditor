<?php 
/**
* Plugin Name: WP Audit
* Plugin URI: https://wpaudit.dev
* Description: Run automated Google Pagespeed/Lighthouse audits and keep track of your 404 errors all in one plugin.
* Version: 2.1.33
* Author: Rik Janssen
* Author URI: https://rikjanssen.info
* Text Domain: bcaudit
**/


// making connection to the api.
include_once('inc/install.php'); // running sequence
include_once('inc/rest.php'); // all the rest functions
include_once('inc/form.php'); // forms
include_once('inc/links.php'); // links
include_once('inc/404.php'); // 404 
include_once('inc/nav.php'); // wp-admin nav

include_once('inc/p.settings.php'); // settings page
include_once('inc/p.dashboard.php'); // dashboard page
include_once('inc/p.404.php'); // 404 page
include_once('inc/p.pagespeed.php'); // settings page


// Check the state of the account -> api side
function bca_check_account() {
    
    // is the api enabled?
    if(get_option('bca_audit_api')!=1){ return; }

    // is it x hours ago since last check?
    if(get_transient('bca_ask_account_update')!=''){ return; }
    
    // check for API availability
    $api = bca_register_api();

    foreach($api as $k => $v){
        update_option('bca_'.$k, esc_html($v), true);
    }

    if($api['status']==1){
        set_transient('bca_ask_account_update', date("Y-m-d H:i:s"), (60*60) * 23 );
        return true;
    }else{
        set_transient('bca_ask_account_update', date("Y-m-d H:i:s"), (60*60));
        return false;
    }

}
add_action('init', 'bca_check_account');


// Add the CSS to the WP-admin
function bca_enqueue_css() {
   wp_enqueue_style('bcaudit', plugin_dir_url( __FILE__ ).'assets/style.css');
}
add_action('admin_enqueue_scripts', 'bca_enqueue_css');

// Add the JS to the WP-admin
function bca_enqueue_js($hook) {
    wp_register_script( 'bcaudit-js-toggle',plugin_dir_url( __FILE__ ).'assets/toggleShow.js', '',true );
    wp_enqueue_script('bcaudit-js-toggle');
}
add_action('admin_enqueue_scripts', 'bca_enqueue_js');


// Plugins page listing
function bca_plugin_links( $links ) {

	$links = array_merge( array(
		'<a href="' . esc_url( 'https://www.patreon.com/wpaudit' ) . '">' . __( 'Help me develop WP Audit', 'bcaudit' ) . '</a>'
	), $links );

    $links = array_merge( array(
		'<a href="' . esc_url( admin_url( '/options-general.php?page=bca_settings' ) ) . '">' . __( 'Settings', 'bcaudit' ) . '</a>'
    ), $links );
    
	return $links;
}

add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'bca_plugin_links' );


// Activation and deactivation
register_activation_hook( __FILE__, 'bca_run_installation' );
register_deactivation_hook( __FILE__, 'bca_run_deactivation' );


?>