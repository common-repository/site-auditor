<?php

function bca_dashboard_pag(){
	
	// the google lighthouse pagespeed check url
	$siteaudit = 'https://developers.google.com/speed/pagespeed/insights/?url=';
	
?>
<div class="wrap bca_wrap">

    <h1><?php _e("WP Audit",'bcaudit'); ?></h1> 
	<h2><?php _e("Dashboard",'bcaudit'); ?></h2> 
    <br />
	

	<div id="bca_stats">
		

		
		<?php if(get_option('bca_audit_api')==1){ 
		/////////// PAGESPEED DATA BLOCK
		?>
			<div id="bca_pagespeed_box" class="bca_box">

				<div class="bca_box_img"><img src="<?php echo plugin_dir_url(__DIR__); ?>assets/img/ic-speed.png" alt="pagespeed icon" /></div>
				<div class="bca_box_content">
					<h2><?php _e("Google Pagespeed",'bcaudit'); ?></h2>
					<p><?php _e("A Pagespeed test can tell you a lot about your websites health. The Google Pagespeed Records is a Weekly Pagespeed check and is ran on our servers so your visitors won't notice it. If you like to read a full report on your website then click the Google Pagespeed Test button or activate the extra link in the page/post-edit list below the title.",'bcaudit'); ?></p>
				</div>
				<div class="bca_box_buttons">
					<a href="<?php echo get_site_url(); ?>/wp-admin/admin.php?page=bca_pagespeed" class="button button-primary"><?php _e("Google Pagespeed Records",'bcaudit'); ?></a>
					<a href="<?php echo $siteaudit.urlencode(get_site_url()); ?>" target="_blank" class="button"><?php _e("Google Pagespeed Test",'bcaudit'); ?></a>
					<?php 
					if(get_transient("bca_api_content_request")!=''){
						$last_fetch_date = date_i18n( get_option('date_format'), strtotime(get_transient("bca_api_content_request"))).' '.date_i18n( get_option('time_format'), strtotime(get_transient("bca_api_content_request")));
						?>
						<p class="bca_update"><?php _e("Refreshed: ",'bcaudit'); ?><?php echo $last_fetch_date; ?></p>
					<?php } ?>
				</div>
			</div>
		<?php } ?>
		<?php if(get_option('bca_404_active')==1){ 
		/////////// 404 DATA BLOCK
		?>
			<div id="bca_404_box" class="bca_box">
		
				<div class="bca_box_img"><img src="<?php echo plugin_dir_url(__DIR__); ?>assets/img/ic-404.png" alt="404 icon" /></div>
				<div class="bca_box_content">
					<h2><?php _e("404 Error Logging",'bcaudit'); ?></h2>
					<p><?php _e("Know where your website displays it's 404 errors. This tool logs them so you can take a look at it later. Fully adjustable on the settings page.",'bcaudit'); ?></p>
				</div>
				<div class="bca_box_buttons">
					<a href="<?php echo get_site_url(); ?>/wp-admin/admin.php?page=bca_404" class="button button-primary"><?php _e("404 Error Records",'bcaudit'); ?></a>
				</div>
				
				
			</div>
		<?php } ?>
		
			<div id="bca_settings_box" class="bca_box">

				<div class="bca_box_img"><img src="<?php echo plugin_dir_url(__DIR__); ?>assets/img/ic-settings.png" alt="settings icon" /></div>
				<div class="bca_box_content">
					<h2><?php _e("WP Audit Settings",'bcaudit'); ?></h2>

				</div>
				<div class="bca_box_buttons">
					<a href="<?php echo get_site_url(); ?>/wp-admin/options-general.php?page=bca_settings" class="button"><?php _e("Settings",'bcaudit'); ?></a>
				</div>
			</div>
	</div>
	
</div>
<?php
	
}

?>