<?php

function bca_run_installation(){

    // add all the options needed

	update_option('bca_pagespeed_audit_link', 1 ); // radio
	update_option('bca_schema_audit_link', 1 ); // radio

	update_option('bca_404_active', 1 ); // radio
	update_option('bca_404_private', 1 ); // radio
	update_option('bca_404_log_interval', 60*5 ); // radio
    update_option('bca_clean_404_log', round(365/4) ); // radio
    
    //run the form transient (email|activate)
    update_option('bca_new_installation', 1);

}

function bca_run_deactivation(){

	// tell the server the plugin is being deactivated:
	bca_uninstall_api(get_option('bca_token'));
	
	// Remove all options
    delete_option('bca_audit_api');
    delete_option('bca_email');
    delete_option('bca_beta'); 
    delete_option('bca_pagespeed_audit_link'); 
	delete_option('bca_schema_audit_link'); 
	delete_option('bca_404_active'); 
	delete_option('bca_404_private'); 
	delete_option('bca_404_log_interval'); 
    delete_option('bca_clean_404_log'); 
    delete_option('bca_token');
    delete_option('bca_date');
    delete_option('bca_error');
    delete_option('bca_http');
    delete_option('bca_panel');
    delete_option('bca_status');
    delete_option('bca_task');
    delete_option('bca_account');
    delete_option('bca_url');
    delete_option('bca_verified');
    delete_option('bca_api_content');
    delete_option('bca_new_installation');
    
	// Remove all transients
    delete_transient('bca_ask_account_update'); 
    delete_transient('bca_api_content_request');


}

// installation form
function bca_install_form(){
    if(get_option('bca_new_installation')==1){
        ?>
        <div class="bca_overlay">

            <div class="bca_centerbox">
                <h1>WP Audit <span>Setup</span></h1>
                <div class="bca_whitebox">
                <form method="post" action="options.php">
                    <?php settings_fields( 'bca_setup' ); ?>
                    <?php do_settings_sections( 'bca_setup' ); ?>

                    <div class="bca_form_field">
                    <label class="main_label"><?php _e('Enable automated Google Pagespeed checks','bcaudit'); ?></label>
                    <?php
                    $args = array(
                        'type' => 'text',
                        'val' => get_option('bca_audit_api'),
                        'cb' => '1',
                        'name' => 'bca_audit_api',
                        
                    );
                    bca_f_check($args,__('By checking this box you will activate the automated Google Pagespeed Audit. This will start gathering information in the background.','bcaudit'));
                    ?>
                    </div>
                    <div class="bca_form_field">
                    <label class="main_label"><?php _e('Confirm your email address.','bcaudit'); ?></label>
                    <?php
                    $args = array(
                        'type' => 'text',
                        'val' => get_option('bca_email'),
                        'cb' => get_bloginfo('admin_email'),
                        'name' => 'bca_email',
                        
                    );
                    bca_f_input($args,__('This is not an email newsletter and your details will not be sold.','bcaudit'));
                    ?> 
                    </div>
                    
                    <input type="hidden" name="bca_new_installation" value="0" />
                    <?php submit_button(); ?>
                </form>
                </div>
            </div>

        </div>
        <?php
    }
}

add_action('admin_footer', 'bca_install_form');

function bca_init_alpha_beta(){
	
	// making sure this one is always set!
	// This way you can go for beta mode by setting it to 1 in the options.php.
	// a new api connection will be made to api-beta.wpaudit instead of api.wpaudit
	// things can (and will) break if you do! So at your own risk.
	
	if(get_option('bca_apistring')==''){ 
		update_option('bca_apistring', 0); 
	}
	
}

add_action('init', 'bca_init_alpha_beta');

function bca_footer_label(){
	
?>
<!-- WP Audit <?php if(get_option('bca_audit_api')==1){ ?>is keeping an eye on this website... <?php } ?>-->
<?php
	
}

add_action('wp_footer', 'bca_footer_label');