<?php

/////////////////////////////////////////////////////////////////////////
////// NAV //////////////////////////////////////////////////////////////

function bca_settings_nav() {
    
    // add the sub menu page for the plugin
    // https://codex.wordpress.org/Adding_Administration_Menus
	
	
	if(get_option('bca_audit_api')==1 || get_option('bca_404_active')==1){
	
		add_menu_page( 
			__('Logs','bcapi'), 
			__('Logs','bcapi'),  
			'manage_options', 
			'bca_dashboard', 
			'bca_dashboard_pag', 
			'dashicons-clipboard', 
			90 
		  );
		
		add_submenu_page('bca_dashboard', 'Dashboard', 'Dashboard', 'manage_options', 'bca_dashboard' );


		if(get_option('bca_audit_api')==1){
			add_submenu_page( 
				'bca_dashboard', 
				__('Google Pagespeed','bcaudit'), 
				__('Google Pagespeed','bcaudit'),  
				'manage_options', 
				'bca_pagespeed', 
				'bca_pagespeed_pag'  // this should correspond with the function name
			); 
		}

		if(get_option('bca_404_active')==1){
			add_submenu_page( 
				'bca_dashboard', 
				__('404 Errors','bcaudit'), 
				__('404 Errors','bcaudit'),  
				'manage_options', 
				'bca_404', 
				'bca_404error_pag'  // this should correspond with the function name
			); 
		}
		
	}
	

		add_submenu_page( 
			'options-general.php',  //tools.php
			__('Audit', 'bcaudit'), 
			__('Audit', 'bcaudit'), 
			'manage_options', 
			'bca_settings', 
			'bca_settings_pag'  // this should correspond with the function name
		);

	
}

add_action( 'admin_menu', 'bca_settings_nav' );