<?php 


/////////////////////////////////////////////////////////////////////////
////// PAG //////////////////////////////////////////////////////////////

function bca_settings_pag(){
?>
<div class="wrap bca_wrap">

    <h1><?php _e("WP Audit",'bcaudit'); ?></h1>
    <h2><?php _e("Settings",'bcaudit'); ?></h2>
	<div class="bca_settings_block">
	<h2><?php _e("Automated Pagespeed Audit",'bcaudit'); ?></h2>
    <form method="post" action="options.php">
    <?php settings_fields( 'bca_settings' ); ?>
    <?php do_settings_sections( 'bca_settings' ); ?>
    <?php if(get_transient('bca_delete_request_timer')!=1){ ?>
    <table class="form-table">
        <tr valign="top">
            <th scope="row">
                <?php _e("Automated Pagespeed Checks",'bcaudit'); ?>

            </th>
            <td>
                <?php
                $args = array(
                    'type' => 'text',
                    'val' => get_option('bca_audit_api'),
                    'cb' => '1',
                    'name' => 'bca_audit_api',
                    
                );
                bca_f_check($args,__('Enable the automated weekly Google Pagespeed check.','bcaudit'));
                ?>
                           
            </td>
        </tr>

        
        <?php if(get_option('bca_audit_api')==1){ ?>
        <tr valign="top">
            <th scope="row">
                <?php _e("API Token",'bcaudit'); ?>

            </th>
            <td>
                
            <input type="text" readonly="readonly" value="<?php echo esc_html(get_option('bca_token')); ?>" class="large-text code">
            
			
			<?php if(get_option('bca_account_active')==1){ ?>
			<br />
				<p class="bca-okay-msg"><span class="dashicons dashicons-yes-alt"></span> <?php _e("Last update",'bcaudit'); ?>: <?php echo esc_html(date_i18n( 'F j, H:i ',strtotime(get_transient('bca_ask_account_update')))); ?></p>
			<?php }else{ ?>
			<br />
				<p class="bca-warning-msg"><span class="dashicons dashicons-warning"></span> <?php _e("Your account is set to inactive on the server.",'bcaudit'); ?><br /><?php _e("Last update",'bcaudit'); ?>: <?php echo esc_html(date_i18n( 'F j, H:i',strtotime(get_transient('bca_ask_account_update')))); ?></p>
			<?php } ?>
				
            <?php if(get_option('bca_callback_error')!=0 && get_option('bca_beta')==1){ ?>
			<br />
			<p class="bca-warning-msg"><span class="dashicons dashicons-dismiss"></span> 
				<?php  
												 
					if(get_option('bca_callback_error')==1){
						_e('There was a problem connecting back to the website from the server (callback). The server can not reach it. Please contact WP Audit if this keeps happening.', 'bcaudit');
					}elseif(get_option('bca_callback_error')==2){
						_e('There was a problem connecting back to the website from the server (callback). If you have restricted your WP REST please look into this before contacting WP Audit.', 'bcaudit');
					}else{
						_e('There was a problem connecting back to the website from the server (callback).', 'bcaudit');
					}
												 
					
												 
					if(get_option('bca_callback_error_count')!=0){
						echo "<br />";
						echo "<strong>".__("Attempt",'bcaudit')." ".get_option('bca_callback_error_count')." ".__("of",'bcaudit')." ".get_option('bca_callback_error_max_count')."</strong>: ";
						_e('The connection issue is persistent so the server is counting down. If you are over the treshold some of the functions may stop working as intended...', 'bcaudit');
						
					}
				
				?>
				<br /><br />
				<a href="<?php echo get_site_url(); ?>/wp-json/bcaudit/v1/call/all?token=<?php echo esc_html(get_option('bca_token')); ?>" class="button button-primary" target="_blank">What I'm sending to WP Audit [Callback]</a>
				<a href="https://rikjanssen.info/support" class="button" target="_blank">Contact Support</a>
				<br /><br />
				<small><?php _e("If you don't see anything strange then try the callback url in another browser where you aren't logged in to Wordpress to make sure there aren't any restrictions or limitations on your REST API.", 'bcaudit'); ?></small>
			</p>
			<?php } ?>               
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <?php _e("Email",'bcaudit'); ?>

            </th>
            <td>
                
            <?php
                $args = array(
                    'type' => 'text',
                    'val' => get_option('bca_email'),
                    'cb' => get_bloginfo('admin_email'),
                    'name' => 'bca_email',
                    
                );
                bca_f_input($args,__('In order to inform you about significant changes and important updates.','bcaudit'));
                ?>                           
            </td>
        </tr>
		
        <tr valign="top">
            <th scope="row">
                <?php _e("Share Plugin and Theme data with the API (beta feature)",'bcaudit'); ?>

            </th>
            <td>
                <?php
                $args = array(
                    'type' => 'text',
                    'val' => get_option('bca_beta'),
                    'cb' => '1',
                    'name' => 'bca_beta',
                    
                );
                bca_f_check($args,__('Sharing this data will be needed when using the website overview panel in the future. This is still a work in progress..','bcaudit'));
                ?><br />
				<span class="dashicons dashicons-admin-links"></span>
				<a href="https://rikjanssen.info/2020/09/03/wp-audit-is-finally-here/"><?php _e("Read more about the future of WP Audit here.",'bcaudit'); ?></a>     
            </td>
        </tr>
        <?php }else{
            ?>
            <input type="hidden" name="bca_email" value="<?php echo esc_html(get_option('bca_email')); ?>">
            <input type="hidden" name="bca_beta" value="<?php echo esc_html(get_option('bca_beta')); ?>">
            <?php
        } ?>
    </table>
    <?php }else{ ?>
        <h4><?php _e("A delete request is active, please check back in 15 minutes..",'bcaudit'); ?></h4>
        <input type="hidden" name="bca_audit_api" value="0">
        <input type="hidden" name="bca_beta" value="<?php echo esc_html(get_option('bca_beta')); ?>">
        <input type="hidden" name="bca_email" value="<?php echo esc_html(get_option('bca_email')); ?>">

    <?php } ?>
    </div>
	<div class="bca_settings_block">
    <h2><?php _e("404 Error Logger",'bcaudit'); ?></h2>
    <table class="form-table">
        <tr valign="top">
            <th scope="row">
                <?php _e("Activate 404 Log",'bcaudit'); ?>

            </th>
            <td>
                <?php
                $args = array(
                    'type' => 'text',
                    'val' => get_option('bca_404_active'),
                    'cb' => '1',
                    'name' => 'bca_404_active',
                    
                );
                bca_f_check($args,__('Keep a list of 404 page hits so you know what your website is missing.','bcaudit'));
                ?>
                           
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <?php _e("Private logging",'bcaudit'); ?>

            </th>
            <td>
                <?php
                $args = array(
                    'type' => 'text',
                    'val' => get_option('bca_404_private'),
                    'cb' => '1',
                    'name' => 'bca_404_private',
                    
                );
                bca_f_check($args,__('Do not log IP addressess of users. This way you dont have to mention it in your Privacy Policy','bcaudit'));
                ?>
                           
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <?php _e("Logging interval",'bcaudit'); ?>

            </th>
            <td>
                <?php
                $args = array(
                    'type' => 'text',
                    'selected' => get_option('bca_404_log_interval'),
                    'options' => array(
                        array(
                            'op_value' => 10,
                            'op_name' => __('10 seconds','bcaudit')
                        ),
                        array(
                            'op_value' => 30,
                            'op_name' => __('30 seconds','bcaudit')
                            ),
                        array(
                        'op_value' => 60,
                        'op_name' => __('1 minute','bcaudit')
                        ),
                        array(
                            'op_value' => 300,
                            'op_name' => __('5 minutes','bcaudit')
                        ),
                        array(
                            'op_value' => 600,
                            'op_name' => __('10 minutes','bcaudit')
                        ),
                        array(
                            'op_value' => 1800,
                            'op_name' => __('30 minutes','bcaudit')
                        ),
                        array(
                            'op_value' => 3600,
                            'op_name' => __('1 Hour','bcaudit')
                        ),
                        array(
                            'op_value' => 7200,
                            'op_name' => __('2 Hours','bcaudit')
                        )
                        
                    ),
                    'name' => 'bca_404_log_interval',
                    
                );
                bca_f_select($args); ?>
                           
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <?php _e("Save log history",'bcaudit'); ?>

            </th>
            <td>
                <?php
                $args = array(
                    'type' => 'text',
                    'selected' => get_option('bca_clean_404_log'),
                    'options' => array(
                        array(
                            'op_value' => 1,
                            'op_name' => __('1 day','bcaudit')
                        ),
                        array(
                            'op_value' => 3,
                            'op_name' => __('3 days','bcaudit')
                        ),
                        array(
                            'op_value' => 7,
                            'op_name' => __('1 week','bcaudit')
                        ),
                        array(
                            'op_value' => 31,
                            'op_name' => __('1 month','bcaudit')
                        ),
                        array(
                            'op_value' => round(365/4),
                            'op_name' => __('3 months','bcaudit')
                        ),
                        array(
                            'op_value' => round(365/2),
                            'op_name' => __('6 months','bcaudit')
                        ),
                        array(
                            'op_value' => 365,
                            'op_name' => __('1 year','bcaudit')
                        ),
                        array(
                            'op_value' => 365*2,
                            'op_name' => __('2 years','bcaudit')
                        ),
                        array(
                            'op_value' => 365*3,
                            'op_name' => __('3 years','bcaudit')
                        ),
                        array(
                            'op_value' => 365*5,
                            'op_name' => __('5 years','bcaudit')
                        ),
                        
                    ),
                    'name' => 'bca_clean_404_log',
                    
                );
                bca_f_select($args); ?>
                           
            </td>
        </tr>
        
        
  
    </table>
    </div>
	<div class="bca_settings_block">
    <h2><?php _e("Post Type Links",'bcaudit'); ?></h2>
    <table class="form-table">
        <tr valign="top">
            <th scope="row">
                <?php _e("Pagespeed Audit button",'bcaudit'); ?>

            </th>
            <td>
                <?php
                $args = array(
                    'type' => 'text',
                    'val' => get_option('bca_pagespeed_audit_link'),
                    'cb' => '1',
                    'name' => 'bca_pagespeed_audit_link',
                    
                );
                bca_f_check($args,__('Show Pagespeed Audit link under every post in the WP-admin post/page list.','bcaudit'));
                ?>
                           
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <?php _e("Schema Validate button",'bcaudit'); ?>

            </th>
            <td>
                <?php
                $args = array(
                    'type' => 'text',
                    'val' => get_option('bca_schema_audit_link'),
                    'cb' => '1',
                    'name' => 'bca_schema_audit_link',
                    
                );
                bca_f_check($args,__('Show Schema Validator link under every post in the WP-admin post/page list.','bcaudit'));
                ?>
                           
            </td>
        </tr>


  
    </table>
	</div>
    <?php submit_button(); ?>
    </form>


    <?php if(get_transient('bca_delete_request_timer')!=1){ ?>
        <br /><br />
        <div class="bca_settings_block">
        <h2 id="danger">The danger zone!</h2>
        <form method="post" action="options.php">
        <?php settings_fields( 'bca_request_delete' ); ?>
        <?php do_settings_sections( 'bca_request_delete' ); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">
                    <?php _e("Request deletion of all data stored on our servers",'bcaudit'); ?>

                </th>
                <td>
                <div id="deleteButton" style="display:none;">
                <?php _e("Warning: this action cannot be undone! Clicking this button will result in creating a request to delete all information from our servers.",'bcaudit'); ?>   
                <p>
                <input type="hidden" name="bca_audit_api" value="0" />
                <input type="hidden" name="bca_delete_request" value="1" />
                <input type="hidden" name="submit" id="submit_delete" class="button button-primary" value="<?php _e("Delete all data",'bcaudit'); ?>"> <a href="#danger" class="button" onclick="toggleWarning()"><?php _e("Nah, not today....",'bcaudit'); ?></a>
                </p>    
                
                </div>
                <div id="warningMsg" style="display:block;">
                    <a href="#danger" onclick="toggleWarning()"><?php _e("Proceed to delete all data",'bcaudit'); ?></a>
                </div>
                </td>
            </tr>

        </table>
			
        
        </form>
		</div>
    <?php } ?>

</div>
<?php
}