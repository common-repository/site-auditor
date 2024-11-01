<?php 
/////////////////////////////////////////////////////////////////////////
////// REG //////////////////////////////////////////////////////////////

function bca_register_api(){

    if(get_option('bca_audit_api')!=1){ return; }

	if(get_option('bca_apistring')==1){
		$bcapi_url = 'https://api-beta.wpaudit.dev';	
	}else{
    	$bcapi_url = 'https://api.wpaudit.dev';
	}

	// gather vars for registration
	
	if(get_option('bca_email')==''){ 
        $admin_email= esc_html(get_bloginfo('admin_email'));
    }else{
        $admin_email= esc_html(get_option('bca_email'));
    }

	if( ! function_exists('get_plugin_data') ){
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }
    $plugin_data = get_plugin_data(dirname(__DIR__).'/audit.php' );
	if(isset($plugin_data['Version'])){ $version = $plugin_data['Version']; }else{ $version = 0; }
	
	if(get_option('bca_beta')==''){ $bca_beta = 0; }else{ $bca_beta = get_option('bca_beta'); }
	if(get_option('bca_audit_api')==''){ $bca_audit_api = 0; }else{ $bca_audit_api = get_option('bca_audit_api'); }
	
    // create the data needed for registration
    $input = array(
        'url'=>site_url(),
		'plugin_email'=>$admin_email,
		'plugin_version'=>$version,
		'plugin_auto_audit'=>esc_html($bca_audit_api),
		'plugin_data_share'=>esc_html($bca_beta),
		'wp_name'=>esc_html(get_bloginfo("name")),
		'wp_description'=>esc_html(get_bloginfo("description")),
		'wp_version'=>esc_html(get_bloginfo("version")),
		'wp_language'=>esc_html(get_bloginfo("language"))
    );

	
    $array_query = http_build_query($input, NULL, '&', PHP_QUERY_RFC3986);
    $request_url = $bcapi_url.'/wp-json/bcapi/v1/call/site?'.$array_query;
    // assemble the method
    $arg = array(
        'method' => 'GET'
    );

    // and request a token
    $return = wp_remote_request($request_url, $arg );
	//if(isset($return['body'])){
	    $json = json_decode($return['body']);
	//}
    
    $ret['url'] = $request_url; 

    if(isset($json->auth_token)){
        $ret['status'] = 1; 
        if(isset($json->auth_token)){ $ret['token'] = $json->auth_token; }else{ $ret['token'] = false; }
        if(isset($json->account_level)){ $ret['account'] = $json->account_level; }else{ $ret['account'] = false; }
		if(isset($json->account_active)){ $ret['account_active'] = $json->account_active; }else{ $ret['account_active'] = 0; }
        if(isset($json->user_verified)){ $ret['verified'] = $json->user_verified; }else{ $ret['verified'] = false; }
        if(isset($json->user_connected)){ $ret['panel'] = $json->user_connected; }else{ $ret['panel'] = false; } 
        if(isset($json->http_code)){ $ret['http'] = $json->http_code; }else{ $ret['http'] = false; }
        if(isset($json->server_datetime)){ $ret['date'] = $json->server_datetime; }else{ $ret['date'] = false; } 
        if(isset($json->user_task)){ $ret['task'] = $json->user_task; }else{ $ret['task'] = false; }
        if(isset($json->error_count)){ $ret['error'] = $json->error_count; }else{ $ret['error'] = 0; }
		if(isset($json->callback_error)){ $ret['callback_error'] = $json->callback_error; }else{ $ret['callback_error'] = 0; }
		if(isset($json->callback_error_count)){ $ret['callback_error_count'] = $json->callback_error_count; }else{ $ret['callback_error_count'] = 0; }
		if(isset($json->callback_error_max_count)){ $ret['callback_error_max_count'] = $json->callback_error_max_count; }else{ $ret['callback_error_max_count'] = 0; }
    }else{
        $ret['status'] = 0; 
    }

    return $ret;

}


/////////////////////////////////////////////////////////////////////////
////// CB ///////////////////////////////////////////////////////////////

// Create the callback url
function bca_cb_api(){

    if(get_option('bca_audit_api')!=1){ return; }

    add_action( 'rest_api_init', function () {
        register_rest_route( 
            'bcaudit/v1', 
            'call/(?P<type>[a-zA-Z0-9-]+)', 
            array(
                'methods' => 'GET',
                'callback' => 'bca_cb_request',
                'args' => array(
                            'type' => array(
                                'validate_callback' =>  function($param, $request, $key) { return  $param; }
                                ),
                            )
            ) 
        );
    } );
    

}
add_action('init', 'bca_cb_api'); 

function bca_cb_request( $data ){

    // first do a registration check on request
    bca_check_account();

    /////////////////////////////////////
    // is there a local token?
    $t = esc_html(get_option('bca_token'));
    
    // parameters from url
    foreach($data->get_query_params() as $key => $val){
        $p[$key] = sanitize_text_field($val);
    }

    /////////////////////////////////////
    // prepare the initial data
    if($p['token']==$t){
        $a['auth_token'] = $p['token'];
        $a['client_http_code'] = 202;
        $a['data']['status'] = 202;
    }else{
        $a['auth_token'] = 'no_match';
        $a['client_http_code'] = 403;
        $a['data']['status'] = 403;
    }
    $a['client_datetime'] = date('Y-m-d H:i:s');

    //if the code is not good, return
    if($a['client_http_code'] != 202){
        return $a;
    }

    /////////////////////////////////////
    // otherwise, continue with the data:
	
    $a['data'] = bca_wp_info();

    return $a;

}

// The local data gathering request 
function bca_wp_info(){
    
    if(get_option('bca_beta')==1){ // if datasharing is set....
        // Plugin Info /////////////////////

        if ( ! function_exists( 'get_plugins' ) ) { require_once ABSPATH . 'wp-admin/includes/plugin.php'; }
        foreach(get_plugins() as $id => $pl){

            $d['plugins'][$id]['plugin_slug'] = bca_pl_slug($id);
            $d['plugins'][$id]['plugin_name'] = $pl['Name'];
            $d['plugins'][$id]['plugin_version'] = $pl['Version'];

            if(is_plugin_active($id)){
                $d['plugins'][$id]['plugin_active'] = 1;
            }else{
                $d['plugins'][$id]['plugin_active'] = 0;
            }

        }

        // Theme Info /////////////////////

        $cur_theme = wp_get_theme();
        foreach(wp_get_themes() as $id => $theme){

            $d['themes'][$id]['theme_slug'] = $id;
            $d['themes'][$id]['theme_name'] = $theme->get( 'Name');
            $d['themes'][$id]['theme_version'] = $theme->get( 'Version' );
            $d['themes'][$id]['theme_parent'] = $theme->get( 'Template' );

            if($theme->get( 'Name' ) == $cur_theme->get( 'Name' )){
                $d['themes'][$id]['theme_active'] = 1;
            }else{
                $d['themes'][$id]['theme_active'] = 0;
            }

        }
    }
	
	if(get_option('bca_email')==''){ 
        $admin_email= esc_html(get_bloginfo('admin_email'));
    }else{
        $admin_email= esc_html(get_option('bca_email'));
    }

	if( ! function_exists('get_plugin_data') ){
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }
    $plugin_data = get_plugin_data(dirname(__DIR__).'/audit.php' );
	if(isset($plugin_data['Version'])){ $version = $plugin_data['Version']; }else{ $version = 0; }
	
	$d['settings']['plugin_email'] = $admin_email;
	$d['settings']['plugin_version'] = $version;
	$d['settings']['plugin_auto_audit'] = esc_html(get_option('bca_audit_api'));
	$d['settings']['plugin_data_share'] = esc_html(get_option('bca_beta'));
	$d['settings']['wp_name'] = esc_html(get_bloginfo("name"));
	$d['settings']['wp_description'] = esc_html(get_bloginfo("description"));
	$d['settings']['wp_version'] = esc_html(get_bloginfo("version"));
	$d['settings']['wp_language'] = esc_html(get_bloginfo("language"));
	$d['settings']['php_version'] = esc_html(phpversion());


    return $d;

}


// HELPERS

function bca_pl_slug($id){

    $ar = get_site_transient('update_plugins');

    if(isset($ar->response[$id])){
        return $ar->response[$id]->slug;
    }else{
        if(!isset($ar->no_update[$id]->slug)){
            return false;
        }else{
            return $ar->no_update[$id]->slug;
        }
    }

}


/////////////////////////////////////////////////////////////////////////
////// DELETE ///////////////////////////////////////////////////////////

function bca_delete_api($token){

	if(get_option('bca_apistring')==1){
		$bcapi_url = 'https://api-beta.wpaudit.dev';	
	}else{
    	$bcapi_url = 'https://api.wpaudit.dev';
	}

    if(!isset($token)){ return; }

    // create the data needed for registration
    $input = array(
        'token'=>$token,
        'do'=>'delete_all'
    );

    $array_query = http_build_query($input, NULL, '&', PHP_QUERY_RFC3986);
    $request_url = $bcapi_url.'/wp-json/bcapi/v2/change/?'.$array_query;
    // assemble the method
    $arg = array(
        'method' => 'GET'
    );

    // and request a token
    $return = wp_remote_request($request_url, $arg );

    set_transient( 'bca_delete_request_timer', true, 15*60 );
}

/////////////////////////////////////////////////////////////////////////
////// UNINSTALL ////////////////////////////////////////////////////////

function bca_uninstall_api($token){

	if(get_option('bca_apistring')==1){
		$bcapi_url = 'https://api-beta.wpaudit.dev';	
	}else{
    	$bcapi_url = 'https://api.wpaudit.dev';
	}

    if(!isset($token)){ return; }

    // create the data needed for registration
    $input = array(
        'token'=>$token,
        'do'=>'uninstall'
    );

    $array_query = http_build_query($input, NULL, '&', PHP_QUERY_RFC3986);
    $request_url = $bcapi_url.'/wp-json/bcapi/v2/change/?'.$array_query;
    // assemble the method
    $arg = array(
        'method' => 'GET'
    );

    // and request a token
    $return = wp_remote_request($request_url, $arg );

}


/////////////////////////////////////////////////////////////////////////
////// REQUEST //////////////////////////////////////////////////////////

function bca_request_api(){

	if(get_option('bca_apistring')==1){
		$bcapi_url = 'https://api-beta.wpaudit.dev';	
	}else{
    	$bcapi_url = 'https://api.wpaudit.dev';
	}

    if(get_option('bca_token')==''){
        return 'no_token_saved';
    }
    $input = array(
        'token'=>get_option('bca_token'));

    $arg = array(
        'method' => 'GET'
    );

    $updates = 6; // hour

    $array_query = http_build_query($input, NULL, '&', PHP_QUERY_RFC3986);
    $request_url = $bcapi_url.'/wp-json/bcapi/v3/plugin/?'.$array_query;
    // assemble the method

    if(get_transient('bca_api_content_request1')!=''){

        $ret = get_option('bca_api_content');
        $next_update = date("Y-m-d H:i:s",strtotime(get_transient('bca_api_content_request')) + 60*60*$updates); 
        $ret['reset'] = $next_update;
        $ret['last'] = get_transient('bca_api_content_request');

    }else{ 
		
        $return = wp_remote_request($request_url, $arg);
	
		$json = json_decode($return['body']);
		
        if(isset($json->data)){
            $body = $json->data;
			$ret['error'] = 'all_is_well';
			$ret['body'] = $json;
			
        }else{
            $ret['error'] = 'no_data_retrieved';
			$ret['body'] = $ret['error'];
        }

        
        set_transient( 'bca_api_content_request', date("Y-m-d H:i:s"), $updates * HOUR_IN_SECONDS );

        $next_update = date("Y-m-d H:i:s",strtotime(get_transient('bca_api_content_request')) + 60*60*$updates); 
        $ret['reset'] = $next_update;
        $ret['last'] = get_transient('bca_api_content_request');
		
		update_option( 'bca_api_content', $ret);
		
    }
	
	

    // store some of the body data in the database as temp data

    return  $ret;

}
