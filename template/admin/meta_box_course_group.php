<?php
		echo $this->data['nonce']; 

		$Date=new BGCBSDate();
		$Validation=new BGCBSValidation();
?>		
		<div class="to">
			<div class="ui-tabs">
				<ul>
					<li><a href="#meta-box-course-group-1"><?php esc_html_e('General','bookingo'); ?></a></li>
					<li><a href="#meta-box-course-group-2"><?php esc_html_e('Schedule','bookingo'); ?></a></li>
				</ul>
				<div id="meta-box-course-group-1">
					<ul class="to-form-field-list">
 						<li>
							<h5><?php esc_html_e('Course start','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Set date/time of course start for this group.','bookingo'); ?></span>
							<div>
								<span class="to-legend-field"><?php esc_html_e('Date.','bookingo'); ?></span>
								<input class="to-datepicker-custom" type="text" name="<?php BGCBSHelper::getFormName('course_group_start_date'); ?>" value="<?php echo esc_attr($Date->formatDateToDisplay($this->data['meta']['course_group_start_date'])); ?>"/>
							</div>
							<div>
								<span class="to-legend-field"><?php esc_html_e('Time.','bookingo'); ?></span>
								<input class="to-timepicker-custom" type="text" name="<?php BGCBSHelper::getFormName('course_group_start_time'); ?>" value="<?php echo esc_attr($this->data['meta']['course_group_start_time']); ?>"/>
							</div>
						</li>
 						<li>
							<h5><?php esc_html_e('Course end','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Set date/time of course end for this group.','bookingo'); ?></span>
							<div>
								<span class="to-legend-field"><?php esc_html_e('Date.','bookingo'); ?></span>
								<input class="to-datepicker-custom" type="text" name="<?php BGCBSHelper::getFormName('course_group_end_date'); ?>" value="<?php echo esc_attr($Date->formatDateToDisplay($this->data['meta']['course_group_end_date'])); ?>"/>
							</div>
							<div>
								<span class="to-legend-field"><?php esc_html_e('Time.','bookingo'); ?></span>
								<input class="to-timepicker-custom" type="text" name="<?php BGCBSHelper::getFormName('course_group_end_time'); ?>" value="<?php echo esc_attr($this->data['meta']['course_group_end_time']); ?>"/>
							</div>
						</li>
						<!-- <li>
							<h5>
								<?php esc_html_e('Location','bookingo'); ?>
								<?php echo BGCBSHelper::createAddPostLink(BGCBSLocation::getCPTName(),'Add new'); ?>
								<?php echo BGCBSHelper::createEditPostLink('Edit'); ?>
							</h5>
							<span class="to-legend">
								<?php esc_html_e('Select location of course for this group.','bookingo'); ?>
							</span>
							<div class="to-clear-fix">
								<select name="<?php BGCBSHelper::getFormName('location_id'); ?>">
<?php
		echo '<option value="0" '.(BGCBSHelper::selectedIf($this->data['meta']['location_id'],0,false)).'>'.esc_html__('- None -','bookingo').'</option>';
		foreach($this->data['dictionary']['location'] as $index=>$value)
			echo '<option value="'.esc_attr($index).'" '.(BGCBSHelper::selectedIf($this->data['meta']['location_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
?>
								</select>
							</div>						
						</li> -->
						<!-- <li>
							<h5><?php esc_html_e('Number of lessons','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Enter number of lessons.','bookingo'); ?></span>
							<div>
								<input type="text" maxlength="5" name="<?php BGCBSHelper::getFormName('lesson_number'); ?>" value="<?php echo esc_attr($this->data['meta']['lesson_number']); ?>"/>
							</div>
						</li> -->
						<!-- <li>
							<h5><?php esc_html_e('Lesson length','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Enter length of single lesson in minutes.','bookingo'); ?></span>
							<div>
								<input type="text" maxlength="5" name="<?php BGCBSHelper::getFormName('lesson_length'); ?>" value="<?php echo esc_attr($this->data['meta']['lesson_length']); ?>"/>
							</div>
						</li> -->
 						<!-- <li>
							<h5>
								<?php esc_html_e('Price','bookingo'); ?>
								<?php echo BGCBSHelper::createAddPostLink(BGCBSTaxRate::getCPTName(),'Add new'); ?>
								<?php echo BGCBSHelper::createEditPostLink('Edit'); ?>
							</h5>
							<span class="to-legend"><?php esc_html_e('Enter net price and tax for course participant in this group.','bookingo'); ?></span>
							<div>
								<span class="to-legend-field"><?php esc_html_e('Net price.','bookingo'); ?></span>
								<input type="text" name="<?php BGCBSHelper::getFormName('price_participant_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_participant_value']); ?>"/>
							</div>
							<div>
								<span class="to-legend-field">
									<?php esc_html_e('Tax rate.','bookingo'); ?>
								</span>
								<select name="<?php BGCBSHelper::getFormName('price_participant_tax_rate_id'); ?>">
<?php
		echo '<option value="0" '.(BGCBSHelper::selectedIf($this->data['meta']['price_participant_tax_rate_id'],0,false)).'>'.esc_html__('- None -','bookingo').'</option>';
		foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
			echo '<option value="'.esc_attr($index).'" '.(BGCBSHelper::selectedIf($this->data['meta']['price_participant_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
?>
								</select>
							</div>
							<div>
								<span class="to-legend-field"><?php esc_html_e('Label displayed under the price.','bookingo'); ?></span>
								<input type="text" name="<?php BGCBSHelper::getFormName('price_label_under_price'); ?>" value="<?php echo esc_attr($this->data['meta']['price_label_under_price']); ?>"/>
							</div>	
							<div>
								<span class="to-legend-field"><?php esc_html_e('Label displayed instead of price (if the label is specified, payments are not available).','bookingo'); ?></span>
								<input type="text" name="<?php BGCBSHelper::getFormName('price_label_instead_price'); ?>" value="<?php echo esc_attr($this->data['meta']['price_label_instead_price']); ?>"/>
							</div>	
							<div>
								<span class="to-legend-field"><?php esc_html_e('Display net price.','bookingo'); ?></span>
								<div class="to-radio-button">
									<input type="radio" value="1" id="<?php BGCBSHelper::getFormName('price_net_display_enable_1'); ?>" name="<?php BGCBSHelper::getFormName('price_net_display_enable'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['price_net_display_enable'],1); ?>/>
									<label for="<?php BGCBSHelper::getFormName('price_net_display_enable_1'); ?>"><?php esc_html_e('Enable','bookingo'); ?></label>
									<input type="radio" value="0" id="<?php BGCBSHelper::getFormName('price_net_display_enable_0'); ?>" name="<?php BGCBSHelper::getFormName('price_net_display_enable'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['price_net_display_enable'],0); ?>/>
									<label for="<?php BGCBSHelper::getFormName('price_net_display_enable_0'); ?>"><?php esc_html_e('Disable','bookingo'); ?></label>
								</div>
							</div>
						</li> -->
 						<li>
							<h5><?php esc_html_e('Participants','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Define participants details.','bookingo'); ?></span>
							<div>
								<span class="to-legend-field"><?php esc_html_e('Enter number of participants.','bookingo'); ?></span>
								<input type="text" maxlength="5" name="<?php BGCBSHelper::getFormName('participant_number'); ?>" value="<?php echo esc_attr($this->data['meta']['participant_number']); ?>"/>
							</div>
							<div>
								<span class="to-legend-field"><?php esc_html_e('Show number of enrolled/all possible to enrolled participants.','bookingo'); ?></span>
								<div class="to-radio-button">
									<input type="radio" value="1" id="<?php BGCBSHelper::getFormName('participant_number_enable_1'); ?>" name="<?php BGCBSHelper::getFormName('participant_number_enable'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['participant_number_enable'],1); ?>/>
									<label for="<?php BGCBSHelper::getFormName('participant_number_enable_1'); ?>"><?php esc_html_e('Enable','bookingo'); ?></label>
									<input type="radio" value="0" id="<?php BGCBSHelper::getFormName('participant_number_enable_0'); ?>" name="<?php BGCBSHelper::getFormName('participant_number_enable'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['participant_number_enable'],0); ?>/>
									<label for="<?php BGCBSHelper::getFormName('participant_number_enable_0'); ?>"><?php esc_html_e('Disable','bookingo'); ?></label>
								</div>									
							</div>
						</li>
						<li>
							<h5><?php esc_html_e('Short description','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Short description of the course group.','bookingo'); ?></span>
							<div>
								<textarea name="<?php BGCBSHelper::getFormName('short_description'); ?>"><?php echo esc_html($this->data['meta']['short_description']); ?></textarea>
							</div>
						</li>
						<li>
							<h5><?php esc_html_e('Short info','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Contact info about the course group displayed in the right sidebar.','bookingo'); ?></span>
							<div>
								<textarea name="<?php BGCBSHelper::getFormName('short_info'); ?>"><?php echo esc_html($this->data['meta']['short_info']); ?></textarea>
							</div>
						</li>
 						<li>
							<h5><?php esc_html_e('Contact info','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Contact info displayed in the right sidebar.','bookingo'); ?></span>
							<div>
								<textarea name="<?php BGCBSHelper::getFormName('contact_info'); ?>"><?php echo esc_html($this->data['meta']['contact_info']); ?></textarea>
							</div>
						</li>
						<li>
							<h5><?php esc_html_e('Target page','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Target page opened after clicking on the group located on list.','bookingo'); ?></span>
							<div>
								<select name="<?php BGCBSHelper::getFormName('target_post_id'); ?>">
<?php
		echo '<option value="0" '.(BGCBSHelper::selectedIf($this->data['meta']['target_post_id'],0,false)).'>'.esc_html__('- None -','bookingo').'</option>';
		foreach($this->data['dictionary']['target_post'] as $index=>$value)
			echo '<option value="'.esc_attr($index).'" '.(BGCBSHelper::selectedIf($this->data['meta']['target_post_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
?>
								</select>
							</div>	
						</li>
					</ul>
				</div>
				<div id="meta-box-course-group-2">
					<ul class="to-form-field-list">
						<li>
							<h5><?php esc_html_e('Day of week','bookingo'); ?></h5>
							<span class="to-legend">
								<?php esc_html_e('Table includes schedule of the course group created based on days of week.','bookingo'); ?>
							</span>
							<div class="to-clear-fix">
								<table class="to-table" id="to-table-schedule-day">
									<tr>
										<th style="width:35%">
											<div>
												<?php esc_html_e('Day of week','bookingo'); ?>
												<span class="to-legend">
													<?php esc_html_e('Day of week.','bookingo'); ?>
												</span>
											</div>
										</th>
										<th style="width:25%">
											<div>
												<?php esc_html_e('Start time','bookingo'); ?>
												<span class="to-legend">
													<?php esc_html_e('Start time.','bookingo'); ?>
												</span>
											</div>
										</th>  
										<th style="width:25%">
											<div>
												<?php esc_html_e('End time','bookingo'); ?>
												<span class="to-legend">
													<?php esc_html_e('End time.','bookingo'); ?>
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
											<div class="to-clear-fix">
												<input type="hidden" name="<?php BGCBSHelper::getFormName('schedule_week_day[id][]'); ?>"/>
												<select name="<?php BGCBSHelper::getFormName('schedule_week_day[day][]'); ?>" class="to-dropkick-disable" id="schedule_week_day">
<?php
		for($i=1;$i<=7;$i++)
			echo '<option value="'.esc_attr($i).'">'.esc_html(date_i18n('l',strtotime('Sunday +'.$i.' days'))).'</option>';
?>				
												</select>
											</div>									
										</td>
										<td>
											<div class="to-clear-fix">
												<input type="text" class="to-timepicker-custom" name="<?php BGCBSHelper::getFormName('schedule_week_day[time_start][]'); ?>"/>
											</div>
										</td>	  
										<td>
											<div class="to-clear-fix">
												<input type="text" class="to-timepicker-custom" name="<?php BGCBSHelper::getFormName('schedule_week_day[time_stop][]'); ?>"/>
											</div>
										</td>  
										<td>
											<div>
												<a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','bookingo'); ?></a>
											</div>
										</td>   
									</tr>
<?php
		if(isset($this->data['meta']['schedule_week_day']))
		{
			foreach($this->data['meta']['schedule_week_day'] as $value)
			{
?>
									<tr>
										<td>
											<div class="to-clear-fix">
												<input type="hidden" name="<?php BGCBSHelper::getFormName('schedule_week_day[id][]'); ?>"/>
												<select name="<?php BGCBSHelper::getFormName('schedule_week_day[day][]'); ?>" id="<?php BGCBSHelper::getFormName('schedule_week_day_day_'.$value['id']); ?>" >
<?php
				for($i=1;$i<=7;$i++)
					echo '<option value="'.esc_attr($i).'" '.(BGCBSHelper::selectedIf($value['day'],$i,false)).'>'.esc_html(date_i18n('l',strtotime('Sunday +'.$i.' days'))).'</option>';
?>				
												</select>
											</div>									
										</td>
										<td>
											<div class="to-clear-fix">
												<input type="text" class="to-timepicker-custom" name="<?php BGCBSHelper::getFormName('schedule_week_day[time_start][]'); ?>" value="<?php echo esc_attr($Date->formatTimeToDisplay($value['time_start'])); ?>"/>
											</div>
										</td>	  
										<td>
											<div class="to-clear-fix">
												<input type="text" class="to-timepicker-custom" name="<?php BGCBSHelper::getFormName('schedule_week_day[time_stop][]'); ?>" value="<?php echo esc_attr($Date->formatTimeToDisplay($value['time_stop'])); ?>"/>
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
							<h5><?php esc_html_e('Dates','bookingo'); ?></h5>
							<span class="to-legend">
								<?php esc_html_e('Table includes schedule of the course group created based on dates.','bookingo'); ?>
							</span>
							<div class="to-clear-fix">
								<table class="to-table" id="to-table-schedule-date">
									<tr>
										<th style="width:35%">
											<div>
												<?php esc_html_e('Date','bookingo'); ?>
												<span class="to-legend">
													<?php esc_html_e('Date.','bookingo'); ?>
												</span>
											</div>
										</th>
										<th style="width:25%">
											<div>
												<?php esc_html_e('Start time','bookingo'); ?>
												<span class="to-legend">
													<?php esc_html_e('Start time.','bookingo'); ?>
												</span>
											</div>
										</th>  
										<th style="width:25%">
											<div>
												<?php esc_html_e('End time','bookingo'); ?>
												<span class="to-legend">
													<?php esc_html_e('End time.','bookingo'); ?>
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
											<div class="to-clear-fix">
												<input type="hidden" name="<?php BGCBSHelper::getFormName('schedule_date[id][]'); ?>"/>
												<input type="text" class="to-datepicker-custom" name="<?php BGCBSHelper::getFormName('schedule_date[date][]'); ?>"/>
											</div>									
										</td>
										<td>
											<div class="to-clear-fix">
												<input type="text" class="to-timepicker-custom" name="<?php BGCBSHelper::getFormName('schedule_date[time_start][]'); ?>"/>
											</div>
										</td>	  
										<td>
											<div class="to-clear-fix">
												<input type="text" class="to-timepicker-custom" name="<?php BGCBSHelper::getFormName('schedule_date[time_stop][]'); ?>"/>
											</div>
										</td>  
										<td>
											<div>
												<a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','bookingo'); ?></a>
											</div>
										</td>   
									</tr>
<?php
		if(isset($this->data['meta']['schedule_date']))
		{
			foreach($this->data['meta']['schedule_date'] as $value)
			{
?>
									<tr>
										<td>
											<div class="to-clear-fix">
												<input type="hidden" name="<?php BGCBSHelper::getFormName('schedule_date[id][]'); ?>"/>
												<input type="text" class="to-datepicker-custom" name="<?php BGCBSHelper::getFormName('schedule_date[date][]'); ?>" value="<?php echo esc_attr($Date->formatDateToDisplay($value['date'])); ?>"/>
											</div>									
										</td>
										<td>
											<div class="to-clear-fix">
												<input type="text" class="to-timepicker-custom" name="<?php BGCBSHelper::getFormName('schedule_date[time_start][]'); ?>" value="<?php echo esc_attr($Date->formatTimeToDisplay($value['time_start'])); ?>"/>
											</div>
										</td>	  
										<td>
											<div class="to-clear-fix">
												<input type="text" class="to-timepicker-custom" name="<?php BGCBSHelper::getFormName('schedule_date[time_stop][]'); ?>" value="<?php echo esc_attr($Date->formatTimeToDisplay($value['time_stop'])); ?>"/>
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
			</div>
		</div>
<?php
		wp_add_inline_script('bgcbs-admin',
		'
			jQuery(document).ready(function($)
			{	
				var element=$(\'.to\').themeOptionElement({init:true});

				/***/

				$(\'#to-table-schedule-day\').table();
				$(\'#to-table-schedule-date\').table();

				/***/

				var timeFormat=\''.BGCBSOption::getOption('time_format').'\';
				var dateFormat=\''.BGCBSJQueryUIDatePicker::convertDateFormat(BGCBSOption::getOption('date_format')).'\';

				toCreateCustomDateTimePicker(dateFormat,timeFormat);
				
				toCreateEditPostLink();

				/***/
			});
		');