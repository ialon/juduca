<?php	
		global $post;
		
		$Validation=new BGCBSValidation();

		$class=array('bgcbs-main','bgcbs-booking-form-id-'.$this->data['booking_form_post_id'],'bgcbs-clear-fix','bgcbs-hidden');
?>
		<div<?php echo BGCBSHelper::createCSSClassAttribute($class); ?> id="<?php echo esc_attr($this->data['booking_form_html_id']); ?>">
			
			<form name="bgcbs-form" enctype="multipart/form-data">
			
				<div class="bgcbs-main-content">
<?php
		if((int)$this->data['booking_form_active']===1)
		{
			$class=array();
			if($this->data['step_active']!==1) array_push($class,'bgcbs-hidden');
?>
					<div id="<?php BGCBSHelper::getFormName('step_1'); ?>"<?php echo BGCBSHelper::createCSSClassAttribute($class); ?>> 
					
						<div class="bgcbs-main-content-top">
							<?php // echo $this->data['info_1_section']; ?>
						</div>	

						<div class="bgcbs-main-content-bottom">

							<div class="bgcbs-main-content-bottom-left">
								
								<?php echo $this->data['course_promo_section']; ?>
								
								<div class="ui-tabs">
									
									<ul>
										<li><a href="#booking-form-tab-1"><?php esc_html_e('Overview','bookingo'); ?></a></li>
										<li><a href="#booking-form-tab-2"><?php esc_html_e('Book a Class','bookingo'); ?></a></li>
									</ul>

									<div id="booking-form-tab-1">
										<?php echo apply_filters('the_content',$this->data['course'][$this->data['course_id']]['post']->post_content); ?>
									</div>

									<div id="booking-form-tab-2">
<?php
			$notice=null;
			$class=array('bgcbs-notice');
			
			BGCBSHelper::removeUIndex($this->data,'notice_1');

			if($Validation->isEmpty($this->data['notice_1'])) array_push($class,'bgcbs-hidden');
			else $notice=$this->data['notice_1'];
?>
                                        <div<?php echo BGCBSHelper::createCSSClassAttribute($class); ?>><?php echo esc_html($notice); ?></div>

										<?php echo $this->data['course_group_form']; ?>
										<?php echo $this->data['course_participant_form']; ?>
										<?php echo $this->data['course_applicant_form']; ?>
										<?php echo $this->data['course_custom_form']; ?>
										<?php echo $this->data['coupon_form']; ?>
										<?php echo $this->data['agreement_form']; ?>

										<div class="bgcbs-button bgcbs-button-send-booking">
											<a href="#"><?php esc_html_e('Sign Up','bookingo'); ?></a>
										</div>

									</div>

								</div>

							</div>						

							<div class="bgcbs-main-content-bottom-right">

								<?php echo $this->data['info_2_section']; ?>
								<?php echo $this->data['schedule_section']; ?>

							</div>	

						</div>
						
					</div>
<?php
			$class=array();
			if($this->data['step_active']!==2) array_push($class,'bgcbs-hidden');
?>
					<div id="<?php BGCBSHelper::getFormName('step_2'); ?>"<?php echo BGCBSHelper::createCSSClassAttribute($class); ?>> 
						
						<div class="bgcbs-payment-form">
							
							<div class="bgcbs-notice bgcbs-hidden"></div>
<?php
			if($Validation->isNotEmpty($this->data['course'][$this->data['course_id']]['meta']['thank_you_page_header_text']))
			{
?>
							<h2><?php echo esc_html($this->data['course'][$this->data['course_id']]['meta']['thank_you_page_header_text']); ?></h2>
<?php
			}
			
			if($Validation->isNotEmpty($this->data['course'][$this->data['course_id']]['meta']['thank_you_page_subheader_text']))
			{
?>
							<p><?php echo nl2br(esc_html($this->data['course'][$this->data['course_id']]['meta']['thank_you_page_subheader_text'])); ?></p>
<?php
			}

			if(($Validation->isEmpty($this->data['course_group'][$this->data['course_group_id']]['meta']['price_label_instead_price'])) && (count($this->data['dictionary']['payment'])) && (in_array($this->data['booking_form'][$this->data['booking_form_id']]['meta']['booking_status_id_default'],array(1,5,7))))
			{
?>
							<ul class="bgcbs-list-reset">
<?php
				foreach($this->data['dictionary']['payment'] as $index=>$value)
				{
?>	
								<li data-payment_id="<?php echo esc_attr($index); ?>">
<?php
					$image=get_the_post_thumbnail($index);
					if($Validation->isNotEmpty($image)) echo $image;
					else echo esc_html($value['post']->post_title);
?>
								</li>
<?php				
				}
?>
							</ul>
							
							<div class="bgcbs-button bgcbs-button-pay-booking">
								<a href="#"><?php _e('Pay <span></span> for the booking','bookingo'); ?></a>
							</div>
<?php			
			}
			else
			{
				if(($Validation->isNotEmpty($this->data['course'][$this->data['course_id']]['meta']['payment_form_button_1_label'])) && ($Validation->isNotEmpty($this->data['course'][$this->data['course_id']]['meta']['payment_form_button_1_url_address'])))
				{
?>
							<div class="bgcbs-button">
								<a href="<?php echo esc_url($this->data['course'][$this->data['course_id']]['meta']['payment_form_button_1_url_address']); ?>"><?php echo esc_html($this->data['course'][$this->data['course_id']]['meta']['payment_form_button_1_label']); ?></a>
							</div>                            
<?php
				}
			}
?>
							
						</div>
						
					</div>
<?php
		}
		else
		{   
			if($Validation->isNotEmpty($this->data['notice_3']))
			{
?>                
                    <div class="bgcbs-notice"><?php echo esc_html($this->data['notice_3']); ?></div>
<?php              
			}
		}
?>
									
				</div>
				
				<input type="hidden" name="action" value=""/>

				<input type="hidden" name="<?php BGCBSHelper::getFormName('currency'); ?>" value="<?php echo BGCBSRequest::get('currency'); ?>"/>
				
				<input type="hidden" name="<?php BGCBSHelper::getFormName('post_id'); ?>" value="<?php echo esc_attr($post->ID); ?>"/>
				
				<input type="hidden" name="<?php BGCBSHelper::getFormName('booking_id'); ?>" value="<?php echo BGCBSRequest::get('booking_id'); ?>"/>
				
				<input type="hidden" name="<?php BGCBSHelper::getFormName('booking_form_id'); ?>" value="<?php echo esc_attr($this->data['booking_form_id']); ?>"/>
				
			</form>
<?php
		if((int)$this->data['booking_form'][$this->data['booking_form_id']]['meta']['form_preloader_enable']===1)
		{
?>
			<div id="bgcbs-preloader"></div>
<?php
		}
?>
		</div>
<?php

        $plugin = new BGCBSPlugin();
        $plugin->publicInit();

		wp_add_inline_script('bgcbs-bookingo',
		'
			jQuery(document).ready(function($)
			{
				var bookingo=$(\'#'.esc_attr($this->data['booking_form_html_id']).'\').BGCBSBookingo(
				{
					booking_form_id												:	'.(int)$this->data['booking_form_id'].',
					plugin_version                                              :   \''.PLUGIN_BGCBS_VERSION.'\',
					ajax_url													:   \''.$this->data['ajax_url'].'\',
					plugin_url                                                  :   \''.PLUGIN_BGCBS_URL.'\',
					time_format                                                 :   \''.BGCBSOption::getOption('time_format').'\',
					date_format                                                 :   \''.BGCBSOption::getOption('date_format').'\',
					date_format_js                                              :   \''.BGCBSJQueryUIDatePicker::convertDateFormat(BGCBSOption::getOption('date_format')).'\',
					current_date												:   \''.date_i18n('d-m-Y').'\',
					current_time												:   \''.date_i18n('H:i').'\',
					tab_active													:	'.(int)$this->data['booking_form'][$this->data['booking_form_id']]['meta']['tab_active'].'			
				});

				bookingo.setup();
			});
		');
			