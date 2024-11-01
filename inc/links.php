<?php

/////////////////////////////////////////////////////////////////////////
////// GPS //////////////////////////////////////////////////////////////

function bca_ps_link( $actions, $id ) {
    
    if(get_option('bca_pagespeed_audit_link')==1){
     
        $siteaudit['pagespeed_url'] = 'https://developers.google.com/speed/pagespeed/insights/?url=';

        global $post, $current_screen, $mode;
        $post_type_object = get_post_type_object( $post->post_type );
        $permalink = get_permalink($post->ID);
        $actions['pagespeed'] = '<a href="'  .esc_url($siteaudit['pagespeed_url'].urlencode($permalink)). '" title="'
            . esc_attr( __( 'Run a speed audit on this page. (will open in a new tab)', 'bcaudit'  ) ) 
            . '" target="_blank">' . __( 'Pagespeed', 'bcaudit'  ) . '</a>';
        return $actions;

    }else{
        return $actions;
    }
    
}

add_filter( 'post_row_actions',  'bca_ps_link', 10, 2 );
add_filter( 'page_row_actions',  'bca_ps_link', 10, 2 );

function bca_sch_link( $actions, $id ) {
    
    if(get_option('bca_schema_audit_link')==1){
     
        $siteaudit['schema_url'] = 'https://search.google.com/structured-data/testing-tool/u/0/#url=';

        global $post, $current_screen, $mode;
        $post_type_object = get_post_type_object( $post->post_type );
        $permalink = get_permalink($post->ID);
        $actions['schema'] = '<a href="'  .esc_url($siteaudit['schema_url'].urlencode($permalink)). '" title="'
            . esc_attr( __( 'Run a schema audit on this page. (will open in a new tab)', 'bcaudit'  ) ) 
            . '" target="_blank">' . __( 'Schema', 'bcaudit'  ) . '</a>';
        return $actions;

    }else{
        return $actions;
    }
    
}

add_filter( 'post_row_actions',  'bca_sch_link', 10, 2 );
add_filter( 'page_row_actions',  'bca_sch_link', 10, 2 );