
		<div class="to to-to" style="display:none">

			<form name="to_form" id="to_form" method="POST" action="#">

				<div id="to_notice"></div> 

				<div class="to-header to-clear-fix">

					<div class="to-header-left">

						<div>
							<h3><?php esc_html_e('QuanticaLabs','bookingo'); ?></h3>
							<h6><?php esc_html_e('Plugin Options','bookingo'); ?></h6>
						</div>

					</div>

					<div class="to-header-right">

						<div>
							<h3>
								<?php esc_html_e('Bookingo - Course Booking System for WordPress','bookingo'); ?>
							</h3>
							<h6>
								<?php echo sprintf(esc_html__('WordPress Plugin ver. %s','bookingo'),PLUGIN_BGCBS_VERSION); ?>
							</h6>
							&nbsp;&nbsp;
							<a href="<?php echo esc_url('http://support.quanticalabs.com'); ?>" target="_blank"><?php esc_html_e('Support Forum','bookingo'); ?></a>
							<a href="<?php echo esc_url('https://codecanyon.net/user/quanticalabs'); ?>" target="_blank"><?php esc_html_e('Plugin site','bookingo'); ?></a>
						</div>

						<a href="<?php echo esc_url('http://quanticalabs.com'); ?>" class="to-header-right-logo"></a>

					</div>

				</div>

				<div class="to-content to-clear-fix">

					<div class="to-content-left">

						<ul class="to-menu" id="to_menu">
							<li>
								<a href="#general"><?php esc_html_e('General','bookingo'); ?><span></span></a>
							</li>
							<li>
								<a href="#import_demo"><?php esc_html_e('Import demo','bookingo'); ?><span></span></a>
							</li>
							<li>
								<a href="#payment"><?php esc_html_e('Payments','bookingo'); ?><span></span></a>
							</li>
							<li>
								<a href="#coupon_creator"><?php esc_html_e('Coupons creator','bookingo'); ?><span></span></a>
							</li>
							<li>
								<a href="#exchange_rate"><?php esc_html_e('Exchange rates','bookingo'); ?><span></span></a>
							</li>
							<li>
								<a href="#log_manager"><?php esc_html_e('Log manager','bookingo'); ?><span></span></a>
								<ul>
									<li><a href="#log_manager_mail"><?php esc_html_e('Mail','bookingo'); ?></a></li>
                                    <li><a href="#log_manager_stripe"><?php esc_html_e('Stripe','bookingo'); ?></a></li>
									<li><a href="#log_manager_paypal"><?php esc_html_e('PayPal','bookingo'); ?></a></li>
									<li><a href="#log_manager_twilio"><?php esc_html_e('Twilio','bookingo'); ?></a></li>
									<li><a href="#log_manager_nexmo"><?php esc_html_e('Vonage','bookingo'); ?></a></li>
									<li><a href="#log_manager_fixerio"><?php esc_html_e('Fixer.io','bookingo'); ?></a></li>
									<li><a href="#log_manager_telegram"><?php esc_html_e('Telegram','bookingo'); ?></a></li>
								</ul>		
							</li>
						</ul>

					</div>

					<div class="to-content-right" id="to_panel">
<?php
		$content=array
		(
			'general',
			'import_demo',
			'payment',
			'coupon_creator',
			'exchange_rate',
			'log_manager_mail',
			'log_manager_stripe',
			'log_manager_paypal',
			'log_manager_nexmo',
			'log_manager_fixerio',
			'log_manager_twilio',
			'log_manager_telegram'
		);
		
		foreach($content as $value)
		{
?>
						<div id="<?php echo $value; ?>">
<?php
			echo BGCBSTemplate::outputS($this->data,PLUGIN_BGCBS_TEMPLATE_PATH.'admin/option_'.$value.'.php');
?>
						</div>
<?php
		}
?>
					</div>

				</div>

				<div class="to-footer to-clear-fix">

					<div class="to-footer-left">

						<ul class="to-social-list">
							<li><a href="<?php echo esc_url('http://themeforest.net/user/QuanticaLabs?ref=quanticalabs'); ?>" class="to-social-list-envato" title="<?php esc_attr_e('Envato','bookingo'); ?>"></a></li>
							<li><a href="<?php echo esc_url('http://www.facebook.com/QuanticaLabs'); ?>" class="to-social-list-facebook" title="<?php esc_attr_e('Facebook','bookingo'); ?>"></a></li>
							<li><a href="<?php echo esc_url('https://twitter.com/quanticalabs'); ?>" class="to-social-list-twitter" title="<?php esc_attr_e('Twitter','bookingo'); ?>"></a></li>
							<li><a href="<?php echo esc_url('http://quanticalabs.tumblr.com/'); ?>" class="to-social-list-tumblr" title="<?php esc_attr_e('Tumblr','bookingo'); ?>"></a></li>
						</ul>

					</div>
					
					<div class="to-footer-right">
						<input type="submit" value="<?php esc_attr_e('Save changes','bookingo'); ?>" name="Submit" id="Submit" class="to-button"/>
					</div>			
				
				</div>
				
				<input type="hidden" name="action" id="action" value="<?php echo esc_attr(PLUGIN_BGCBS_CONTEXT.'_option_page_save'); ?>" />
				
<?php
		wp_add_inline_script('bgcbs-admin',
		'
			jQuery(document).ready(function($)
			{
				$(\'.to\').themeOption({afterSubmit:function(response)
				{
					if(typeof(response.global.reload)!=\'undefined\')
						location.reload();

					return(false);
				}});

				var element=$(\'.to\').themeOptionElement({init:true});

				element.bindBrowseMedia(\'input[name="bgcbs_logo_browse"]\');
				element.bindBrowseMedia(\'input[name="bgcbs_attachment_woocommerce_email_browse"]\',false,2,\'\');
			});
		');
?>
			</form>
			
		</div>