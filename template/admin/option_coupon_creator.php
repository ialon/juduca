<?php
		$Date=new BGCBSDate();
?>
		<ul class="to-form-field-list">
			<li>
				<h5><?php esc_html_e('Count','bookingo'); ?></h5>
				<span class="to-legend">
					<?php esc_html_e('Number of coupons which should be generated.','bookingo'); ?><br/>
					<?php esc_html_e('Allowed are integer numbers from range 1-999.','bookingo'); ?>
				</span>
				<div>
					<input type="text" maxlength="3" name="<?php BGCBSHelper::getFormName('coupon_generate_count'); ?>" id="<?php BGCBSHelper::getFormName('coupon_generate_count'); ?>" value="<?php echo esc_attr($this->data['option']['coupon_generate_count']); ?>"/>
				</div>
			</li> 
			<li>
				<h5><?php esc_html_e('Usage limit','bookingo'); ?></h5>
				<span class="to-legend"><?php esc_html_e('Current usage count of the code. Allowed are integer values from range 1-9999. Leave blank for unlimited.','bookingo'); ?></span>
				<div>
					<input type="text" maxlength="4" name="<?php BGCBSHelper::getFormName('coupon_generate_usage_limit'); ?>" id="<?php BGCBSHelper::getFormName('coupon_generate_usage_limit'); ?>" value="<?php echo esc_attr($this->data['option']['coupon_generate_usage_limit']); ?>"/>
				</div>
			</li>							 
			<li>
				<h5><?php esc_html_e('Percentage discount','bookingo'); ?></h5>
				<span class="to-legend">
					<?php esc_html_e('Percentage discount. Allowed are integer numbers from 1-99.','bookingo'); ?></span>
				<div>
					<input type="text" maxlength="2" name="<?php BGCBSHelper::getFormName('coupon_generate_discount_percentage'); ?>" id="<?php BGCBSHelper::getFormName('coupon_generate_discount_percentage'); ?>" value="<?php echo esc_attr($this->data['option']['coupon_generate_discount_percentage']); ?>"/>
				</div>
			</li>   
			<li>
				<h5><?php esc_html_e('Fixed discount','bookingo'); ?></h5>
				<span class="to-legend"><?php esc_html_e('Fixed discount.','bookingo'); ?></span>
				<div>
					<input type="text" maxlength="12" name="<?php BGCBSHelper::getFormName('coupon_generate_discount_fixed'); ?>" id="<?php BGCBSHelper::getFormName('coupon_generate_discount_fixed'); ?>" value="<?php echo esc_attr($this->data['option']['coupon_generate_discount_fixed']); ?>"/>
				</div>
			</li>	 
			<li>
				<h5><?php esc_html_e('Active from','bookingo'); ?></h5>
				<span class="to-legend"><?php esc_html_e('Start date. Leave blank for no start date.','bookingo'); ?></span>
				<div>
					<input type="text" class="to-datepicker-custom" name="<?php BGCBSHelper::getFormName('coupon_generate_active_date_start'); ?>" id="<?php BGCBSHelper::getFormName('coupon_generate_active_date_start'); ?>" value="<?php echo esc_attr($this->data['option']['coupon_generate_active_date_start']); ?>"/>
				</div>
			</li>  
			<li>
				<h5><?php esc_html_e('Active to','bookingo'); ?></h5>
				<span class="to-legend"><?php esc_html_e('Stop date. Leave blank for no start date.','bookingo'); ?></span>
				<div>
					<input type="text" class="to-datepicker-custom" name="<?php BGCBSHelper::getFormName('coupon_generate_active_date_stop'); ?>" id="<?php BGCBSHelper::getFormName('coupon_generate_active_date_stop'); ?>" value="<?php echo esc_attr($this->data['option']['coupon_generate_active_date_stop']); ?>"/>
				</div>
			</li>
			<li>
				<input type="button" name="<?php BGCBSHelper::getFormName('create_coupon_code'); ?>" id="<?php BGCBSHelper::getFormName('create_coupon_code'); ?>" class="to-button to-margin-0" value="<?php esc_attr_e('Create coupons','bookingo'); ?>"/>
			</li>
		</ul>
<?php
		wp_add_inline_script('bgcbs-admin',
		'
			jQuery(document).ready(function($)
			{	
				var timeFormat=\''.BGCBSOption::getOption('time_format').'\';
				var dateFormat=\''.BGCBSJQueryUIDatePicker::convertDateFormat(BGCBSOption::getOption('date_format')).'\';

				toCreateCustomDateTimePicker(dateFormat,timeFormat);

				$(\'#'.BGCBSHelper::getFormName('create_coupon_code',false).'\').bind(\'click\',function(e) 
				{
					e.preventDefault();
					$(\'#action\').val(\''.PLUGIN_BGCBS_CONTEXT.'_option_page_create_coupon_code\');
					$(\'#to_form\').submit();
					$(\'#action\').val(\''.PLUGIN_BGCBS_CONTEXT.'_option_page_save\');
				});
			});
		');