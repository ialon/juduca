<?php 
		echo $this->data['nonce']; 
		
		$Date=new BGCBSDate();
		$Validation=new BGCBSValidation();
		
		$CourseAgreement=new BGCBSCourseAgreement();
		$CourseFormElement=new BGCBSCourseFormElement();
?>	
		<div class="to">
			<div class="ui-tabs">
				<ul>
					<li><a href="#meta-box-booking-1"><?php esc_html_e('General','bookingo'); ?></a></li>
					<li><a href="#meta-box-booking-2"><?php esc_html_e('Participant','bookingo'); ?></a></li>
					<li><a href="#meta-box-booking-3"><?php esc_html_e('Applicant','bookingo'); ?></a></li>
					<li><a href="#meta-box-booking-4"><?php esc_html_e('Form elements','bookingo'); ?></a></li>
					<li><a href="#meta-box-booking-5"><?php esc_html_e('Agreements','bookingo'); ?></a></li>
					<li><a href="#meta-box-booking-6"><?php esc_html_e('Payment','bookingo'); ?></a></li>
				</ul>
				<div id="meta-box-booking-1">
					<ul class="to-form-field-list">
						<?php echo BGCBSHelper::createPostIdField(esc_html__('Booking ID','bookingo')); ?>
						<li>
							<h5><?php esc_html_e('Status','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Booking status.','bookingo'); ?></span>
							<div class="to-radio-button">
<?php
		foreach($this->data['dictionary']['booking_status'] as $index=>$value)
		{
?>
								<input type="radio" value="<?php echo esc_attr($index); ?>" id="<?php BGCBSHelper::getFormName('booking_status_id_'.$index); ?>" name="<?php BGCBSHelper::getFormName('booking_status_id'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['booking_status_id'],$index); ?>/>
								<label for="<?php BGCBSHelper::getFormName('booking_status_id_'.$index); ?>"><?php echo esc_html($value[0]); ?></label>
<?php		
		}
?>
							</div>
						</li>	
						<li>
							<h5>
								<?php esc_html_e('Course','bookingo'); ?>
								<?php echo BGCBSHelper::createEditPostLink('Edit',$this->data['meta']['course_id']); ?>
							</h5>
							<span class="to-legend"><?php esc_html_e('Course name.','bookingo'); ?></span>
							<div class="to-field-disabled">
								<?php echo esc_html($this->data['meta']['course_name']) ?>
							</div>
						</li> 
						<li>
							<h5>
								<?php esc_html_e('Course group','bookingo'); ?>
								<?php echo BGCBSHelper::createEditPostLink('Edit',$this->data['meta']['course_group_id']); ?>
							</h5>
							<span class="to-legend"><?php esc_html_e('Course group.','bookingo'); ?></span>
							<div class="to-field-disabled">
								<?php echo esc_html($this->data['meta']['course_group_name']) ?>
							</div>
						</li> 
						<li>
							<h5><?php esc_html_e('Price','bookingo'); ?></h5>
							<span class="to-legend">
								<?php esc_html_e('Price details.','bookingo'); ?><br/>
							</span>
							<div class="to-clear-fix">
								<span class="to-legend-field"><?php esc_html_e('Net.','bookingo'); ?></span>
								<div class="to-field-disabled">
									<?php echo BGCBSPrice::format($this->data['meta']['price_participant_value'],$this->data['meta']['currency']); ?>
								</div>
							</div>
							<div class="to-clear-fix">
								<span class="to-legend-field"><?php esc_html_e('Gross.','bookingo'); ?></span>
								<div class="to-field-disabled">
									<?php echo BGCBSPrice::format(BGCBSPrice::calculateGross($this->data['meta']['price_participant_value'],0,$this->data['meta']['price_participant_tax_rate_value']),$this->data['meta']['currency']); ?>
								</div>
							</div>
							<div class="to-clear-fix">
								<span class="to-legend-field"><?php esc_html_e('Tax rate.','bookingo'); ?></span>
								<div class="to-field-disabled">
									<?php echo esc_html($this->data['meta']['price_participant_tax_rate_value'].'%'); ?>
									<div class="to-float-right"><?php edit_post_link(esc_html__('Edit','bookingo'),null,null,$this->data['meta']['price_participant_tax_rate_id'],'to-link-target-blank'); ?></div>
								</div>
							</div>
							<div class="to-clear-fix">
								<span class="to-legend-field"><?php esc_html_e('Label displayed instead of price','bookingo'); ?></span>
								<div class="to-field-disabled">
									<?php echo esc_html($this->data['meta']['price_label_instead_price']); ?>
								</div>
							</div>							
						</li>	
<?php
		if((int)$this->data['meta']['coupon_id']>0)
		{
?>
						<li>
							<h5><?php esc_html_e('Coupon','bookingo'); ?></h5>
							<span class="to-legend">
								<?php esc_html_e('Coupon details.','bookingo'); ?><br/>
							</span>
							<div class="to-clear-fix">
								<span class="to-legend-field"><?php esc_html_e('Code.','bookingo'); ?></span>
								<div class="to-field-disabled">
									<?php echo esc_html($this->data['meta']['coupon_code']) ?>
									<div class="to-float-right"><?php edit_post_link(esc_html__('Edit','bookingo'),null,null,$this->data['meta']['coupon_id'],'to-link-target-blank'); ?></div>
								</div>
							</div>
							<div class="to-clear-fix">
								<span class="to-legend-field"><?php esc_html_e('Percentage discount.','bookingo'); ?></span>
								<div class="to-field-disabled">
									<?php echo esc_html($this->data['meta']['coupon_discount_percentage']) ?>
								</div>
							</div>
							<div class="to-clear-fix">
								<span class="to-legend-field"><?php esc_html_e('Fixed discount.','bookingo'); ?></span>
								<div class="to-field-disabled">
									<?php echo esc_html($this->data['meta']['coupon_discount_fixed']) ?>
								</div>
							</div>
						</li>						
<?php
		}
?>
					</ul>
				</div>
				<div id="meta-box-booking-2">
					<ul class="to-form-field-list">
						<li>
							<h5><?php esc_html_e('Participant','bookingo'); ?></h5>
							<span class="to-legend">
								<?php esc_html_e('Participant details.','bookingo'); ?><br/>
							</span>
							<div class="to-clear-fix">
								<span class="to-legend-field"><?php esc_html_e('First name.','bookingo'); ?></span>
								<div class="to-field-disabled">
									<?php echo esc_html($this->data['meta']['participant_first_name']) ?>
								</div>
							</div>
							<div class="to-clear-fix">
								<span class="to-legend-field"><?php esc_html_e('Last name.','bookingo'); ?></span>
								<div class="to-field-disabled">
									<?php echo esc_html($this->data['meta']['participant_second_name']) ?>
								</div>
							</div>
							<?php echo $CourseFormElement->display(1,$this->data); ?>
						</li>
					</ul>
				</div>	
				<div id="meta-box-booking-3">
					<ul class="to-form-field-list">
						<li>
							<h5><?php esc_html_e('Applicant','bookingo'); ?></h5>
							<span class="to-legend">
								<?php esc_html_e('Applicant details.','bookingo'); ?><br/>
							</span>
							<div class="to-clear-fix">
								<span class="to-legend-field"><?php esc_html_e('First name.','bookingo'); ?></span>
								<div class="to-field-disabled">
									<?php echo esc_html($this->data['meta']['applicant_first_name']) ?>
								</div>
							</div>
							<div class="to-clear-fix">
								<span class="to-legend-field"><?php esc_html_e('Last name.','bookingo'); ?></span>
								<div class="to-field-disabled">
									<?php echo esc_html($this->data['meta']['applicant_second_name']) ?>
								</div>
							</div>
							<div class="to-clear-fix">
								<span class="to-legend-field"><?php esc_html_e('E-mail address.','bookingo'); ?></span>
								<div class="to-field-disabled">
									<?php echo esc_html($this->data['meta']['applicant_email_address']) ?>
								</div>
							</div>
							<div class="to-clear-fix">
								<span class="to-legend-field"><?php esc_html_e('Phone number.','bookingo'); ?></span>
								<div class="to-field-disabled">
									<?php echo esc_html($this->data['meta']['applicant_phone_number']) ?>
								</div>
							</div>
							<?php echo $CourseFormElement->display(2,$this->data); ?>
						</li> 
					</ul>					
				</div>
				<div id="meta-box-booking-4">
<?php
		$html=null;
		$panel=$CourseFormElement->getPanel($this->data['meta']);
		
		foreach($panel as $panelIndex=>$panelValue)
		{
			if(in_array($panelValue['id'],array(1,2))) continue;
			
			$html.=
			'
				<li>
					<h5>'.esc_html($panelValue['label']).'</h5>
					<span class="to-legend">'.esc_html($panelValue['label']).'.</span>							
					'.$CourseFormElement->display($panelValue['id'],$this->data).'
				</li>   
			';
		}
		
		if($Validation->isNotEmpty($html))
		{
			echo
			'
				<ul class="to-form-field-list">
					'.$html.'
				</ul>
			';
		}
		else
		{
?>
					<div class="to-notice-small to-notice-small-error">
						<?php esc_html_e('There are no form elements in this booking.') ?>
					</div>		
<?php			
		}
?>		
				</div>
				<div id="meta-box-booking-5">
<?php
		$html=$CourseAgreement->display($this->data,1);
		if($Validation->isNotEmpty($html))
		{
?>
					<ul class="to-form-field-list">
						<li>
							<h5><?php esc_html_e('Agreements','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('List of agreements from course.','bookingo'); ?></span>
							<div>
					
								<table class="to-table" id="to-table-agreement">
									<tr>
										<th style="width:90%">
											<div>
												<?php esc_html_e('Agreement','bookingo'); ?>
												<span class="to-legend">
													<?php esc_html_e('Text of agreement.','bookingo'); ?>
												</span>
											</div>
										</th>
										<th style="width:10%">
											<div>
												<?php esc_html_e('Answer','bookingo'); ?>
												<span class="to-legend">
													<?php esc_html_e('Applicant answer.','bookingo'); ?>
												</span>
											</div>
										</th>  
									</tr>
									<?php echo $html; ?>
								</table>
							</div>
						</li>
					</ul>
<?php
		}
		else
		{
?>
					<div class="to-notice-small to-notice-small-error">
						<?php esc_html_e('There are no agreements in this course.') ?>
					</div>		
<?php
		}
?>
				</div>
				<div id="meta-box-booking-6">
<?php
		if($Validation->isNotEmpty($this->data['meta']['payment_name']))
		{
?>
					<ul class="to-form-field-list">
						<li>
							<h5><?php esc_html_e('Payment details','bookingo'); ?></h5>
							<span class="to-legend">
								<?php esc_html_e('Payment details.','bookingo'); ?><br/>
							</span>
							<div>
								<span class="to-legend-field"><?php esc_html_e('Payment method:','bookingo'); ?></span>
								<div class="to-field-disabled"><?php echo esc_html($this->data['meta']['payment_name']) ?></div>								
							</div>
						</li>
						<li>
							<h5><?php esc_html_e('Transactions','bookingo'); ?></h5>
							<span class="to-legend">
								<?php esc_html_e('List of registered transactions for this payment.','bookingo'); ?><br/>
							</span>
<?php
				if(array_key_exists('payment_stripe_data',$this->data['meta']))
				{
					if((is_array($this->data['meta']['payment_stripe_data'])) && (count($this->data['meta']['payment_stripe_data'])))
					{
?>						
							<div>	
								<table class="to-table to-table-fixed-layout">
									 <thead>
										<tr>
											<th style="width:15%">
												<div>
													<?php esc_html_e('Transaction ID','bookingo'); ?>
													<span class="to-legend"><?php esc_html_e('Transaction ID.','bookingo'); ?></span>
												</div>
											</th>
											<th style="width:15%">
												<div>
													<?php esc_html_e('Type','bookingo'); ?>
													<span class="to-legend"><?php esc_html_e('Type.','bookingo'); ?></span>
												</div>
											</th>
											<th style="width:15%">
												<div>
													<?php esc_html_e('Date','bookingo'); ?>
													<span class="to-legend"><?php esc_html_e('Date.','bookingo'); ?></span>
												</div>
											</th>	
											<th style="width:55%">
												<div>
													<?php esc_html_e('Details','bookingo'); ?>
													<span class="to-legend"><?php esc_html_e('Status.','bookingo'); ?></span>
												</div>
											</th>
										</tr>
									</thead>	
									<tbody>
<?php
						foreach($this->data['meta']['payment_stripe_data'] as $index=>$value)
						{
?>
										<tr>
											<td><div><?php echo esc_html($value->id); ?></div></td>
											<td><div><?php echo esc_html($value->type); ?></div></td>
											<td><div><?php echo esc_html(date_i18n(BGCBSOption::getOption('date_format').' '.BGCBSOption::getOption('time_format'),$value->created)); ?></div></td>
											<td>
												<div class="to-toggle-details">
													<a href="#"><?php esc_html_e('Toggle details','bookingo'); ?></a>
													<div class="to-hidden">
														<pre>
															<?php var_dump($value); ?>
														</pre>
													</div>
												</div>
											</td>
										</tr>
<?php
						}
?>
									</tbody>
								</table>
							</div>
<?php						
					}
				}
				else if(array_key_exists('payment_paypal_data',$this->data['meta']))
				{
					if((is_array($this->data['meta']['payment_paypal_data'])) && (count($this->data['meta']['payment_paypal_data'])))
					{
?>

							<div>	
								<table class="to-table">
									<thead>
										<tr>
											<th style="width:15%">
												<div>
													<?php esc_html_e('Transaction ID','bookingo'); ?>
													<span class="to-legend"><?php esc_html_e('Transaction ID.','bookingo'); ?></span>
												</div>
											</th>
											<th style="width:15%">
												<div>
													<?php esc_html_e('Status','bookingo'); ?>
													<span class="to-legend"><?php esc_html_e('Type.','bookingo'); ?></span>
												</div>
											</th>
											<th style="width:15%">
												<div>
													<?php esc_html_e('Date','bookingo'); ?>
													<span class="to-legend"><?php esc_html_e('Date.','bookingo'); ?></span>
												</div>
											</th>	
											<th style="width:55%">
												<div>
													<?php esc_html_e('Details','bookingo'); ?>
													<span class="to-legend"><?php esc_html_e('Details.','bookingo'); ?></span>
												</div>
											</th>
										</tr>
									</thead>
									<tbody>
<?php
						foreach($this->data['meta']['payment_paypal_data'] as $index=>$value)
						{
?>
										<tr>
											<td><div><?php echo esc_html($value['txn_id']); ?></div></td>
											<td><div><?php echo esc_html($value['payment_status']); ?></div></td>
											<td><div><?php echo esc_html(date_i18n(BGCBSOption::getOption('date_format').' '.BGCBSOption::getOption('time_format'),$value['payment_date'])); ?></div></td>
											<td>
												<div class="to-toggle-details">
													<a href="#"><?php esc_html_e('Toggle details','bookingo'); ?></a>
													<div class="to-hidden">
														<pre>
															<?php var_dump($value); ?>
														</pre>
													</div>
												</div>
											</td>
										</tr>
<?php
						}
?>
									</tbody>
								</table>
							</div>
<?php				
					}
				}
?>
						</li>
					</ul>
<?php
		}
?>
				</div>
			</div>
		</div>
<?php
		wp_add_inline_script('bgcbs-admin',
		'
			jQuery(document).ready(function($)
			{	
				$(\'.to\').themeOptionElement({init:true});

				$(\'.to-toggle-details>a\').on(\'click\',function(e)
				{
					e.preventDefault();
					$(this).parents(\'td:first\').css(\'max-width\',\'0px\');
					$(this).next(\'div\').toggleClass(\'to-hidden\');
				});
			});
		');