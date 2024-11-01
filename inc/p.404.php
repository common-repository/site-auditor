<?php 

/////////////////////////////////////////////////////////////////////////
////// PAG //////////////////////////////////////////////////////////////

function bca_404error_pag(){
    global $wpdb;
    if(bca_t_ex('404_log')==false){ 
        _e('The database table is not made (yet)..','bcaudit');
        return;     
    }
?>
<div class="wrap bca_wrap">

    <h1><?php _e("WP Audit",'bcaudit'); ?></h1>
    <h2><?php _e("404 Error Records",'bcaudit'); ?></h2>


<?php
$items_per_page = 25;
$page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
$offset = ( $page * $items_per_page ) - $items_per_page;

$query = 'SELECT * FROM '.$wpdb->prefix.'404_log';

$total_query = "SELECT COUNT(1) FROM (${query}) AS combined_table";
$total = $wpdb->get_var( $total_query );

$results = $wpdb->get_results( $query.' ORDER BY id DESC LIMIT '. $offset.', '. $items_per_page, OBJECT );


if($total!=0){
?>

    <div id="bca_error404list">

    <div class="bca_tablenav bca_tn_top">
        <div class="bca_tn_numbers">
            <strong><?php echo $total; ?> <?php _e('errors','bcaudit'); ?></strong> | <?php echo $items_per_page.' '; _e('per page','bcaudit'); ?>
        </div>
		<div class="bca_tn_pages">
        <?php echo paginate_links( array(
		'base' => add_query_arg( 'cpage', '%#%' ),
		'format' => '',
		'prev_text' => __('&laquo;'),
		'next_text' => __('&raquo;'),
		'total' => ceil($total / $items_per_page),
		'current' => $page
		)); ?></div>
	</div>
		
    <div class="bc_table_box">
    <table class="wp-list-table widefat fixed striped table-view-list posts">
    <thead> 
        <tr class="bc_t_titlerow">
            <th><?php _e("Page",'bcaudit'); ?></th>
            <th class="bca_datecol"><?php _e("Time",'bcaudit'); ?></th>
            <?php if(get_option('bca_404_private')!=1){ ?>
            <th class="bca_ipcol"><?php _e("IP Address",'bcaudit'); ?></th>
            <?php } ?>
        </tr>
    </thead>


    <tbody> 
        <?php

        foreach($results as $key => $val){

        $friendly_date = date_i18n( get_option('date_format'), $val->timestamp ).'<br />'.date_i18n( get_option('time_format'), $val->timestamp );
        ?>
        <tr>
            
            <td>
                <strong><a href="<?php echo $val->pageurl; ?>" target="_blank"><?php echo $val->pageurl; ?></a></strong>
            </td>
            <td class="bca_datecol"><?php echo $friendly_date; ?></td>
            <?php if(get_option('bca_404_private')!=1){ ?>
            <td class="bca_ipcol"><?php echo $val->ip; ?></td>
            <?php } ?>
        </tr>
        <?php

        }

        ?>
    </tbody>        


    </table>
    </div>

	<div class="bca_tablenav bca_tn_bottom">
        <div class="bca_tn_numbers">
            <strong><?php echo $total; ?> <?php _e('errors','bcaudit'); ?></strong> | <?php echo $items_per_page.' '; _e('per page','bcaudit'); ?>
        </div>
		<div class="bca_tn_pages">
        <?php echo paginate_links( array(
		'base' => add_query_arg( 'cpage', '%#%' ),
		'format' => '',
		'prev_text' => __('&laquo;'),
		'next_text' => __('&raquo;'),
		'total' => ceil($total / $items_per_page),
		'current' => $page
		)); ?></div>
	</div>


    </div>
    <?php }else{ ?>
        <div class="bc_row">
            <h3><?php _e('There is no 404 page data (yet)..','bcaudit'); ?></h3>
        </div>
    <?php } ?>
    
</div>

<?php
}