<?php 
		echo $this->data['nonce']; 
		global $post;
?>	
		<div class="to">
			<div class="ui-tabs">
				<ul>
					<li><a href="#meta-box-booking-form-1"><?php esc_html_e('General','bookingo'); ?></a></li>
					<li><a href="#meta-box-booking-form-2"><?php esc_html_e('Look & feel','bookingo'); ?></a></li>
					<li><a href="#meta-box-booking-form-3"><?php esc_html_e('Styles','bookingo'); ?></a></li>
				</ul>
				<div id="meta-box-booking-form-1">
<?php
		if((int)$this->data['meta']['course_id']<=0)
		{
?>
					<div class="to-notice-small to-notice-small-error">
						<?php esc_html_e('You have to assign one course to this booking form. Otherwise booking form will not be displayed.') ?>
					</div>
<?php
		}
?>
					<ul class="to-form-field-list">
						<?php echo BGCBSHelper::createPostIdField(esc_html__('Booking form ID','bookingo')); ?>
						<li>
							<h5><?php esc_html_e('Shortcode','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Copy and paste the shortcode on a page.','bookingo'); ?></span>
							<div class="to-field-disabled">
<?php
$shortcode='['.PLUGIN_BGCBS_CONTEXT.'_booking_form booking_form_id="'.$post->ID.'"]';
echo esc_html($shortcode);
?>
								<a href="#" class="to-copy-to-clipboard to-float-right" data-clipboard-text="<?php echo esc_attr($shortcode); ?>" data-label-on-success="<?php esc_attr_e('Copied!','bookingo') ?>"><?php esc_html_e('Copy','bookingo'); ?></a>
							</div>
						</li> 
						<li>
							<h5>
								<?php esc_html_e('Course','bookingo'); ?>
								<?php echo BGCBSHelper::createAddPostLink(BGCBSCourse::getCPTName(),'Add new'); ?>
								<?php echo BGCBSHelper::createEditPostLink('Edit'); ?>
							</h5>
							<span class="to-legend">
								<?php esc_html_e('Select course.','bookingo'); ?>
							</span>
							<div class="to-radio-button">
								<input type="checkbox" value="-1" id="<?php BGCBSHelper::getFormName('course_id_0'); ?>" name="<?php BGCBSHelper::getFormName('course_id[]'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['course_id'],-1); ?>/>
								<label for="<?php BGCBSHelper::getFormName('course_id_0'); ?>"><?php esc_html_e('- None -','bookingo') ?></label>

<?php
		foreach($this->data['dictionary']['course'] as $index=>$value)
		{
?>
								<input type="radio" value="<?php echo esc_attr($index); ?>" id="<?php BGCBSHelper::getFormName('course_id_'.$index); ?>" name="<?php BGCBSHelper::getFormName('course_id'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['course_id'],$index); ?>/>
								<label for="<?php BGCBSHelper::getFormName('course_id_'.$index); ?>"><?php echo esc_html($value['post']->post_title); ?></label>
<?php		
		}
?>								
							</div>
						</li> 
						<li>
							<h5><?php esc_html_e('Default booking status','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Default booking status of new order.','bookingo'); ?></span>
							<div class="to-radio-button">
<?php
		foreach($this->data['dictionary']['booking_status'] as $index=>$value)
		{
?>
								<input type="radio" value="<?php echo esc_attr($index); ?>" id="<?php BGCBSHelper::getFormName('booking_status_id_default_'.$index); ?>" name="<?php BGCBSHelper::getFormName('booking_status_id_default'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['booking_status_id_default'],$index); ?>/>
								<label for="<?php BGCBSHelper::getFormName('booking_status_id_default_'.$index); ?>"><?php echo esc_html($value[0]); ?></label>
<?php		
		}	
?>								
							</div>
						</li>  		
						<li>
							<h5><?php esc_html_e('WooCommerce','bookingo'); ?></h5>
							<span class="to-legend">
								<?php esc_html_e('Enable or disable WooCommerce support for this booking form.','bookingo'); ?><br/>
								<?php echo sprintf(__('Please make sure that you set up "Checkout page" in <a href="%s">WooCommerce settings</a>','bookingo'),admin_url('admin.php?page=wc-settings&tab=advanced')); ?>
							</span>
							<div class="to-radio-button">
								<input type="radio" value="1" id="<?php BGCBSHelper::getFormName('woocommerce_enable_1'); ?>" name="<?php BGCBSHelper::getFormName('woocommerce_enable'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['woocommerce_enable'],1); ?>/>
								<label for="<?php BGCBSHelper::getFormName('woocommerce_enable_1'); ?>"><?php esc_html_e('Enable','bookingo'); ?></label>
								<input type="radio" value="0" id="<?php BGCBSHelper::getFormName('woocommerce_enable_0'); ?>" name="<?php BGCBSHelper::getFormName('woocommerce_enable'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['woocommerce_enable'],0); ?>/>
								<label for="<?php BGCBSHelper::getFormName('woocommerce_enable_0'); ?>"><?php esc_html_e('Disable','bookingo'); ?></label>
							</div>
						</li> 
						<li>
							<h5><?php esc_html_e('Currencies','bookingo'); ?></h5>
							<span class="to-legend">
								<?php esc_html_e('Select available currencies.','bookingo'); ?><br/>
								<?php esc_html_e('You can set exchange rates for each selected currency in plugin options.','bookingo'); ?><br/>
								<?php esc_html_e('You can run booking form with particular currency by adding parameter "currency=CODE" to the query string of page on which booking form is located.','bookingo'); ?>
							</span>						
							<div class="to-clear-fix">
								<select multiple="multiple" class="to-dropkick-disable" name="<?php BGCBSHelper::getFormName('currency[]'); ?>">
									<option value="-1" <?php BGCBSHelper::selectedIf($this->data['meta']['currency'],-1); ?>><?php esc_html_e('- None -','bookingo'); ?></option>
<?php
		foreach($this->data['dictionary']['currency'] as $index=>$value)
			echo '<option value="'.esc_attr($index).'" '.(BGCBSHelper::selectedIf($this->data['meta']['currency'],$index,false)).'>'.esc_html($value['name'].' ('.$index.')').'</option>';
?>
								</select>												
							</div>
						</li>
						<li>
							<h5><?php esc_html_e('Coupons','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Enable or disable coupons for this booking form.','bookingo'); ?></span>
							<div class="to-radio-button">
								<input type="radio" value="1" id="<?php BGCBSHelper::getFormName('coupon_enable_1'); ?>" name="<?php BGCBSHelper::getFormName('coupon_enable'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['coupon_enable'],1); ?>/>
								<label for="<?php BGCBSHelper::getFormName('coupon_enable_1'); ?>"><?php esc_html_e('Enable','bookingo'); ?></label>
								<input type="radio" value="0" id="<?php BGCBSHelper::getFormName('coupon_enable_0'); ?>" name="<?php BGCBSHelper::getFormName('coupon_enable'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['coupon_enable'],0); ?>/>
								<label for="<?php BGCBSHelper::getFormName('coupon_enable_0'); ?>"><?php esc_html_e('Disable','bookingo'); ?></label>
							</div>
						</li>	
					</ul>
				</div>
				<div id="meta-box-booking-form-2">
					<ul class="to-form-field-list">
						<li>
							<h5><?php esc_html_e('Form preloader','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Enable or disable form preloader.','bookingo'); ?></span>
							<div class="to-radio-button">
								<input type="radio" value="1" id="<?php BGCBSHelper::getFormName('form_preloader_enable_1'); ?>" name="<?php BGCBSHelper::getFormName('form_preloader_enable'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['form_preloader_enable'],1); ?>/>
								<label for="<?php BGCBSHelper::getFormName('form_preloader_enable_1'); ?>"><?php esc_html_e('Enable','bookingo'); ?></label>
								<input type="radio" value="0" id="<?php BGCBSHelper::getFormName('form_preloader_enable_0'); ?>" name="<?php BGCBSHelper::getFormName('form_preloader_enable'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['form_preloader_enable'],0); ?>/>
								<label for="<?php BGCBSHelper::getFormName('form_preloader_enable_0'); ?>"><?php esc_html_e('Disable','bookingo'); ?></label>
							</div>
						</li> 
						<li>
							<h5><?php esc_html_e('Default active tab','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Select which tab has to be active by default.','bookingo'); ?></span>
							<div class="to-radio-button">
								<input type="radio" value="0" id="<?php BGCBSHelper::getFormName('tab_active_0'); ?>" name="<?php BGCBSHelper::getFormName('tab_active'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['tab_active'],0); ?>/>
								<label for="<?php BGCBSHelper::getFormName('tab_active_0'); ?>"><?php esc_html_e('Overview','bookingo'); ?></label>
								<input type="radio" value="1" id="<?php BGCBSHelper::getFormName('tab_active_1'); ?>" name="<?php BGCBSHelper::getFormName('tab_active'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['tab_active'],1); ?>/>
								<label for="<?php BGCBSHelper::getFormName('tab_active_1'); ?>"><?php esc_html_e('Book a class','bookingo'); ?></label>
							</div>
						</li> 
						<li>
							<h5><?php esc_html_e('Course start/end time','bookingo'); ?></h5>
							<span class="to-legend"><?php esc_html_e('Show or hide course start/end time in the top bar.','bookingo'); ?></span>
							<div class="to-radio-button">
								<input type="radio" value="1" id="<?php BGCBSHelper::getFormName('course_time_enable_1'); ?>" name="<?php BGCBSHelper::getFormName('course_time_enable'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['course_time_enable'],1); ?>/>
								<label for="<?php BGCBSHelper::getFormName('course_time_enable_1'); ?>"><?php esc_html_e('Show','bookingo'); ?></label>
								<input type="radio" value="0" id="<?php BGCBSHelper::getFormName('course_time_enable_0'); ?>" name="<?php BGCBSHelper::getFormName('course_time_enable'); ?>" <?php BGCBSHelper::checkedIf($this->data['meta']['course_time_enable'],0); ?>/>
								<label for="<?php BGCBSHelper::getFormName('course_time_enable_0'); ?>"><?php esc_html_e('Hide','bookingo'); ?></label>
							</div>
						</li> 						
					</ul>							
				</div>
				<div id="meta-box-booking-form-3">
					<ul class="to-form-field-list">
						<li>
							<h5><?php esc_html_e('Colors','bookingo'); ?></h5>
							<span class="to-legend">
								<?php esc_html_e('Specify color for each group of elements.','bookingo'); ?>
							</span> 
							<div class="to-clear-fix">
								<table class="to-table">
									<tr>
										<th style="width:20%">
											<div>
												<?php esc_html_e('Group number','bookingo'); ?>
												<span class="to-legend">
													<?php esc_html_e('Group number.','bookingo'); ?>
												</span>
											</div>
										</th>
										<th style="width:30%">
											<div>
												<?php esc_html_e('Default color','bookingo'); ?>
												<span class="to-legend">
													<?php esc_html_e('Default value of the color.','bookingo'); ?>
												</span>
											</div>
										</th>
										<th style="width:50%">
											<div>
												<?php esc_html_e('Color','bookingo'); ?>
												<span class="to-legend">
													<?php esc_html_e('New value (in HEX) of the color.','bookingo'); ?>
												</span>
											</div>
										</th>
									</tr>
<?php
		foreach($this->data['dictionary']['color'] as $index=>$value)
		{
?>
									<tr>
										<td>
											<div><?php echo esc_html($index); ?>.</div>
										</td>
										<td>
											<div class="to-clear-fix">
												<span class="to-color-picker-sample to-color-picker-sample-style-1" style="background-color:#<?php echo esc_attr($value['default']); ?>"></span>
												<span><?php echo '#'.esc_html($value['default']); ?></span>
											</div>
										</td>
										<td>
											<div class="to-clear-fix">	
												 <input type="text" class="to-color-picker" id="<?php BGCBSHelper::getFormName('style_color_'.$index); ?>" name="<?php BGCBSHelper::getFormName('style_color['.$index.']'); ?>" value="<?php echo esc_attr($this->data['meta']['style_color'][$index]); ?>"/>
											</div>
										</td>
									</tr>
<?php
		}
?>
								</table>
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
				$(\'.to\').themeOptionElement({init:true});
				
				toCreateEditPostLink();
			});
		');