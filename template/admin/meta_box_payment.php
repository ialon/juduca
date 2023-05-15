<?php
		echo $this->data['nonce']; 

		$Validation=new BGCBSValidation();
?>		
		<div class="to">
			<div class="ui-tabs">
				<ul>
					<li><a href="#meta-box-payment-1"><?php esc_html_e('General','bookingo'); ?></a></li>
					<li><a href="#meta-box-payment-2"><?php esc_html_e('Custom','bookingo'); ?></a></li>
					<li><a href="#meta-box-payment-3"><?php esc_html_e('Stripe','bookingo'); ?></a></li>
					<li><a href="#meta-box-payment-4"><?php esc_html_e('PayPal','bookingo'); ?></a></li>
				</ul>
				<div id="meta-box-payment-1">
					<ul class="to-form-field-list">
						<?php echo BGCBSHelper::createPostIdField(esc_html__('Payment ID','bookingo')); ?>		
						<li>
							<h5><?php esc_html_e('Type','bookingo'); ?></h5>
							<span class="to-legend">
								<?php esc_html_e('Select payment type.','bookingo'); ?><br/>
								<?php esc_html_e('If you choose "Stripe" or "PayPal" you have to enter all needed details in related tabs..','bookingo'); ?>
							</span>
							<div class="to-radio-button">
<?php
		foreach($this->data['dictionary']['payment_type'] as $index=>$value)
		{
?>
								<input type="radio" value="<?php echo esc_attr($index); ?>" id="<?php BGCBSHelper::getFormName('payment_type_'.$index); ?>" name="<?php BGCBSHelper::getFormName('payment_type'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['payment_type'],$index); ?>/>
								<label for="<?php BGCBSHelper::getFormName('payment_type_'.$index); ?>"><?php echo esc_html($value[0]); ?></label>							
<?php		
		}
?>
							</div>	
						</li>
					</ul>
				</div>
				<div id="meta-box-payment-2">
					<div class="to-notice-small to-notice-small-error">
						<?php esc_html_e('Enter all "Custom" details if you selected this payment type in the "General" tab.') ?>
					</div>
					<ul class="to-form-field-list">					
						<li>	
							<h5><?php esc_html_e('"Success" URL address','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Enter "Success" URL address.','bookingo'); ?></span>
							<div class="to-clear-fix">
								<input type="text" name="<?php BGCBSHelper::getFormName('payment_custom_success_url_address'); ?>" value="<?php echo esc_attr($this->data['meta']['payment_custom_success_url_address']); ?>"/>
							</div>
						</li>
					</ul>
				</div>
				<div id="meta-box-payment-3">
					<div class="to-notice-small to-notice-small-error">
						<?php esc_html_e('Enter all "Stripe" details if you selected this payment type in the "General" tab.') ?>
					</div>
					<ul class="to-form-field-list">					
						<li>
							<h5><?php esc_html_e('Secret API key','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Enter secret API key.','bookingo'); ?></span>
							<div class="to-clear-fix">
								<input type="text" name="<?php BGCBSHelper::getFormName('payment_stripe_api_key_secret'); ?>" value="<?php echo esc_attr($this->data['meta']['payment_stripe_api_key_secret']); ?>"/>
							</div>
						</li>
						<li>
							<h5><?php esc_html_e('Publishable API key','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Enter publishable API key.','bookingo'); ?></span>
							<div class="to-clear-fix">
								<input type="text" name="<?php BGCBSHelper::getFormName('payment_stripe_api_key_publishable'); ?>" value="<?php echo esc_attr($this->data['meta']['payment_stripe_api_key_publishable']); ?>"/>
							</div>
						</li>
						<li>
							<h5><?php esc_html_e('Payment methods','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Select at least one payment method - you need to set up each of them in your "Stripe" dashboard under "Settings / Payment methods":','bookingo'); ?></span>
							<div class="to-checkbox-button">
<?php
		foreach($this->data['dictionary']['payment_stripe_method'] as $index=>$value)
		{
?>
								<input type="checkbox" value="<?php echo esc_attr($index); ?>" id="<?php BGCBSHelper::getFormName('payment_stripe_method_'.$index); ?>" name="<?php BGCBSHelper::getFormName('payment_stripe_method[]'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['payment_stripe_method'],$index); ?>/>
								<label for="<?php BGCBSHelper::getFormName('payment_stripe_method_'.$index); ?>"><?php echo esc_html($value[0]); ?></label>							
<?php		
		}
?>
							</div>	
						</li>
						<li>
							<h5><?php esc_html_e('Product ID','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Product ID.','bookingo'); ?></span>
							<div class="to-clear-fix">
								<input type="text" name="<?php BGCBSHelper::getFormName('payment_stripe_product_id'); ?>" value="<?php echo esc_attr($this->data['meta']['payment_stripe_product_id']); ?>"/>
							</div>
						</li>
						<li>	
							<h5><?php esc_html_e('"Success" URL address','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Enter "Success" URL address.','bookingo'); ?></span>
							<div class="to-clear-fix">
								<input type="text" name="<?php BGCBSHelper::getFormName('payment_stripe_success_url_address'); ?>" value="<?php echo esc_attr($this->data['meta']['payment_stripe_success_url_address']); ?>"/>
							</div>
						</li>
						<li>
							<h5><?php esc_html_e('"Cancel" URL address','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Enter "Cancel" URL address.','bookingo'); ?></span>
							<div class="to-clear-fix">
								<input type="text" name="<?php BGCBSHelper::getFormName('payment_stripe_cancel_url_address'); ?>" value="<?php echo esc_attr($this->data['meta']['payment_stripe_cancel_url_address']); ?>"/>
							</div>	
						</li>
					</ul>
				</div>
				<div id="meta-box-payment-4">
					<div class="to-notice-small to-notice-small-error">
						<?php esc_html_e('Enter all "PayPal" details if you selected this payment type in the "General" tab.') ?>
					</div>
					<ul class="to-form-field-list">
						<li>
							<h5><?php esc_html_e('E-mail address','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Enter your PayPal e-mail address.','bookingo'); ?></span>
							<div class="to-clear-fix">
								<input type="text" name="<?php BGCBSHelper::getFormName('payment_paypal_email_address'); ?>" value="<?php echo esc_attr($this->data['meta']['payment_paypal_email_address']); ?>"/>
							</div>
						</li>
						<li>
							<h5><?php esc_html_e('Sandbox mode','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Enable or disabled testing mode.','bookingo'); ?></span>
							<div class="to-radio-button">
								<input type="radio" value="1" id="<?php BGCBSHelper::getFormName('payment_paypal_sandbox_mode_enable_1'); ?>" name="<?php BGCBSHelper::getFormName('payment_paypal_sandbox_mode_enable'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['payment_paypal_sandbox_mode_enable'],1); ?>/>
								<label for="<?php BGCBSHelper::getFormName('payment_paypal_sandbox_mode_enable_1'); ?>"><?php esc_html_e('Enable','bookingo'); ?></label>
								<input type="radio" value="0" id="<?php BGCBSHelper::getFormName('payment_paypal_sandbox_mode_enable_0'); ?>" name="<?php BGCBSHelper::getFormName('payment_paypal_sandbox_mode_enable'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['payment_paypal_sandbox_mode_enable'],0); ?>/>
								<label for="<?php BGCBSHelper::getFormName('payment_paypal_sandbox_mode_enable_0'); ?>"><?php esc_html_e('Disable','bookingo'); ?></label>
							</div>
						</li>
						<li>	
							<h5><?php esc_html_e('"Success" URL address','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Enter "Success" URL address.','bookingo'); ?></span>
							<div class="to-clear-fix">
								<input type="text" name="<?php BGCBSHelper::getFormName('payment_paypal_success_url_address'); ?>" value="<?php echo esc_attr($this->data['meta']['payment_paypal_success_url_address']); ?>"/>
							</div>
						</li>
						<li>	
							<h5><?php esc_html_e('"Cancel" URL address:','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Enter "Cancel" URL address.','bookingo'); ?></span>
							<div class="to-clear-fix">
								<input type="text" name="<?php BGCBSHelper::getFormName('payment_paypal_cancel_url_address'); ?>" value="<?php echo esc_attr($this->data['meta']['payment_paypal_cancel_url_address']); ?>"/>
							</div>
						</li>
					</ul>
				</div>
				<div id="meta-box-payment-3">
					
				</div>
			</div>	
		</div>
<?php
		wp_add_inline_script('bgcbs-admin',
		'
			jQuery(document).ready(function($)
			{	
				var element=$(\'.to\').themeOptionElement({init:true});
			});
		');