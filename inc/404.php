<?php

// Create a table
function bca_db_table404(){
    if(get_option('bca_404_active')==1){
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        global $wpdb;
        $tablename = $wpdb->prefix."404_log"; 
        //print_r($wpdb->tables);
        if(!bca_t_ex('404_log')){
            
            $main_sql_create = "CREATE TABLE " . $tablename."(
                id int NOT NULL AUTO_INCREMENT,
                pageurl varchar(255),
                timestamp int(11),
                ip varchar(255),
                PRIMARY KEY (id)
            );";    

            maybe_create_table( $wpdb->prefix . $tablename, $main_sql_create );   
        }
    }
        
}
add_action( 'init', 'bca_db_table404' );

// Check if a DB exists
function bca_t_ex($dbname){

	global $wpdb;
	$table_name = $wpdb->base_prefix.$dbname;
	$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );

	if ( ! $wpdb->get_var( $query ) == $table_name ) {
		return false;
	}else{
		return true;
	}

}

// Fire on the 404 page
function bca_log_404_page(){
    if(get_option('bca_404_active')==1){
        if( is_404() ){
        
            if(bca_404_no_spam()==true){
                bca_404_logger();
            }
            
        }
    }
    
}
add_action( 'template_redirect', 'bca_log_404_page' );

// Log error hits
function bca_404_logger(){
    
    global $wpdb;
    if(get_option('bca_404_active')==1){
        $tablename = $wpdb->prefix."404_log"; 
            if(bca_t_ex('404_log')){
            $url = sanitize_text_field($_SERVER['REQUEST_URI']);

            $url = str_replace(' ', '', $url);
            $url = str_replace('', '', $url);

            $url = site_url().$url;

            $wpdb->insert($tablename, 
                array(
                'pageurl' => $url,
                'timestamp' => time(),
                //'template' => 'template-name',
                'ip' => bca_404_get_ip(),
                ),
                array(
                '%s',
                '%s',
                '%s',
                '%s',
                '%s'
                ) 
            ); 
        
        
        }
    }
}

// helpers

function bca_404_get_ip(){
    
    // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    // option to turn this off, else make this reflect in the privacy policy
    
    // activate when log by IP.
    // else return a 0.0.0.0
    if(get_option('bca_404_private')==1){
        $ip = '0.0.0.0';
    }else{
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
    }
    
    return $ip;
}

/* 
Function that fetches the IP address
*/

function bca_404_no_spam(){
    
    // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    // option to turn this off, else make this reflect in the privacy policy

    if(bca_t_ex('404_log')){
        global $wpdb;
        $url = site_url().$_SERVER['REQUEST_URI'];
        
        // log by IP
        if(get_option('bca_404_private')!=1){
            $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}404_log WHERE ip = '".bca_404_get_ip()."' ORDER BY timestamp DESC LIMIT 1", OBJECT );
        }else{
        // log by Page
            $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}404_log WHERE pageurl = '".$url."' ORDER BY timestamp DESC LIMIT 1", OBJECT );
        }

        $my_interval = get_option('bca_404_log_interval');
        if($my_interval==''){ $my_interval = 60; }

        if(isset($results[0])){
            $difference = time() - $results[0]->timestamp;
        }else{
            $difference = ($my_interval*10);    
        }

        
        
        if($difference > $my_interval){ // has to have 60 seconds in between each log
            return true;  
        }else{
            return false;
        }
    }
}

// Clean 404 log data

function bca_clean_404_log(){
    if(get_option('bca_404_active')==1){
        if(get_option('bca_clean_404_log')==''){
            $timespan = (365*24*60*60)-200;
        }else{
            $timespan = get_option('bca_clean_404_log')*24*60*60;
        }
        global $wpdb;
        $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}404_log WHERE timestamp<=".(time()-$timespan)." ORDER BY timestamp DESC", OBJECT );
        foreach ($results as $key => $val){
        
        
            $wpdb->query( "DELETE  FROM {$wpdb->prefix}404_log WHERE id='{$key}'" );
            
            
        }
    }
}


add_action('bca_clean_404_log', 'bca_clean_404_log');
if ( wp_next_scheduled( 'bca_clean_404_log' )=='' ) {
    wp_schedule_event( time(), 'daily', 'bca_clean_404_log' );
}
