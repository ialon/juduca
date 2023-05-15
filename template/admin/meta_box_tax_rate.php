<?php 
		echo $this->data['nonce']; 
?>	
		<div class="to">
			<div class="ui-tabs">
				<ul>
					<li><a href="#meta-box-tax-rate-1"><?php esc_html_e('General','bookingo'); ?></a></li>
				</ul>
				<div id="meta-box-tax-rate-1">
					<ul class="to-form-field-list">
						<?php echo BGCBSHelper::createPostIdField(esc_html__('Tax rate ID','bookingo')); ?>
						<li>
							<h5><?php esc_html_e('Value','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Percentage value of tax rate. Floating point values are allowed, up to two decimal places in the range 0-100.','bookingo'); ?></span>
							<div>
								<input type="text" maxlength="5" name="<?php BGCBSHelper::getFormName('tax_rate_value'); ?>" id="<?php BGCBSHelper::getFormName('tax_rate_value'); ?>" value="<?php echo esc_attr($this->data['meta']['tax_rate_value']); ?>"/>
							</div>
						</li>  
						<li>
							<h5><?php esc_html_e('Default tax rate','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Mark this tax rate as default.','bookingo'); ?></span>
							<div class="to-radio-button">
								<input type="radio" value="1" id="<?php BGCBSHelper::getFormName('tax_rate_default_1'); ?>" name="<?php BGCBSHelper::getFormName('tax_rate_default'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['tax_rate_default'],1); ?>/>
								<label for="<?php BGCBSHelper::getFormName('tax_rate_default_1'); ?>"><?php esc_html_e('Yes','bookingo'); ?></label>
								<input type="radio" value="0" id="<?php BGCBSHelper::getFormName('tax_rate_default_0'); ?>" name="<?php BGCBSHelper::getFormName('tax_rate_default'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['tax_rate_default'],0); ?>/>
								<label for="<?php BGCBSHelper::getFormName('tax_rate_default_0'); ?>"><?php esc_html_e('No','bookingo'); ?></label>
							</div>
						</li>						
					</ul>
				</div>
			</div>
		</div>
<?php
		wp_add_inline_script('bgcbs-admin',
		'
			jQuery(document).ready(function($)
			{	
				$(\'.to\').themeOptionElement({init:true});
			});
		');