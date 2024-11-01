<?php 
/////////////////////////////////////////////////////////////////////////
////// FORMS ////////////////////////////////////////////////////////////

////// CHECK
function bca_f_check($arg, $label=''){
	if (isset($arg['cb'])==''){
		$arg['cb'] = 0;
	}
?>
<div class="bcaudit_check_wrapper">
	<label>
		<input type="checkbox" 
			   name="<?php echo esc_html($arg['name']); ?>" 
			   value="<?php echo esc_html($arg['cb']); ?>"
			   <?php 
				if($arg['cb']==$arg['val']){ echo "checked"; } ?> />
		<span></span>
		<?php if ($label!=''){ echo "<label>".__($label,'bcaudit')."</label>"; } ?>
	</label>
</div>
<?php
}

////// SELECT
function bca_f_select($arg){

?>
<div class="bcaudit_select_wrapper">
	<select name="<?php echo esc_html($arg['name']); ?>">
		<?php // making a list of the options
		foreach($arg['options'] as $name => $value){
			if($value['op_value']==$arg['selected']){$checkme=' selected';}else{$checkme='';}
			?><option value="<?php echo esc_html($value['op_value']); ?>"<?php echo $checkme; ?>><?php echo esc_html($value['op_name']); ?></option><?php
		} ?>
	</select>
</div>
<?php
}

////// INPUT
function bca_f_input($arg,$label=''){
    if(!isset($arg['type'])){
        $arg['type'] = 'text';
	}
	if (!isset($arg['cb'])){
		$arg['cb'] = '';
	}
    if ($arg['val'] == ''){
		$arg['val'] = $arg['cb'];
	}
?>
<label class="bcaudit_input_wrapper">
	<input type="<?php echo esc_html($arg['type']); ?>"
		   name="<?php echo esc_html($arg['name']); ?>"
		   value="<?php echo esc_html($arg['val']); ?>"
		   class="large-text" 
		   />
    <?php if ($label!=''){ echo "<span>".__($label,'bcaudit')."</span>"; } ?>

</label>
<?php	
}

////// TEXTAREA
function bca_f_textarea($arg){
//not for html!!!
?>
<div class="bcaudit_textarea_wrapper">
	<textarea name="<?php echo esc_html($arg['name']); ?>" 
			  class="large-text code"
			  rows="10"
			  cols="50"><?php echo esc_html($arg['selected']); ?></textarea>
</div>
<?php	
}

/////////////////////////////////////////////////////////////////////////
////// SUBMIT ///////////////////////////////////////////////////////////

function bcaudit_register_settings() {
	
	// sanitize settings
    $args_html = array(
            'type' => 'string', 
            'sanitize_callback' => 'wp_kses_post',
            'default' => NULL,
            );	
	
    $args_int = 'intval';
	
    $args_text = array(
            'type' => 'string', 
            'sanitize_callback' => 'sanitize_text_field',
            'default' => NULL,
            );

    // Account tab
    // -------------------	
	// this corresponds to some information added at the top of the form
	$setting_name[0] = 'bca_settings';
	$setting_name[1] = 'bca_request_delete';
	$setting_name[2] = 'bca_setup';
	
	
	// adding the information to the database as options
	register_setting( $setting_name[0], 'bca_audit_api', $args_int ); // radio
	register_setting( $setting_name[0], 'bca_email', $args_text ); // text
	register_setting( $setting_name[0], 'bca_beta', $args_int ); // text
	
	register_setting( $setting_name[0], 'bca_pagespeed_audit_link', $args_int ); // radio
	register_setting( $setting_name[0], 'bca_schema_audit_link', $args_int ); // radio

	register_setting( $setting_name[0], 'bca_404_active', $args_int ); // radio
	register_setting( $setting_name[0], 'bca_404_private', $args_int ); // radio
	register_setting( $setting_name[0], 'bca_404_log_interval', $args_int ); // radio
	register_setting( $setting_name[0], 'bca_clean_404_log', $args_int ); // radio

	register_setting( $setting_name[1], 'bca_delete_request', $args_int ); // radio
	register_setting( $setting_name[1], 'bca_audit_api', $args_int ); // radio

	register_setting( $setting_name[2], 'bca_audit_api', $args_int ); // radio
	register_setting( $setting_name[2], 'bca_email', $args_text ); // text
	register_setting( $setting_name[2], 'bca_new_installation', $args_text ); // text
		      
}

add_action( 'admin_init', 'bcaudit_register_settings' );


// Request deletion of data from api
function bca_req_delete(){

	if(get_option('bca_delete_request')==1 && get_option('bca_audit_api')==0 && get_transient('bca_delete_request_timer')!=1){

		// run the request
		bca_delete_api(get_option('bca_token'));

		// update all local options
		update_option('bca_delete_request',0);

		// remove all local options
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
		set_transient('bca_ask_account_update', date("Y-m-d H:i:s"), (12*60));

	}

}

add_action( 'admin_init', 'bca_req_delete' );
