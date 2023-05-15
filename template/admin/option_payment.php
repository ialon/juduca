		<ul class="to-form-field-list">
			<li>
				<h5><?php esc_html_e('Booking status after successful payment','bookingo'); ?></h5>
				<span class="to-legend"><?php esc_html_e('Set selected status of the booking after the successful payment.','bookingo'); ?></span>
				<div class="to-clear-fix">
					<span class="to-legend-field"><?php esc_html_e('New status:','bookingo'); ?></span>
					<div class="to-radio-button">
						<input type="radio" value="-1" id="<?php BGCBSHelper::getFormName('booking_status_payment_success_0'); ?>" name="<?php BGCBSHelper::getFormName('booking_status_payment_success'); ?>" <?php BGCBSHelper::checkedIf($this->data['option']['booking_status_payment_success'],-1); ?>/>
						<label for="<?php BGCBSHelper::getFormName('booking_status_payment_success_0'); ?>"><?php esc_html_e('[No changes]','bookingo'); ?></label>
<?php
		foreach($this->data['dictionary']['booking_status'] as $index=>$value)
		{
?>
						<input type="radio" value="<?php echo esc_attr($index); ?>" id="<?php BGCBSHelper::getFormName('booking_status_payment_success_'.$index); ?>" name="<?php BGCBSHelper::getFormName('booking_status_payment_success'); ?>" <?php BGCBSHelper::checkedIf($this->data['option']['booking_status_payment_success'],$index); ?>/>
						<label for="<?php BGCBSHelper::getFormName('booking_status_payment_success_'.$index); ?>"><?php echo esc_html($value[0]); ?></label>
<?php		
		}
?>
					</div>
				</div>
				<div class="to-clear-fix">
					<span class="to-legend-field"><?php esc_html_e('Set the same status if the booking sum is zero:','bookingo'); ?></span>
					<div class="to-radio-button">
						<input type="radio" value="1" id="<?php BGCBSHelper::getFormName('booking_status_sum_zero_1'); ?>" name="<?php BGCBSHelper::getFormName('booking_status_sum_zero'); ?>" <?php BGCBSHelper::checkedIf($this->data['option']['booking_status_sum_zero'],1); ?>/>
						<label for="<?php BGCBSHelper::getFormName('booking_status_sum_zero_1'); ?>"><?php esc_html_e('Enable','bookingo'); ?></label>
						<input type="radio" value="0" id="<?php BGCBSHelper::getFormName('booking_status_sum_zero_0'); ?>" name="<?php BGCBSHelper::getFormName('booking_status_sum_zero'); ?>" <?php BGCBSHelper::checkedIf($this->data['option']['booking_status_sum_zero'],0); ?>/>
						<label for="<?php BGCBSHelper::getFormName('booking_status_sum_zero_0'); ?>"><?php esc_html_e('Disable','bookingo'); ?></label>
					</div>
				</div>				
			</li> 
			<li>
				<h5><?php esc_html_e('Booking statuses synchronization','bookingo'); ?></h5>
				<span class="to-legend">
					<?php esc_html_e('Synchronize booking statuses between plugin and wooCommerce.','bookingo'); ?>
				</span>
				<div class="to-radio-button">
<?php
		foreach($this->data['dictionary']['booking_status_synchronization'] as $index=>$value)
		{
?>
					<input type="radio" value="<?php echo esc_attr($index); ?>" id="<?php BGCBSHelper::getFormName('booking_status_synchronization_'.$index); ?>" name="<?php BGCBSHelper::getFormName('booking_status_synchronization'); ?>" <?php BGCBSHelper::checkedIf($this->data['option']['booking_status_synchronization'],$index); ?>/>
					<label for="<?php BGCBSHelper::getFormName('booking_status_synchronization_'.$index); ?>"><?php echo esc_html($value[0]); ?></label>
<?php		
		}
?>
				</div>
			</li>  
		</ul>

