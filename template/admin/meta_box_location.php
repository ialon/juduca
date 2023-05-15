<?php
		echo $this->data['nonce']; 

		$Validation=new BGCBSValidation();
?>		
		<div class="to">
			<div class="ui-tabs">
				<ul>
					<li><a href="#meta-box-location-1"><?php esc_html_e('Address','bookingo'); ?></a></li>
				</ul>
				<div id="meta-box-location-1">
					<ul class="to-form-field-list">
						<li>
							<h5><?php esc_html_e('Street','bookingo'); ?></h5>
							<span class="to-legend-field"><?php esc_html_e('Enter street name.','bookingo'); ?></span>
							<div class="to-clear-fix">
								<input type="text" name="<?php BGCBSHelper::getFormName('address_street'); ?>" id="<?php BGCBSHelper::getFormName('address_street'); ?>" value="<?php echo esc_attr($this->data['meta']['address_street']); ?>"/>
							</div>
						</li>
						<li>
							<h5><?php esc_html_e('Street number','bookingo'); ?></h5>
							<span class="to-legend-field"><?php esc_html_e('Enter street number.','bookingo'); ?></span>
							<div class="to-clear-fix">
								<input type="text" name="<?php BGCBSHelper::getFormName('address_street_number'); ?>" id="<?php BGCBSHelper::getFormName('address_street_number'); ?>" value="<?php echo esc_attr($this->data['meta']['address_street_number']); ?>"/>
							</div>
						</li>
						<li>
							<h5><?php esc_html_e('Postcode','bookingo'); ?></h5>
							<span class="to-legend-field"><?php esc_html_e('Enter postcode.','bookingo'); ?></span>
							<div class="to-clear-fix">
								<input type="text" name="<?php BGCBSHelper::getFormName('address_postcode'); ?>" id="<?php BGCBSHelper::getFormName('address_postcode'); ?>" value="<?php echo esc_attr($this->data['meta']['address_postcode']); ?>"/>
							</div>
						</li>
						<li>
							<h5><?php esc_html_e('City','bookingo'); ?></h5>
							<span class="to-legend-field"><?php esc_html_e('Enter city name.','bookingo'); ?></span>
							<div class="to-clear-fix">
								<input type="text" name="<?php BGCBSHelper::getFormName('address_city'); ?>" id="<?php BGCBSHelper::getFormName('address_city'); ?>" value="<?php echo esc_attr($this->data['meta']['address_city']); ?>"/>
							</div>
						</li>
						<li>
							<h5><?php esc_html_e('State','bookingo'); ?></h5>
							<span class="to-legend-field"><?php esc_html_e('Enter state name.','bookingo'); ?></span>
							<div class="to-clear-fix">
								<input type="text" name="<?php BGCBSHelper::getFormName('address_state'); ?>" id="<?php BGCBSHelper::getFormName('address_state'); ?>" value="<?php echo esc_attr($this->data['meta']['address_state']); ?>"/>
							</div>
						</li>
						<li>
							<h5><?php esc_html_e('Country','bookingo'); ?></h5>
							<span class="to-legend-field"><?php esc_html_e('Select country.','bookingo'); ?></span>
							<div class="to-clear-fix">
								<select name="<?php BGCBSHelper::getFormName('address_country'); ?>" id="<?php BGCBSHelper::getFormName('address_country'); ?>">
<?php
		foreach($this->data['dictionary']['country'] as $index=>$value)
			echo '<option value="'.esc_attr($index).'" '.(BGCBSHelper::selectedIf($this->data['meta']['address_country'],$index,false)).'>'.esc_html($value[0]).'</option>';
?>
								</select>
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
			});
		');