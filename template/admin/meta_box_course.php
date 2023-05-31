<?php
		echo $this->data['nonce']; 

		$Date=new BGCBSDate();
		$Validation=new BGCBSValidation();
?>		
		<div class="to">
			<div class="ui-tabs">
				<ul>
					<li><a href="#meta-box-course-1"><?php esc_html_e('General','bookingo'); ?></a></li>
					<!-- <li><a href="#meta-box-course-2"><?php esc_html_e('Payments','bookingo'); ?></a></li> -->
					<!-- <li><a href="#meta-box-course-3"><?php esc_html_e('Notifications','bookingo'); ?></a></li> -->
					<li><a href="#meta-box-course-4"><?php esc_html_e('Form Elements','bookingo'); ?></a></li>
					<li><a href="#meta-box-course-5"><?php esc_html_e('Agreements','bookingo'); ?></a></li>
					<li><a href="#meta-box-course-6"><?php esc_html_e('Other','bookingo'); ?></a></li>
				</ul>
				<div id="meta-box-course-1">
<?php
		if((!count($this->data['meta']['course_group_id'])) || (in_array(-1,$this->data['meta']['course_group_id'])))
		{
?>
					<div class="to-notice-small to-notice-small-error">
						<?php esc_html_e('You have to assign at least one group to this course. Otherwise booking form will not be displayed.', 'bookingo') ?>
					</div>
<?php
		}
?>
					<ul class="to-form-field-list">
						<li>
							<h5>
								<?php esc_html_e('Course groups','bookingo'); ?>
								<?php echo BGCBSHelper::createAddPostLink(BGCBSCourseGroup::getCPTName(),'Add new'); ?>
							</h5>
							<span class="to-legend">
								<?php esc_html_e('Select at least one course group. Course group can be assigned to the one course only.','bookingo'); ?>
								
							</span>
							<div class="to-checkbox-button">
								<input type="checkbox" value="-1" id="<?php BGCBSHelper::getFormName('course_group_id_0'); ?>" name="<?php BGCBSHelper::getFormName('course_group_id[]'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['course_group_id'],-1); ?>/>
								<label for="<?php BGCBSHelper::getFormName('course_group_id_0'); ?>"><?php esc_html_e('- None -','bookingo') ?></label>
<?php
		foreach($this->data['dictionary']['course_group'] as $index=>$value)
		{
?>
								<input type="checkbox" value="<?php echo esc_attr($index); ?>" id="<?php BGCBSHelper::getFormName('course_group_id_'.$index); ?>" name="<?php BGCBSHelper::getFormName('course_group_id[]'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['course_group_id'],$index); ?>/>
								<label for="<?php BGCBSHelper::getFormName('course_group_id_'.$index); ?>"><?php echo esc_html($value['post']->post_title); ?></label>
<?php		
		}
?>
							</div>
						</li>
 						<li>
							<h5><?php esc_html_e('Registration start','bookingo'); ?></h5>
							<span class="to-legend">
								<?php esc_html_e('Set date/time of registration start.','bookingo'); ?><br/>
								<?php esc_html_e('Before this date/time form will not be available for participants.','bookingo'); ?><br/>
							</span>
							<div>
								<span class="to-legend-field"><?php esc_html_e('Date.','bookingo'); ?></span>
								<input class="to-datepicker-custom" type="text" name="<?php BGCBSHelper::getFormName('registration_period_start_date'); ?>" value="<?php echo esc_attr($this->data['meta']['registration_period_start_date']); ?>"/>
							</div>
							<div>
								<span class="to-legend-field"><?php esc_html_e('Time.','bookingo'); ?></span>
								<input class="to-timepicker-custom" type="text" name="<?php BGCBSHelper::getFormName('registration_period_start_time'); ?>" value="<?php echo esc_attr($this->data['meta']['registration_period_start_time']); ?>"/>
							</div>
						</li>
 						<li>
							<h5><?php esc_html_e('Registration end','bookingo'); ?></h5>
							<span class="to-legend">
								<?php esc_html_e('Set date/time of registration end.','bookingo'); ?><br/>
								<?php esc_html_e('After this date/time form will not be available for participants.','bookingo'); ?><br/>
							</span>
							<div>
								<span class="to-legend-field"><?php esc_html_e('Date.','bookingo'); ?></span>
								<input class="to-datepicker-custom" type="text" name="<?php BGCBSHelper::getFormName('registration_period_end_date'); ?>" value="<?php echo esc_attr($this->data['meta']['registration_period_end_date']); ?>"/>
							</div>
							<div>
								<span class="to-legend-field"><?php esc_html_e('Time.','bookingo'); ?></span>
								<input class="to-timepicker-custom" type="text" name="<?php BGCBSHelper::getFormName('registration_period_end_time'); ?>" value="<?php echo esc_attr($this->data['meta']['registration_period_end_time']); ?>"/>
							</div>
						</li>
						<li>
							<h5><?php esc_html_e('Applicant data','bookingo'); ?></h5>
							<span class="to-legend">
								<?php esc_html_e('Define whether the plugin has to collect data about applicant.','bookingo'); ?><br/>
								<?php esc_html_e('If this option is enable, section "Applicant data" will be visible in the booking form.','bookingo'); ?>
							</span>
							<div class="to-radio-button">
								<input type="radio" value="1" id="<?php BGCBSHelper::getFormName('applicant_data_enable_1'); ?>" name="<?php BGCBSHelper::getFormName('applicant_data_enable'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['applicant_data_enable'],1); ?>/>
								<label for="<?php BGCBSHelper::getFormName('applicant_data_enable_1'); ?>"><?php esc_html_e('Enable','bookingo'); ?></label>
								<input type="radio" value="0" id="<?php BGCBSHelper::getFormName('applicant_data_enable_0'); ?>" name="<?php BGCBSHelper::getFormName('applicant_data_enable'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['applicant_data_enable'],0); ?>/>
								<label for="<?php BGCBSHelper::getFormName('applicant_data_enable_0'); ?>"><?php esc_html_e('Disable','bookingo'); ?></label>
							</div>								
						</li>
					</ul>
				</div>
				<!-- <div id="meta-box-course-2">
					<ul class="to-form-field-list">
						<li>
							<h5>
								<?php esc_html_e('Payments','bookingo'); ?>
								<?php echo BGCBSHelper::createAddPostLink(BGCBSPayment::getCPTName(),'Add new'); ?>
							</h5>
							<span class="to-legend">
								<?php esc_html_e('Select payment methods available in this course.','bookingo'); ?>
							</span>
							<div class="to-checkbox-button">
								<input type="checkbox" value="-1" id="<?php BGCBSHelper::getFormName('payment_id_0'); ?>" name="<?php BGCBSHelper::getFormName('payment_id[]'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['payment_id'],-1); ?>/>
								<label for="<?php BGCBSHelper::getFormName('payment_id_0'); ?>"><?php esc_html_e('- None -','bookingo') ?></label>
<?php
		foreach($this->data['dictionary']['payment'] as $index=>$value)
		{
?>
								<input type="checkbox" value="<?php echo esc_attr($index); ?>" id="<?php BGCBSHelper::getFormName('payment_id_'.$index); ?>" name="<?php BGCBSHelper::getFormName('payment_id[]'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['payment_id'],$index); ?>/>
								<label for="<?php BGCBSHelper::getFormName('payment_id_'.$index); ?>"><?php echo esc_html($value['post']->post_title); ?></label>
<?php		
		}
?>
							</div>
						</li>						
					</ul>				
				</div> -->
				<!-- <div id="meta-box-course-3">
					<div class="ui-tabs">
						<ul>
						   <li><a href="#meta-box-course-2-1"><?php esc_html_e('E-mail','bookingo'); ?></a></li>
						   <li><a href="#meta-box-course-2-2"><?php esc_html_e('Twilio','bookingo'); ?></a></li>
						   <li><a href="#meta-box-course-2-3"><?php esc_html_e('Vonage','bookingo'); ?></a></li>
						   <li><a href="#meta-box-course-2-4"><?php esc_html_e('Telegram','bookingo'); ?></a></li>
					   </ul>
					   <div id="meta-box-course-2-1">
						   <ul class="to-form-field-list">
								<li>
									<h5>
										<?php esc_html_e('Sender e-mail account','bookingo'); ?>
										<?php echo BGCBSHelper::createAddPostLink(BGCBSEmailAccount::getCPTName(),'Add new'); ?>
										<?php echo BGCBSHelper::createEditPostLink('Edit'); ?>
									</h5>
									<span class="to-legend-field"><?php esc_html_e('Select sender e-mail account.','bookingo'); ?></span>
									<div class="to-clear-fix">
										<select name="<?php BGCBSHelper::getFormName('booking_new_sender_email_account_id'); ?>" id="<?php BGCBSHelper::getFormName('booking_new_sender_email_account_id'); ?>">
<?php
		echo '<option value="-1" '.(BGCBSHelper::selectedIf($this->data['meta']['booking_new_sender_email_account_id'],-1,false)).'>'.esc_html__(' - Not set -','bookingo').'</option>';
		foreach($this->data['dictionary']['email_account'] as $index=>$value)
			echo '<option value="'.esc_attr($index).'" '.(BGCBSHelper::selectedIf($this->data['meta']['booking_new_sender_email_account_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
?>
										</select>
									</div>
								</li>
								<li>
									<h5><?php esc_html_e('Recipients','bookingo'); ?></h5>
									<span class="to-legend-field"><?php esc_html_e('List of recipients e-mail addresses (separated by semicolon).','bookingo'); ?></span>
									<div class="to-clear-fix">
										<input type="text" name="<?php BGCBSHelper::getFormName('booking_new_recipient_email_address'); ?>" value="<?php echo esc_attr($this->data['meta']['booking_new_recipient_email_address']); ?>"/>
									</div>
								</li>
								<li>
									<h5><?php esc_html_e('Sending an e-mail to the customers','bookingo'); ?></h5>
									<span class="to-legend-field"><?php esc_html_e('Define whether the plugin has to sent e-mail messages about new booking to the customers.','bookingo'); ?></span>
									<div class="to-radio-button">
										<input type="radio" value="1" id="<?php BGCBSHelper::getFormName('email_notification_booking_new_client_enable_1'); ?>" name="<?php BGCBSHelper::getFormName('email_notification_booking_new_client_enable'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['email_notification_booking_new_client_enable'],1); ?>/>
										<label for="<?php BGCBSHelper::getFormName('email_notification_booking_new_client_enable_1'); ?>"><?php esc_html_e('Enable','bookingo'); ?></label>
										<input type="radio" value="0" id="<?php BGCBSHelper::getFormName('email_notification_booking_new_client_enable_0'); ?>" name="<?php BGCBSHelper::getFormName('email_notification_booking_new_client_enable'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['email_notification_booking_new_client_enable'],0); ?>/>
										<label for="<?php BGCBSHelper::getFormName('email_notification_booking_new_client_enable_0'); ?>"><?php esc_html_e('Disable','bookingo'); ?></label>
									</div>								
								</li>
								<li>
									<h5><?php esc_html_e('Sending an e-mail to defined recipients','bookingo'); ?></h5>
									<span class="to-legend-field"><?php esc_html_e('Define whether the plugin has to sent e-mail messages about new booking to addresses defined on recipient list.','bookingo'); ?></span>
									<div class="to-radio-button">
										<input type="radio" value="1" id="<?php BGCBSHelper::getFormName('email_notification_booking_new_admin_enable_1'); ?>" name="<?php BGCBSHelper::getFormName('email_notification_booking_new_admin_enable'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['email_notification_booking_new_admin_enable'],1); ?>/>
										<label for="<?php BGCBSHelper::getFormName('email_notification_booking_new_admin_enable_1'); ?>"><?php esc_html_e('Enable','bookingo'); ?></label>
										<input type="radio" value="0" id="<?php BGCBSHelper::getFormName('email_notification_booking_new_admin_enable_0'); ?>" name="<?php BGCBSHelper::getFormName('email_notification_booking_new_admin_enable'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['email_notification_booking_new_admin_enable'],0); ?>/>
										<label for="<?php BGCBSHelper::getFormName('email_notification_booking_new_admin_enable_0'); ?>"><?php esc_html_e('Disable','bookingo'); ?></label>
									</div>								
								</li>
						   </ul>
						</div>
						<div id="meta-box-course-2-2">	
							<ul class="to-form-field-list">
								<li>
									<h5><?php esc_html_e('Status','bookingo'); ?></h5>
									<span class="to-legend-field"><?php esc_html_e('Status.','bookingo'); ?></span>
									<div class="to-radio-button">
										<input type="radio" value="1" id="<?php BGCBSHelper::getFormName('nexmo_sms_enable_1'); ?>" name="<?php BGCBSHelper::getFormName('nexmo_sms_enable'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['nexmo_sms_enable'],1); ?>/>
										<label for="<?php BGCBSHelper::getFormName('nexmo_sms_enable_1'); ?>"><?php esc_html_e('Enable','bookingo'); ?></label>
										<input type="radio" value="0" id="<?php BGCBSHelper::getFormName('nexmo_sms_enable_0'); ?>" name="<?php BGCBSHelper::getFormName('nexmo_sms_enable'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['nexmo_sms_enable'],0); ?>/>
										<label for="<?php BGCBSHelper::getFormName('nexmo_sms_enable_0'); ?>"><?php esc_html_e('Disable','bookingo'); ?></label>
									</div>								
								</li>
								<li>
									<h5><?php esc_html_e('API key','bookingo'); ?></h5>
									<span class="to-legend-field"><?php esc_html_e('API key.','bookingo'); ?></span>
									<div>
										<input type="text" name="<?php BGCBSHelper::getFormName('nexmo_sms_api_key'); ?>" value="<?php echo esc_attr($this->data['meta']['nexmo_sms_api_key']); ?>"/>
									</div>
								</li>
								<li>
									<h5><?php esc_html_e('Secret API key','bookingo'); ?></h5>
									<span class="to-legend-field"><?php esc_html_e('Secret API key.','bookingo'); ?></span>
									<div class="to-clear-fix">
										<input type="text" name="<?php BGCBSHelper::getFormName('nexmo_sms_api_key_secret'); ?>" value="<?php echo esc_attr($this->data['meta']['nexmo_sms_api_key_secret']); ?>"/>
									</div> 
								</li>
								<li>
									<h5><?php esc_html_e('Sender name','bookingo'); ?></h5>
									<span class="to-legend-field"><?php esc_html_e('Sender name.','bookingo'); ?></span>
									<div class="to-clear-fix">
										<input type="text" name="<?php BGCBSHelper::getFormName('nexmo_sms_sender_name'); ?>" value="<?php echo esc_attr($this->data['meta']['nexmo_sms_sender_name']); ?>"/>
									</div> 
								</li>
								<li>
									<h5><?php esc_html_e('Recipient phone number','bookingo'); ?></h5>
									<span class="to-legend-field"><?php esc_html_e('Recipient phone number.','bookingo'); ?></span>
									<div class="to-clear-fix">
										<input type="text" name="<?php BGCBSHelper::getFormName('nexmo_sms_recipient_phone_number'); ?>" value="<?php echo esc_attr($this->data['meta']['nexmo_sms_recipient_phone_number']); ?>"/>
									</div> 
								</li>
								<li>
									<h5><?php esc_html_e('Message','bookingo'); ?></h5>
									<span class="to-legend-field"><?php esc_html_e('Message.','bookingo'); ?></span>
									<div class="to-clear-fix">
										<input type="text" name="<?php BGCBSHelper::getFormName('nexmo_sms_message'); ?>" value="<?php echo esc_attr($this->data['meta']['nexmo_sms_message']); ?>"/>
									</div>							  
								</li>
							</ul>
						</div>
						<div id="meta-box-course-2-3">	
							<ul class="to-form-field-list">
								<li>
									<h5><?php esc_html_e('Status','bookingo'); ?></h5>
									<span class="to-legend-field"><?php esc_html_e('Status.','bookingo'); ?></span>
									<div class="to-radio-button">
										<input type="radio" value="1" id="<?php BGCBSHelper::getFormName('twilio_sms_enable_1'); ?>" name="<?php BGCBSHelper::getFormName('twilio_sms_enable'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['twilio_sms_enable'],1); ?>/>
										<label for="<?php BGCBSHelper::getFormName('twilio_sms_enable_1'); ?>"><?php esc_html_e('Enable','bookingo'); ?></label>
										<input type="radio" value="0" id="<?php BGCBSHelper::getFormName('twilio_sms_enable_0'); ?>" name="<?php BGCBSHelper::getFormName('twilio_sms_enable'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['twilio_sms_enable'],0); ?>/>
										<label for="<?php BGCBSHelper::getFormName('twilio_sms_enable_0'); ?>"><?php esc_html_e('Disable','bookingo'); ?></label>
									</div>								
								</li>
								<li>
									<h5><?php esc_html_e('API SID','bookingo'); ?></h5>
									<span class="to-legend-field"><?php esc_html_e('API SID.','bookingo'); ?></span>
									<div class="to-clear-fix">
										<input type="text" name="<?php BGCBSHelper::getFormName('twilio_sms_api_sid'); ?>" value="<?php echo esc_attr($this->data['meta']['twilio_sms_api_sid']); ?>"/>
									</div>
								</li>
								<li>
									<h5><?php esc_html_e('API token','bookingo'); ?></h5>
									<span class="to-legend-field"><?php esc_html_e('API token.','bookingo'); ?></span>
									<div class="to-clear-fix">
										<input type="text" name="<?php BGCBSHelper::getFormName('twilio_sms_api_token'); ?>" value="<?php echo esc_attr($this->data['meta']['twilio_sms_api_token']); ?>"/>
									</div>
								</li>
								<li>
									<h5><?php esc_html_e('Sender phone number','bookingo'); ?></h5>
									<span class="to-legend-field"><?php esc_html_e('Sender phone number.','bookingo'); ?></span>
									<div class="to-clear-fix">
										<input type="text" name="<?php BGCBSHelper::getFormName('twilio_sms_sender_phone_number'); ?>" value="<?php echo esc_attr($this->data['meta']['twilio_sms_sender_phone_number']); ?>"/>
									</div> 
								</li>
								<li>
									<h5><?php esc_html_e('Recipient phone number','bookingo'); ?></h5>
									<span class="to-legend-field"><?php esc_html_e('Recipient phone number.','bookingo'); ?></span>
									<div class="to-clear-fix">
										<input type="text" name="<?php BGCBSHelper::getFormName('twilio_sms_recipient_phone_number'); ?>" value="<?php echo esc_attr($this->data['meta']['twilio_sms_recipient_phone_number']); ?>"/>
									</div> 
								</li>
								<li>
									<h5><?php esc_html_e('Message','bookingo'); ?></h5>
									<span class="to-legend-field"><?php esc_html_e('Message.','bookingo'); ?></span>
									<div class="to-clear-fix">
										<input type="text" name="<?php BGCBSHelper::getFormName('twilio_sms_message'); ?>" value="<?php echo esc_attr($this->data['meta']['twilio_sms_message']); ?>"/>
									</div>							  
								</li>
							</ul>
						</div>
						<div id="meta-box-course-2-4">	
							<ul class="to-form-field-list">
								<li>
									<h5><?php esc_html_e('Status','bookingo'); ?></h5>
									<span class="to-legend-field"><?php esc_html_e('Status.','bookingo'); ?></span>
									<div class="to-radio-button">
										<input type="radio" value="1" id="<?php BGCBSHelper::getFormName('telegram_enable_1'); ?>" name="<?php BGCBSHelper::getFormName('telegram_enable'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['telegram_enable'],1); ?>/>
										<label for="<?php BGCBSHelper::getFormName('telegram_enable_1'); ?>"><?php esc_html_e('Enable','bookingo'); ?></label>
										<input type="radio" value="0" id="<?php BGCBSHelper::getFormName('telegram_enable_0'); ?>" name="<?php BGCBSHelper::getFormName('telegram_enable'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['telegram_enable'],0); ?>/>
										<label for="<?php BGCBSHelper::getFormName('telegram_enable_0'); ?>"><?php esc_html_e('Disable','bookingo'); ?></label>
									</div>								
								</li>
								<li>
									<h5><?php esc_html_e('Token','bookingo'); ?></h5>
									<span class="to-legend-field"><?php esc_html_e('Token.','bookingo'); ?></span>
									<div class="to-clear-fix">
										<input type="text" name="<?php BGCBSHelper::getFormName('telegram_token'); ?>" value="<?php echo esc_attr($this->data['meta']['telegram_token']); ?>"/>
									</div>
								</li>
								<li>
									<h5><?php esc_html_e('Group ID','bookingo'); ?></h5>
									<span class="to-legend-field"><?php esc_html_e('Group ID.','bookingo'); ?></span>
									<div class="to-clear-fix">
										<input type="text" name="<?php BGCBSHelper::getFormName('telegram_group_id'); ?>" value="<?php echo esc_attr($this->data['meta']['telegram_group_id']); ?>"/>
									</div>
								</li>
								<li>
									<h5><?php esc_html_e('Message','bookingo'); ?></h5>
									<span class="to-legend-field"><?php esc_html_e('Message.','bookingo'); ?></span>
									<div class="to-clear-fix">
										<input type="text" name="<?php BGCBSHelper::getFormName('telegram_message'); ?>" value="<?php echo esc_attr($this->data['meta']['telegram_message']); ?>"/>
									</div>
								</li>
						   </ul>
					   </div>
					</div>
				</div> -->
				<div id="meta-box-course-4">
					<ul class="to-form-field-list">
						<li>
							<h5><?php esc_html_e('Panels','bookingo'); ?></h5>
							<span class="to-legend">
								<?php esc_html_e('Table includes list of user defined panels (group of fields) used in client form.','bookingo'); ?>
							</span>
							<div class="to-clear-fix">
								<table class="to-table" id="to-table-form-element-field">
									<tr>
										<th style="width:85%">
											<div>
												<?php esc_html_e('Label','bookingo'); ?>
												<span class="to-legend">
													<?php esc_html_e('Label of the panel.','bookingo'); ?>
												</span>
											</div>
										</th>
										<th style="width:18%">
											<div>
												<?php esc_html_e('Remove','bookingo'); ?>
												<span class="to-legend">
													<?php esc_html_e('Remove this entry.','bookingo'); ?>
												</span>
											</div>
										</th>
									</tr>
									<tr class="to-hidden">
										<td>
											<div>
												<input type="hidden" name="<?php BGCBSHelper::getFormName('form_element_panel[id][]'); ?>"/>
												<input type="text" name="<?php BGCBSHelper::getFormName('form_element_panel[label][]'); ?>" title="<?php esc_attr_e('Enter label.','bookingo'); ?>"/>
											</div>									
										</td>
										<td>
											<div>
												<a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','bookingo'); ?></a>
											</div>
										</td>										
									</tr>
<?php
		if(isset($this->data['meta']['form_element_panel']))
		{
			foreach($this->data['meta']['form_element_panel'] as $panelValue)
			{
?>
									<tr>
										<td>
											<div>
												<input type="hidden" value="<?php echo esc_attr($panelValue['id']); ?>" name="<?php BGCBSHelper::getFormName('form_element_panel[id][]'); ?>"/>
												<input type="text" value="<?php echo esc_attr($panelValue['label']); ?>" name="<?php BGCBSHelper::getFormName('form_element_panel[label][]'); ?>" title="<?php esc_attr_e('Enter label.','bookingo'); ?>"/>
											</div>									
										</td>
										<td>
											<div>
												<a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','bookingo'); ?></a>
											</div>
										</td>										
									</tr>	 
<?php			  
			}
		}
?>
								</table>
								<div> 
									<a href="#" class="to-table-button-add"><?php esc_html_e('Add','bookingo'); ?></a>
								</div>
							</div>				
						</li>
						<li>
							<h5><?php esc_html_e('Fields','bookingo'); ?></h5>
							<span class="to-legend">
								<?php esc_html_e('Table includes list of user defined fields used in client form.','bookingo'); ?><br/>
                            	<?php esc_html_e('In case of set field as "Mandatory", it is needed to fill "Error message" field, otherwise new field will not be created.','bookingo'); ?><br/>
							</span>
							<div class="to-clear-fix">
								<table class="to-table" id="to-table-form-element-panel">
									<tr>
										<th style="width:15%">
											<div>
												<?php esc_html_e('Label','bookingo'); ?>
												<span class="to-legend">
													<?php esc_html_e('Label of the field.','bookingo'); ?>
												</span>
											</div>
										</th>
										<th style="width:15%">
											<div>
												<?php esc_html_e('Type','bookingo'); ?>
												<span class="to-legend">
													<?php esc_html_e('Field type.','bookingo'); ?>
												</span>
											</div>
										</th>										
										<th style="width:5%">
											<div>
												<?php esc_html_e('Mandatory','bookingo'); ?>
												<span class="to-legend">
													<?php esc_html_e('Mandatory.','bookingo'); ?>
												</span>
											</div>
										</th>   
										<th style="width:20%">
											<div>
												<?php esc_html_e('Values','bookingo'); ?>
												<span class="to-legend">
													<?php esc_html_e('List of possible values to choose separated by semicolon.','bookingo'); ?>
												</span>
											</div>
										</th>   										
										<th style="width:15%">
											<div>
												<?php esc_html_e('Error message','bookingo'); ?>
												<span class="to-legend">
													<?php esc_html_e('Error message displayed in tooltip when field is empty.','bookingo'); ?>
												</span>
											</div>
										</th>											  
										<th style="width:20%">
											<div>
												<?php esc_html_e('Panel','bookingo'); ?>
												<span class="to-legend">
													<?php esc_html_e('Panel in which field has to be located.','bookingo'); ?>
												</span>
											</div>
										</th>											 
										<th style="width:10%">
											<div>
												<?php esc_html_e('Remove','bookingo'); ?>
												<span class="to-legend">
													<?php esc_html_e('Remove this entry.','bookingo'); ?>
												</span>
											</div>
										</th>
									</tr>
									<tr class="to-hidden">
										<td>
											<div class="to-clear-fix">
												<input type="hidden" name="<?php BGCBSHelper::getFormName('form_element_field[id][]'); ?>"/>
												<input type="text" name="<?php BGCBSHelper::getFormName('form_element_field[label][]'); ?>" title="<?php esc_attr_e('Enter label.','bookingo'); ?>"/>
											</div>									
										</td>
										<td>
											<div class="to-clear-fix">
												<select name="<?php BGCBSHelper::getFormName('form_element_field[field_type][]'); ?>" class="to-dropkick-disable" id="form_element_field_field_type">
<?php
		foreach($this->data['dictionary']['field_type'] as $index=>$value)
			echo '<option value="'.esc_attr($index).'">'.esc_html($value[0]).'</option>';
?>
												</select>
											</div>									
										</td>										
										<td>
											<div class="to-clear-fix">
												<select name="<?php BGCBSHelper::getFormName('form_element_field[mandatory][]'); ?>" class="to-dropkick-disable" id="form_element_field_mandatory">
													<option value="1"><?php esc_html_e('Yes','bookingo'); ?></option>
													<option value="0"><?php esc_html_e('No','bookingo'); ?></option>
												</select>
											</div>
										</td>										
										<td>
											<div class="to-clear-fix">												
												<input type="text" name="<?php BGCBSHelper::getFormName('form_element_field[dictionary][]'); ?>" title="<?php esc_attr_e('Enter values of list separated by semicolon.','bookingo'); ?>"/>
											</div>									
										</td>   										
										<td>
											<div class="to-clear-fix">												
												<input type="text" name="<?php BGCBSHelper::getFormName('form_element_field[message_error][]'); ?>" title="<?php esc_attr_e('Enter error message.','bookingo'); ?>"/>
											</div>									
										</td>										
										<td>
											<div class="to-clear-fix">
												<select name="<?php BGCBSHelper::getFormName('form_element_field[panel_id][]'); ?>" class="to-dropkick-disable" id="form_element_field_panel_id">
<?php
		foreach($this->data['dictionary']['form_element_panel'] as $index=>$value)
			echo '<option value="'.esc_attr($value['id']).'">'.esc_html($value['label']).'</option>';
?>
												</select>
											</div>									
										</td>
										<td>
											<div>
												<a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','bookingo'); ?></a>
											</div>
										</td>										
									</tr>
<?php
		if(isset($this->data['meta']['form_element_field']))
		{
			foreach($this->data['meta']['form_element_field'] as $fieldValue)
			{
?>			   
									<tr>
										<td>
											<div class="to-clear-fix">
												<input type="hidden" value="<?php echo esc_attr($fieldValue['id']); ?>" name="<?php BGCBSHelper::getFormName('form_element_field[id][]'); ?>"/>

                                                <?php
                                                    $disabled = '';
                                                    $hardcoded = [
                                                        'Documento de viaje',
                                                        'No. de Documento de viaje',
                                                        'Fecha de nacimiento',
                                                        'Universidad',
                                                        'Documentación',
                                                        'Foto de carnet',
                                                    ];

                                                    $cfe = new BGCBSCourseFormElement();
                                                    $hardcoded = array_merge($hardcoded, array_keys($cfe->disciplinas));
                                                    $hardcoded = array_merge($hardcoded, array_keys($cfe->customOptions));

                                                    if (in_array($fieldValue['label'], $hardcoded)) {
                                                        $disabled = 'disabled';
                                                    }
                                                ?>

												<input <?php echo $disabled; ?> type="text" value="<?php echo esc_attr($fieldValue['label']); ?>" name="<?php BGCBSHelper::getFormName('form_element_field[label][]'); ?>" title="<?php esc_attr_e('Enter label.','bookingo'); ?>"/>
											</div>
										</td>
										<td>
											<div class="to-clear-fix">
												<select  id="<?php BGCBSHelper::getFormName('form_element_field_field_type_'.$fieldValue['id']); ?>" name="<?php BGCBSHelper::getFormName('form_element_field[field_type][]'); ?>">
<?php
		foreach($this->data['dictionary']['field_type'] as $index=>$value)
			echo '<option value="'.esc_attr($index).'" '.(BGCBSHelper::selectedIf($fieldValue['field_type'],$index,false)).'>'.esc_html($value[0]).'</option>';
?>
												</select>
											</div>									
										</td>	
										<td>
											<div class="to-clear-fix">
												<select id="<?php BGCBSHelper::getFormName('form_element_field_mandatory_'.$fieldValue['id']); ?>" name="<?php BGCBSHelper::getFormName('form_element_field[mandatory][]'); ?>">
													<option value="1" <?php BGCBSHelper::selectedIf($fieldValue['mandatory'],1); ?>><?php esc_html_e('Yes','bookingo'); ?></option>
													<option value="0" <?php BGCBSHelper::selectedIf($fieldValue['mandatory'],0); ?>><?php esc_html_e('No','bookingo'); ?></option>
												</select>
											</div>
										</td>
										<td>
											<div class="to-clear-fix">												
												<input <?php echo $disabled; ?> type="text" value="<?php echo esc_attr($fieldValue['dictionary']); ?>" name="<?php BGCBSHelper::getFormName('form_element_field[dictionary][]'); ?>" title="<?php esc_attr_e('Enter values of list separated by semicolon.','bookingo'); ?>"/>
											</div>									
										</td> 
										<td>
											<div class="to-clear-fix">												
												<input <?php echo $disabled; ?> type="text" value="<?php echo esc_attr($fieldValue['message_error']); ?>" name="<?php BGCBSHelper::getFormName('form_element_field[message_error][]'); ?>" title="<?php esc_attr_e('Enter error message.','bookingo'); ?>"/>
											</div>									
										</td>										
										<td>
											<div class="to-clear-fix">
												<select id="<?php BGCBSHelper::getFormName('form_element_field_panel_id_'.$fieldValue['id']); ?>" name="<?php BGCBSHelper::getFormName('form_element_field[panel_id][]'); ?>">
<?php
		foreach($this->data['dictionary']['form_element_panel'] as $index=>$value)
			echo '<option value="'.esc_attr($value['id']).'" '.(BGCBSHelper::selectedIf($fieldValue['panel_id'],$value['id'],false)).'>'.esc_html($value['label']).'</option>';
?>
												</select>
											</div>									
										</td>
										<td>
											<div>
                                                <?php
                                                    if (!$disabled)
                                                    {
                                                        echo '<a href="#" class="to-table-button-remove">' . esc_html('Remove','bookingo') . '</a>';
                                                    }
                                                ?>
											</div>
										</td>										
									</tr>		   
<?php			  
			}
		}
?>									
								</table>
								<div> 
									<a href="#" class="to-table-button-add"><?php esc_html_e('Add','bookingo'); ?></a>
								</div>
							</div>				
						</li>
					</ul>
				</div>				
				<div id="meta-box-course-5">
					<ul class="to-form-field-list">
						<li>
							<h5><?php esc_html_e('Agreements','bookingo'); ?></h5>
							<span class="to-legend">
								<?php esc_html_e('Table includes list of agreements needed to accept by course participant before sending the booking.','bookingo'); ?><br/>
								<?php esc_html_e('Each agreement consists of approval field (checkbox) and text of agreement.','bookingo'); ?>
							</span>
							<div class="to-clear-fix">
								<table class="to-table" id="to-table-agreement">
									<tr>
										<th style="width:60%">
											<div>
												<?php esc_html_e('Text','bookingo'); ?>
												<span class="to-legend">
													<?php esc_html_e('Text of the agreement.','bookingo'); ?>
												</span>
											</div>
										</th>
										<th style="width:25%">
											<div>
												<?php esc_html_e('Mandatory','bookingo'); ?>
												<span class="to-legend">
													<?php esc_html_e('Mandatory.','bookingo'); ?>
												</span>
											</div>
										</th>										
										<th style="width:15%">
											<div>
												<?php esc_html_e('Remove','bookingo'); ?>
												<span class="to-legend">
													<?php esc_html_e('Remove this entry.','bookingo'); ?>
												</span>
											</div>
										</th>
									</tr>
									<tr class="to-hidden">
										<td>
											<div>
												<input type="hidden" name="<?php BGCBSHelper::getFormName('agreement[id][]'); ?>"/>
												<input type="text" name="<?php BGCBSHelper::getFormName('agreement[text][]'); ?>" title="<?php esc_attr_e('Enter text.','bookingo'); ?>"/>
											</div>									
										</td>
										<td>
											<div class="to-clear-fix">
												<select name="<?php BGCBSHelper::getFormName('agreement[mandatory][]'); ?>" class="to-dropkick-disable" id="agreement_mandatory">
													<option value="1"><?php esc_html_e('Yes','bookingo'); ?></option>
													<option value="0"><?php esc_html_e('No','bookingo'); ?></option>
												</select>
											</div>
										</td>	  
										<td>
											<div>
												<a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','bookingo'); ?></a>
											</div>
										</td>										
									</tr>
<?php
		if(isset($this->data['meta']['agreement']))
		{
			foreach($this->data['meta']['agreement'] as $value)
			{
?>
									<tr>
										<td>
											<div>
												<input type="hidden" value="<?php echo esc_attr($value['id']); ?>" name="<?php BGCBSHelper::getFormName('agreement[id][]'); ?>"/>
												<input type="text" value="<?php echo esc_attr($value['text']); ?>" name="<?php BGCBSHelper::getFormName('agreement[text][]'); ?>" title="<?php esc_attr_e('Enter text.','bookingo'); ?>"/>
											</div>									
										</td>
										<td>
											<div class="to-clear-fix">
												<select name="<?php BGCBSHelper::getFormName('agreement[mandatory][]'); ?>">
													<option value="1" <?php BGCBSHelper::selectedIf($value['mandatory'],1); ?> id="<?php BGCBSHelper::getFormName('agreement_mandatory_'.$value['id']); ?>"><?php esc_html_e('Yes','bookingo'); ?></option>
													<option value="0" <?php BGCBSHelper::selectedIf($value['mandatory'],0); ?>><?php esc_html_e('No','bookingo'); ?></option>
												</select>
											</div>
										</td>
										<td>
											<div>
												<a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','bookingo'); ?></a>
											</div>
										</td>										
									</tr>							   
<?php
			}
		}
?>
								</table>
								<div> 
									<a href="#" class="to-table-button-add"><?php esc_html_e('Add','bookingo'); ?></a>
								</div>
							</div>				
						</li>
					</ul>
				</div>	
				<div id="meta-box-course-6">
					<ul class="to-form-field-list">
 						<li>
							<h5><?php esc_html_e('Promo section','bookingo'); ?></h5>
							<span class="to-legend">
								<?php esc_html_e('Enter settings for promo section.','bookingo'); ?>
							</span>
							<div>
								<span class="to-legend-field"><?php esc_html_e('Image:','bookingo'); ?></span>
								<div class="to-clear-fix">
									<input type="text" name="<?php BGCBSHelper::getFormName('promo_image'); ?>" id="<?php BGCBSHelper::getFormName('promo_image'); ?>" class="to-float-left" value="<?php echo esc_attr($this->data['meta']['promo_image']); ?>"/>
									<input type="button" name="<?php BGCBSHelper::getFormName('promo_image_browse'); ?>" id="<?php BGCBSHelper::getFormName('promo_image_browse'); ?>" class="to-button-browse to-button" value="<?php esc_attr_e('Browse','bookingo'); ?>"/>
								</div>
							</div>
							<div>
								<span class="to-legend-field"><?php esc_html_e('Embed video code:','bookingo'); ?></span>
								<textarea name="<?php BGCBSHelper::getFormName('promo_video_embed_code'); ?>"><?php echo esc_html($this->data['meta']['promo_video_embed_code']); ?></textarea>
							</div>
						</li>
 						<li>
							<h5><?php esc_html_e('"Thank you" page','bookingo'); ?></h5>
							<span class="to-legend">
								<?php esc_html_e('Enter settings for "Thank you" page.','bookingo'); ?>
							</span>
							<div>
								<span class="to-legend-field"><?php esc_html_e('Header:','bookingo'); ?></span>
								<input type="text" name="<?php BGCBSHelper::getFormName('thank_you_page_header_text'); ?>" value="<?php echo esc_attr($this->data['meta']['thank_you_page_header_text']); ?>"/>
							</div>
							<div>
								<span class="to-legend-field"><?php esc_html_e('Subheader:','bookingo'); ?></span>
								<textarea name="<?php BGCBSHelper::getFormName('thank_you_page_subheader_text'); ?>"><?php echo nl2br(esc_html($this->data['meta']['thank_you_page_subheader_text'])); ?></textarea>
							</div>
						</li>
 						<li>
							<h5><?php esc_html_e('Back button','bookingo'); ?></h5>
							<span class="to-legend">
								<?php esc_html_e('Button displayed on the second step (payment) if there is no payment method.','bookingo'); ?>
							</span>
							<div>
								<span class="to-legend-field"><?php esc_html_e('Label:','bookingo'); ?></span>
                                <input type="text" name="<?php BGCBSHelper::getFormName('payment_form_button_1_label'); ?>" id="<?php BGCBSHelper::getFormName('payment_form_button_1_label'); ?>" value="<?php echo esc_attr($this->data['meta']['payment_form_button_1_label']); ?>"/>
							</div>
							<div>
                                <span class="to-legend-field"><?php esc_html_e('URL address:','bookingo'); ?></span>
                          		<input type="text" name="<?php BGCBSHelper::getFormName('payment_form_button_1_url_address'); ?>" id="<?php BGCBSHelper::getFormName('payment_form_button_1_url_address'); ?>" value="<?php echo esc_attr($this->data['meta']['payment_form_button_1_url_address']); ?>"/>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div
<?php
		wp_add_inline_script('bgcbs-admin',
		'
			jQuery(document).ready(function($)
			{	
				var element=$(\'.to\').themeOptionElement({init:true});

				element.bindBrowseMedia(\'input[name="'.BGCBSHelper::getFormName('promo_image_browse',false).'"]\');

				/***/

				var timeFormat=\''.BGCBSOption::getOption('time_format').'\';
				var dateFormat=\''.BGCBSJQueryUIDatePicker::convertDateFormat(BGCBSOption::getOption('date_format')).'\';

				toCreateCustomDateTimePicker(dateFormat,timeFormat);

				/***/

				$(\'input[name="'.BGCBSHelper::getFormName('course_group_id',false).'[]"],input[name="'.BGCBSHelper::getFormName('payment_id',false).'[]"]\').on(\'change\',function()
				{
					var checkbox=$(this).parents(\'li:first\').find(\'input[type="checkbox"]\');

					var value=parseInt($(this).val());

					if(value===-1)
					{
						checkbox.prop(\'checked\',false);
						checkbox.first().prop(\'checked\',true);
					}
					else checkbox.first().prop(\'checked\',false);	

					var checked=[];
					checkbox.each(function()
					{
						if($(this).is(\':checked\'))
							checked.push(parseInt($(this).val(),10));
					});

					if(checked.length===0)
					{
						checkbox.prop(\'checked\',false);
						checkbox.first().prop(\'checked\',true);
					}

					checkbox.button(\'refresh\');
				});

				/***/

				$(\'#to-table-form-element-panel\').table();
				$(\'#to-table-form-element-field\').table();
				$(\'#to-table-agreement\').table();
				
				toCreateEditPostLink();

				/***/
			});
		');