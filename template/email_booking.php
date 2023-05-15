<?php
		$Date=new BGCBSDate();
		$Validation=new BGCBSValidation();
		
		$CourseAgreement=new BGCBSCourseAgreement();
		$CourseFormElement=new BGCBSCourseFormElement();
		
		if((int)$this->data['document_header_exclude']!==1)
		{
?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">

			<head>
<?php
			if(is_rtl())
			{
?>
				<style>
					body
					{
						direction:rtl;
					}
				</style>
<?php		
			}
?>
			</head>

			<body>
<?php
		}
?>
				<table cellspacing="0" cellpadding="0" width="100%" bgcolor="#EEEEEE"<?php echo $this->data['style']['base']; ?>>
					
					<tr height="50px"><td></td></tr>
					
					<tr>
						
						<td>
							
							<table cellspacing="0" cellpadding="0" width="600px" border="0" align="center" bgcolor="#FFFFFF" style="border:solid 1px #E1E8ED;padding:50px">
							
								<!-- -->
<?php
		$logo=BGCBSOption::getOption('logo');
		if($Validation->isNotEmpty($logo))
		{
?>
								<tr>
									<td>
										<img style="max-width:100%;height:auto;" src="<?php echo esc_attr($logo); ?>" alt=""/>
										<br/><br/>
									</td>
								</tr>						   
<?php
		}
?>
								<!-- -->
								
								<tr>
									<td <?php echo $this->data['style']['header']; ?>><?php esc_html_e('General','bookingo'); ?></td>
								</tr>
								<tr><td <?php echo $this->data['style']['separator'][3]; ?>></td></tr>
								<tr>
									<td>
										<table cellspacing="0" cellpadding="0">
											<tr>
												<td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Title','bookingo'); ?></td>
												<td <?php echo $this->data['style']['cell'][2]; ?>><?php echo $this->data['booking']['booking_title']; ?></td>
											</tr>											
											<tr>
												<td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Status','bookingo'); ?></td>
												<td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['booking_status_name']); ?></td>
											</tr>
											<tr>
												<td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Course','bookingo'); ?></td>
												<td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['meta']['course_name']); ?></td>
											</tr>											
											<tr>
												<td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Course group','bookingo'); ?></td>
												<td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['meta']['course_group_name']); ?></td>
											</tr>	
<?php
		if($Validation->isNotEmpty($this->data['booking']['meta']['price_label_instead_price']))
		{
?>
											<tr>
												<td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Price','bookingo'); ?></td>
												<td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['meta']['price_label_instead_price']); ?></td>
											</tr>	
<?php
		}
		else
		{
?>
											<tr>
												<td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Net price','bookingo'); ?></td>
												<td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['billing']['value_net_format']); ?></td>
											</tr>											
											<tr>
												<td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Tax','bookingo'); ?></td>
												<td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['billing']['value_tax_format']); ?></td>
											</tr>													
											<tr>
												<td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Gross price','bookingo'); ?></td>
												<td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['billing']['value_gross_format']); ?></td>
											</tr>	
											<tr>
												<td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Payment','bookingo'); ?></td>
												<td <?php echo $this->data['style']['cell'][2]; ?>><a href="<?php echo esc_url($this->data['payment_url']); ?>" target="_blank"><?php esc_html_e('Pay for a booking','bookingo'); ?></a></td>
											</tr>
<?php
		}
?>
										</table>
									</td>
								</tr>
											
								<!-- -->
								
								<tr><td <?php echo $this->data['style']['separator'][2]; ?>></td></tr>
								<tr>
									<td <?php echo $this->data['style']['header']; ?>><?php esc_html_e('Participant','bookingo'); ?></td>
								</tr>
								<tr><td <?php echo $this->data['style']['separator'][3]; ?>></td></tr>
								<tr>
									<td>
										<table cellspacing="0" cellpadding="0">
											<tr>
												<td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('First name','bookingo'); ?></td>
												<td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['meta']['participant_first_name']); ?></td>
											</tr>
											<tr>
												<td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Second name','bookingo'); ?></td>
												<td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['meta']['participant_second_name']); ?></td>
											</tr>											
											<?php echo $CourseFormElement->display(1,$this->data['booking'],2,array('style'=>$this->data['style'])); ?>
										</table>
									</td>
								</tr>			
								
								<!-- -->
								
								<tr><td <?php echo $this->data['style']['separator'][2]; ?>></td></tr>
								<tr>
									<td <?php echo $this->data['style']['header']; ?>><?php esc_html_e('Applicant','bookingo'); ?></td>
								</tr>
								<tr><td <?php echo $this->data['style']['separator'][3]; ?>></td></tr>
								<tr>
									<td>
										<table cellspacing="0" cellpadding="0">
											<tr>
												<td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('First name','bookingo'); ?></td>
												<td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['meta']['applicant_first_name']); ?></td>
											</tr>
											<tr>
												<td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Second name','bookingo'); ?></td>
												<td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['meta']['applicant_second_name']); ?></td>
											</tr>	
											<tr>
												<td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('E-mail address','bookingo'); ?></td>
												<td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['meta']['applicant_email_address']); ?></td>
											</tr>	
											<tr>
												<td <?php echo $this->data['style']['cell'][1]; ?>><?php esc_html_e('Phone number','bookingo'); ?></td>
												<td <?php echo $this->data['style']['cell'][2]; ?>><?php echo esc_html($this->data['booking']['meta']['applicant_phone_number']); ?></td>
											</tr>	
											<?php echo $CourseFormElement->display(2,$this->data['booking'],2,array('style'=>$this->data['style'])); ?>
										</table>
									</td>
								</tr>	
											
								<!-- -->
<?php
		$panel=$CourseFormElement->getPanel($this->data['booking']['meta']);
		
		foreach($panel as $panelIndex=>$panelValue)
		{
			if(in_array($panelValue['id'],array(1,2))) continue;
?>
								<tr><td <?php echo $this->data['style']['separator'][2]; ?>></td></tr>
								<tr>
									<td <?php echo $this->data['style']['header']; ?>><?php echo esc_html($panelValue['label']); ?></td>
								</tr>
								<tr><td <?php echo $this->data['style']['separator'][3]; ?>></td></tr>
								<tr>
									<td>
										<table cellspacing="0" cellpadding="0">   
<?php
			echo $CourseFormElement->display($panelValue['id'],$this->data['booking'],2,array('style'=>$this->data['style']));
?>											
										</table>
									</td>
								</tr>
<?php
		}
		
		$html=$CourseAgreement->display($this->data['booking'],2);
		if($Validation->isNotEmpty($html))
		{
?>
								<tr><td <?php echo $this->data['style']['separator'][2]; ?>></td></tr>
								<tr>
									<td <?php echo $this->data['style']['header']; ?>><?php echo esc_html($panelValue['label']); ?></td>
								</tr>
								<tr><td <?php echo $this->data['style']['separator'][3]; ?>></td></tr>
								<tr>
									<td>
										<table cellspacing="0" cellpadding="0">  
											<?php echo $html; ?>
										</table>
									</td>
								</tr>
<?php
		}
?>
							</table>

						</td>

					</tr>
					
					<tr height="50px"><td></td></tr>
		
				</table> 
<?php
		if((int)$this->data['document_header_exclude']!==1)
		{
?>				
			</body>

		</html>
<?php
		}