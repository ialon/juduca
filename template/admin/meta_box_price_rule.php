<?php 
		echo $this->data['nonce'];
		
		$Date=new BGCBSDate();
?>	
		<div class="to">
			<div class="ui-tabs">
				<ul>
					<li><a href="#meta-box-price-rule-1"><?php esc_html_e('Conditions ','bookingo'); ?></a></li>
					<li><a href="#meta-box-price-rule-2"><?php esc_html_e('Prices','bookingo'); ?></a></li>
				</ul>
				<div id="meta-box-price-rule-1">
					<ul class="to-form-field-list">
						<?php echo BGCBSHelper::createPostIdField(esc_html__('Price rule ID','bookingo')); ?>
						<li>
							<h5><?php esc_html_e('Booking forms','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Select forms.','bookingo'); ?></span>
							<div class="to-checkbox-button">
								<input type="checkbox" value="-1" id="<?php BGCBSHelper::getFormName('booking_form_id_0'); ?>" name="<?php BGCBSHelper::getFormName('booking_form_id[]'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['booking_form_id'],-1); ?>/>
								<label for="<?php BGCBSHelper::getFormName('booking_form_id_0'); ?>"><?php esc_html_e('- All forms -','bookingo') ?></label>
<?php
		foreach($this->data['dictionary']['booking_form'] as $index=>$value)
		{
?>
								<input type="checkbox" value="<?php echo esc_attr($index); ?>" id="<?php BGCBSHelper::getFormName('booking_form_id_'.$index); ?>" name="<?php BGCBSHelper::getFormName('booking_form_id[]'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['booking_form_id'],$index); ?>/>
								<label for="<?php BGCBSHelper::getFormName('booking_form_id_'.$index); ?>"><?php echo esc_html($value['post']->post_title); ?></label>
<?php		
		}
?>
							</div>
						</li>
						<li>
							<h5><?php esc_html_e('Course','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Select course.','bookingo'); ?></span>
							<div class="to-checkbox-button">
								<input type="checkbox" value="-1" id="<?php BGCBSHelper::getFormName('course_id_0'); ?>" name="<?php BGCBSHelper::getFormName('course_id[]'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['course_id'],-1); ?>/>
								<label for="<?php BGCBSHelper::getFormName('course_id_0'); ?>"><?php esc_html_e('- All courses -','bookingo') ?></label>
<?php
		foreach($this->data['dictionary']['course'] as $index=>$value)
		{
?>
								<input type="checkbox" value="<?php echo esc_attr($index); ?>" id="<?php BGCBSHelper::getFormName('course_id_'.$index); ?>" name="<?php BGCBSHelper::getFormName('course_id[]'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['course_id'],$index); ?>/>
								<label for="<?php BGCBSHelper::getFormName('course_id_'.$index); ?>"><?php echo esc_html($value['post']->post_title); ?></label>
<?php		
		}
?>
							</div>
						</li>  
						<li>
							<h5><?php esc_html_e('Course group','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Select course group.','bookingo'); ?></span>
							<div class="to-checkbox-button">
								<input type="checkbox" value="-1" id="<?php BGCBSHelper::getFormName('course_group_id_0'); ?>" name="<?php BGCBSHelper::getFormName('course_group_id[]'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['course_group_id'],-1); ?>/>
								<label for="<?php BGCBSHelper::getFormName('course_group_id_0'); ?>"><?php esc_html_e('- All course groups -','bookingo') ?></label>
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
							<h5><?php esc_html_e('Registration dates','bookingo'); ?></h5>
							<span class="to-legend">
								<?php esc_html_e('Enter the registration dates for which rule has to be applied or define prices.','bookingo'); ?><br/>
								<?php esc_html_e('If the "Price source" option is set to "Calculation based on registration dates" plugin uses prices defined in this table.','bookingo'); ?><br/>
								<?php esc_html_e('Otherwise, plugin checks whether the registration date is defined in this table and use prices from "Prices" table.','bookingo'); ?>
							</span>
							<div>
								<table class="to-table" id="to-table-registration-date">
									<tr>
										<th style="width:30%">
											<div>
												<?php esc_html_e('From','bookingo'); ?>
												<span class="to-legend">
													<?php esc_html_e('From.','bookingo'); ?>
												</span>
											</div>
										</th>
										<th style="width:30%">
											<div>
												<?php esc_html_e('To','bookingo'); ?>
												<span class="to-legend">
													<?php esc_html_e('To.','bookingo'); ?>
												</span>
											</div>
										</th>
										<th style="width:20%">
											<div>
												<?php esc_html_e('Price','bookingo'); ?>
												<span class="to-legend">
													<?php esc_html_e('Price.','bookingo'); ?>
												</span>
											</div>
										</th>
										<th style="width:20%">
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
												<input type="text" class="to-datepicker-custom" name="<?php BGCBSHelper::getFormName('registration_date[start][]'); ?>"/>
											</div>									
										</td>
										<td>
											<div>
												<input type="text" class="to-datepicker-custom" name="<?php BGCBSHelper::getFormName('registration_date[stop][]'); ?>"/>
											</div>									
										</td>
										<td>
											<div>
												<input type="text" maxlength="12" name="<?php BGCBSHelper::getFormName('registration_date[price][]'); ?>"/>
											</div>									
										</td>
										<td>
											<div>
												<a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','bookingo'); ?></a>
											</div>
										</td>
									</tr>						 
<?php
		if(isset($this->data['meta']['registration_date']))
		{
			if(is_array($this->data['meta']['registration_date']))
			{
				foreach($this->data['meta']['registration_date'] as $index=>$value)
				{
?>
									<tr>
										<td>
											<div>
												<input type="text" class="to-datepicker-custom" name="<?php BGCBSHelper::getFormName('registration_date[start][]'); ?>" value="<?php echo esc_attr($Date->formatDateToDisplay($value['start'])); ?>"/>
											</div>									
										</td>
										<td>
											<div>
												<input type="text" class="to-datepicker-custom" name="<?php BGCBSHelper::getFormName('registration_date[stop][]'); ?>" value="<?php echo esc_attr($Date->formatDateToDisplay($value['stop'])); ?>"/>
											</div>									
										</td>
										<td>
											<div>
												<input type="text" mexlength="12" name="<?php BGCBSHelper::getFormName('registration_date[price][]'); ?>" value="<?php echo esc_attr($value['price']); ?>"/>
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
				<div id="meta-box-price-rule-2">
					<ul class="to-form-field-list">
						<li>
							<h5><?php esc_html_e('Price source','bookingo'); ?></h5>
							<span class="to-legend">
								<?php esc_html_e('Select price source.','bookingo'); ?>
							</span>
							<div class="to-clear-fix">
								<select name="<?php BGCBSHelper::getFormName('price_source_type'); ?>">
<?php
		foreach($this->data['dictionary']['price_source_type'] as $index=>$value)
			echo '<option value="'.esc_attr($index).'" '.(BGCBSHelper::selectedIf($this->data['meta']['price_source_type'],$index,false)).'>'.esc_html($value[0]).'</option>';
?>
								</select>
							</div>
						</li>
						<li>
							<h5><?php esc_html_e('Next rule processing','bookingo'); ?></h5>
							<span class="to-legend">
								<?php echo esc_html__('This option determine, whether prices will be set up based on this rule only or plugin has to processing next rule based on priority (order).','bookingo'); ?>
							</span>			   
							<div>
								<div class="to-radio-button">
									<input type="radio" value="1" id="<?php BGCBSHelper::getFormName('process_next_rule_enable_1'); ?>" name="<?php BGCBSHelper::getFormName('process_next_rule_enable'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['process_next_rule_enable'],1); ?>/>
									<label for="<?php BGCBSHelper::getFormName('process_next_rule_enable_1'); ?>"><?php esc_html_e('Enable','bookingo'); ?></label>
									<input type="radio" value="0" id="<?php BGCBSHelper::getFormName('process_next_rule_enable_0'); ?>" name="<?php BGCBSHelper::getFormName('process_next_rule_enable'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['process_next_rule_enable'],0); ?>/>
									<label for="<?php BGCBSHelper::getFormName('process_next_rule_enable_0'); ?>"><?php esc_html_e('Disable','bookingo'); ?></label>
								</div>  
							</div>							  
						</li>   
						<li>
							<h5><?php esc_html_e('Prices','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Prices.','bookingo'); ?></span>
							<div>
								<table class="to-table to-table-price">
									<tr>
										<th style="width:20%">
											<div>
												<?php esc_html_e('Name','bookingo'); ?>
												<span class="to-legend">
													<?php esc_html_e('Name.','bookingo'); ?>
												</span>
											</div>
										</th>
										<th style="width:30%">
											<div>
												<?php esc_html_e('Description','bookingo'); ?>
												<span class="to-legend">
													<?php esc_html_e('Description.','bookingo'); ?>
												</span>
											</div>
										</th>
										<th style="width:20%">
											<div>
												<?php esc_html_e('Price alter','bookingo'); ?>
												<span class="to-legend">
													<?php esc_html_e('Price alter type.','bookingo'); ?>
												</span>
											</div>
										</th>	
										<th style="width:20%">
											<div>
												<?php esc_html_e('Value','bookingo'); ?>
												<span class="to-legend">
													<?php esc_html_e('Value.','bookingo'); ?>
												</span>
											</div>
										</th>										
										<th style="width:10%">
											<div>
												<?php esc_html_e('Tax','bookingo'); ?>
												<span class="to-legend">
													<?php esc_html_e('Tax.','bookingo'); ?>
												</span>
											</div>
										</th>										  
									</tr>
									<tr>
										<td>
											<div class="to-clear-fix">
												<?php esc_html_e('Participant','bookingo'); ?>
											</div>
										</td>							
										<td>
											<div class="to-clear-fix">
												<?php esc_html_e('Price per participant','bookingo'); ?>
											</div>
										</td>	
										<td>
											<div class="to-clear-fix">
												<select name="<?php BGCBSHelper::getFormName('price_participant_alter_type_id'); ?>">
<?php
		foreach($this->data['dictionary']['price_alter_type'] as $index=>$value)
		{
			echo '<option value="'.esc_attr($index).'" '.(BGCBSHelper::selectedIf($this->data['meta']['price_participant_alter_type_id'],$index,false)).'>'.esc_html($value[0]).'</option>';
		}
?>
												</select>												  
											</div>
										</td> 
										<td>
											<div class="to-clear-fix">
												<input type="text" maxlength="12" id="<?php BGCBSHelper::getFormName('price_participant_value'); ?>" name="<?php BGCBSHelper::getFormName('price_participant_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_participant_value']); ?>"/>
											</div>
										</td>	
										<td>
											<div class="to-clear-fix">
												<select name="<?php BGCBSHelper::getFormName('price_participant_tax_rate_id'); ?>">
<?php
		echo '<option value="0" '.(BGCBSHelper::selectedIf($this->data['meta']['price_participant_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','bookingo').'</option>';
		foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
		{
			echo '<option value="'.esc_attr($index).'" '.(BGCBSHelper::selectedIf($this->data['meta']['price_participant_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
		}
?>	   
												</select>
											</div>
										</td>	
									</tr>
								</table>
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
				/***/

				$(\'.to\').themeOptionElement({init:true});

				/***/

				$(\'input[name="'.BGCBSHelper::getFormName('booking_form_id',false).'[]"],input[name="'.BGCBSHelper::getFormName('course_id',false).'[]"],input[name="'.BGCBSHelper::getFormName('course_group_id',false).'[]"]\').on(\'change\',function()
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

				$(\'#to-table-registration-date\').table();

				/***/

				var timeFormat=\''.BGCBSOption::getOption('time_format').'\';
				var dateFormat=\''.BGCBSJQueryUIDatePicker::convertDateFormat(BGCBSOption::getOption('date_format')).'\';

				toCreateCustomDateTimePicker(dateFormat,timeFormat);

				/***/
			});
		');