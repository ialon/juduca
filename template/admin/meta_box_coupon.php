<?php 
		echo $this->data['nonce']; 
		$Date=new BGCBSDate();
?>	
		<div class="to">
			<div class="ui-tabs">
				<ul>
					<li><a href="#meta-box-coupon-1"><?php esc_html_e('General','bookingo'); ?></a></li>
				</ul>
				<div id="meta-box-coupon-1">
					<ul class="to-form-field-list">
						<?php echo BGCBSHelper::createPostIdField(esc_html__('Coupon ID','bookingo')); ?>
						<li>
							<h5><?php esc_html_e('Coupon code','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Unique, coupon code.','bookingo'); ?></span>
							<div>
								<input type="text" maxlength="32" name="<?php BGCBSHelper::getFormName('code'); ?>" id="<?php BGCBSHelper::getFormName('code'); ?>" value="<?php echo esc_attr($this->data['meta']['code']); ?>"/>
							</div>
						</li>  
						<li>
							<h5><?php esc_html_e('Usage count','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Current usage count of the coupon.','bookingo'); ?></span>
							<div class="to-field-disabled">
								<?php echo esc_html($this->data['meta']['usage_count']); ?>
							</div>
						</li>  
						<li>
							<h5><?php esc_html_e('Usage limit','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Usage limit of the coupon. Allowed are integer values from range 1-9999. Leave blank for unlimited.','bookingo'); ?></span>
							<div>
								<input type="text" maxlength="4" name="<?php BGCBSHelper::getFormName('usage_limit'); ?>" id="<?php BGCBSHelper::getFormName('usage_limit'); ?>" value="<?php echo esc_attr($this->data['meta']['usage_limit']); ?>"/>
							</div>
						</li>  
						<li>
							<h5><?php esc_html_e('Active from','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Start date. Leave blank if there is no start date.','bookingo'); ?></span>
							<div>
								<input type="text" class="to-datepicker-custom" name="<?php BGCBSHelper::getFormName('active_date_start'); ?>" id="<?php BGCBSHelper::getFormName('active_date_start'); ?>" value="<?php echo esc_html($Date->formatDateToDisplay($this->data['meta']['active_date_start'])); ?>"/>
							</div>
						</li>  
						<li>
							<h5><?php esc_html_e('Active to','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Stop date. Leave blank if there is no stop date.','bookingo'); ?></span>
							<div>
								<input type="text" class="to-datepicker-custom" name="<?php BGCBSHelper::getFormName('active_date_stop'); ?>" id="<?php BGCBSHelper::getFormName('active_date_stop'); ?>" value="<?php echo esc_html($Date->formatDateToDisplay($this->data['meta']['active_date_stop'])); ?>"/>
							</div>
						</li>  						
						<li>
							<h5><?php esc_html_e('Percentage discount','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Percentage discount. Allowed are integer numbers from 0-99.','bookingo'); ?></span>
							<div>
								<input type="text" maxlength="2" name="<?php BGCBSHelper::getFormName('discount_percentage'); ?>" id="<?php BGCBSHelper::getFormName('discount_percentage'); ?>" value="<?php echo esc_attr($this->data['meta']['discount_percentage']); ?>"/>
							</div>
						</li>	 
						<li>
							<h5><?php esc_html_e('Fixed discount','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Fixed discount. This discount is used only if percentage discount is set to 0.','bookingo'); ?></span>
							<div>
								<input type="text" maxlength="12" name="<?php BGCBSHelper::getFormName('discount_fixed'); ?>" id="<?php BGCBSHelper::getFormName('discount_fixed'); ?>" value="<?php echo esc_attr($this->data['meta']['discount_fixed']); ?>"/>
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

				var timeFormat=\''.BGCBSOption::getOption('time_format').'\';
				var dateFormat=\''.BGCBSJQueryUIDatePicker::convertDateFormat(BGCBSOption::getOption('date_format')).'\';

				toCreateCustomDateTimePicker(dateFormat,timeFormat);
			});
		');