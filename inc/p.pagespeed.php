<?php 

/////////////////////////////////////////////////////////////////////////
////// PAG //////////////////////////////////////////////////////////////

function bca_pagespeed_pag(){
    $siteaudit = 'https://developers.google.com/speed/pagespeed/insights/?url=';
	// get the dataset:
    $d = bca_request_api();
?>
<div class="wrap bca_wrap">

    <h1><?php _e("WP Audit",'bcaudit'); ?></h1> 
	<h2><?php _e("Google Pagespeed Audits",'bcaudit'); ?></h2> 
	<p><?php _e("A Pagespeed test can tell you a lot about your websites health. In the list below only the scores are shown so you can tell if there are any dips or peaks after changes made on the server or to your website. The Weekly Pagespeed check is ran on our servers so your visitors won't notice it. If you like to read a full report on your website then click the Pagespeed Test button below or activate the extra link in the page/post-edit list below the title.",'bcaudit'); ?></p>
	<?php if(get_option('bca_account_active')==1){ ?>
	<?php 
	if(get_transient("bca_api_content_request")!=''){
		$last_fetch_date = date_i18n( get_option('date_format'), strtotime(get_transient("bca_api_content_request"))).' '.date_i18n( get_option('time_format'), strtotime(get_transient("bca_api_content_request")));
		?>
		<p class="bca_update"><?php _e("Refreshed: ",'bcaudit'); ?><?php echo $last_fetch_date; ?></p>
	<?php } ?>
    <br />
	<div class="bc_table_box bc_tb_730">
		
	<?php //if($d['body']!='' AND is_array($d['body'])) { ?>
    <table class="wp-list-table widefat fixed striped table-view-list posts">
    <thead> 
        <tr class="bc_t_titlerow">
        <th class="manage-column bca_weekcol"><?php _e("Week",'bcaudit'); ?></th>
            <th class="manage-column"><?php _e("Audit Date",'bcaudit'); ?></th>
            <th class="manage-column bca_datacol2" colspan="2"><?php _e("Performance",'bcaudit'); ?></th>
            <th class="manage-column bca_datacol2" colspan="2"><?php _e("Accessibility",'bcaudit'); ?></th>
            <th class="manage-column bca_datacol2" colspan="2"><?php _e("SEO",'bcaudit'); ?></th>
            <th class="manage-column bca_datacol"><?php _e("Total",'bcaudit'); ?></th>
            
        </tr>

        <tr class="bc_t_titlerow">
            <th class="manage-column bca_weekcol"></th>
            <th class="manage-column"></th>
            <th class="manage-column bca_datacol"><?php _e("Mobile",'bcaudit'); ?></th>
            <th class="manage-column bca_datacol"><?php _e("Desktop",'bcaudit'); ?></th>
            <th class="manage-column bca_datacol"><?php _e("Mobile",'bcaudit'); ?></th>
            <th class="manage-column bca_datacol"><?php _e("Desktop",'bcaudit'); ?></th>
            <th class="manage-column bca_datacol"><?php _e("Mobile",'bcaudit'); ?></th>
            <th class="manage-column bca_datacol"><?php _e("Desktop",'bcaudit'); ?></th>
            <th class="manage-column bca_datacol"></th>
        </tr>
    </thead>


    <tbody> 
	<?php //} ?>
    <?php   
        $o = get_option( 'bca_api_content');
		if($o['body']!=''){
			$i=0;
			
			
			foreach($o['body']->data as $k => $v){

				if(isset($v->date)){
					$friendly_date = date_i18n( get_option('date_format'), strtotime($v->date));
				}else{
					$friendly_date = '';
				}

				$i++;
			?>
				<tr class="bca_row_<?php echo $i; ?>">
					<td class="bca_weekcol"><?php if(isset($v->week)){ _e('Week','bcaudit');  echo ' '.esc_html($v->week); }else{ echo '-'; } ?></td>
					<td><?php echo esc_html($friendly_date); ?></td>

					<td class="bca_datacol"><?php if(isset($v->performance->mobile->score)){ echo esc_html($v->performance->mobile->score).'%'; }else{ echo '-'; } ?></td>
					<td class="bca_datacol"><?php if(isset($v->performance->desktop->score)){ echo esc_html($v->performance->desktop->score).'%'; }else{ echo '-'; } ?></td>

					<td class="bca_datacol"><?php if(isset($v->accessibility->mobile->score)){ echo esc_html($v->accessibility->mobile->score).'%'; }else{ echo '-'; } ?></td>
					<td class="bca_datacol"><?php if(isset($v->accessibility->desktop->score)){ echo esc_html($v->accessibility->desktop->score).'%'; }else{ echo '-'; } ?></td>

					<td class="bca_datacol"><?php if(isset($v->seo->mobile->score)){ echo esc_html($v->seo->mobile->score).'%'; }else{ echo '-'; } ?></td>
					<td class="bca_datacol"><?php if(isset($v->seo->desktop->score)){ echo esc_html($v->seo->desktop->score).'%'; }else{ echo '-'; } ?></td>

					<?php

						$ar = array('performance','accessibility','seo');
						$i=0;
						$total=0;
						foreach($ar as $key){
							if(isset($v->$key->desktop->score)){
								$total = $total+$v->$key->desktop->score;
								$i++;
							}
							if(isset($v->$key->mobile->score)){
								$total = $total+$v->$key->mobile->score;
								$i++;
							}
						}

						$total = round($total/$i);

					?>


					<td class="bca_datacol"><strong><?php if(isset($total)){ echo esc_html($total).'%'; }else{ echo '-'; } ?></strong></td>



				</tr>
			<?php
				$total = '';
			}
		}else{
			_e('Please have a little patience. Server is working on your request.','bcaudit');
		}

	//if($d['body']!='' AND is_array($d['body'])){
    ?>
    </tbody>
    </table>
	</div>
	<?php //} ?>
	<?php }else{ ?>
	<br />
	<p class="bca-warning-msg"><?php _e("Your account is set to inactive on the server.",'bcaudit'); ?><br /><?php _e("Last update",'bcaudit'); ?>: <?php echo esc_html(date_i18n( 'F j, H:i',strtotime(get_transient('bca_ask_account_update')))); ?></p>
	
	<?php } ?>
		<p><?php _e("If you have made a change or just want to know how your homepage is doing at this moment? Then hit the Google Pagespeed Test button below. This will open a new window and take you to the Google Pagespeed test. The new test will not be visible in the list above since that data is fetched from our servers.",'bcaudit'); ?></p>
	<p><a href="<?php echo $siteaudit.urlencode(get_site_url()); ?>" target="_blank" class="button button-primary"><?php _e("Google Pagespeed Test",'bcaudit'); ?></a></p>

</div>

<?php
}