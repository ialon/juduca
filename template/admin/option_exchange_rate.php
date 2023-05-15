
		<ul class="to-form-field-list">
			<li>
				<h5><?php esc_html_e('Update','bookingo'); ?></h5>
				<span class="to-legend">
					<?php esc_html_e('Select exchange rate provider.','bookingo'); ?><br/>
					<?php esc_html_e('For the "Fixer.io" provider, you need to enter additional details in "General" tab.','bookingo'); ?><br/>
					<?php esc_html_e('All rates will be replaced during process of importing.','bookingo'); ?>
				</span>
				<div class="to-clear-fix">
					<select name="<?php BGCBSHelper::getFormName('exchange_rate_provider'); ?>" id="<?php BGCBSHelper::getFormName('exchange_rate_provider'); ?>">
<?php
						foreach($this->data['dictionary']['exchange_rate_provider'] as $index=>$value)
							echo '<option value="'.esc_attr($index).'">'.esc_html($value[0]).'</option>';
?>
					</select>
				</div>
			</li> 
			<li>
				<input type="button" name="<?php BGCBSHelper::getFormName('import_exchange_rate'); ?>" id="<?php BGCBSHelper::getFormName('import_exchange_rate'); ?>" class="to-button to-margin-0" value="<?php esc_attr_e('Import exchange rates','bookingo'); ?>"/>
			</li>			
			<li>
				<h5><?php esc_html_e('Rates table','bookingo'); ?></h5>
				<span class="to-legend">
					<?php esc_html_e('Enter an exchange rate for selected currencies in relation to base currency.','bookingo'); ?>
				</span>
				<div>
					<table class="to-table">
						<tr>
							<th style="width:50%">
								<div>
									<?php esc_html_e('Currency','bookingo'); ?>
									<span class="to-legend">
										<?php esc_html_e('Currency.','bookingo'); ?>
									</span>
								</div>
							</th>
							<th style="width:50%">
								<div>
									<?php esc_html_e('Exchange rate','bookingo'); ?>
									<span class="to-legend">
										<?php esc_html_e('Exchange rate.','bookingo'); ?>
									</span>
								</div>
							</th>
						</tr> 
<?php
		foreach($this->data['dictionary']['currency'] as $index=>$value)
		{
?>
						<tr>
							<td>
								<div>
									<?php echo esc_html__($value['name']).' <b>('.esc_html($value['symbol']).')</b>'; ?>
								</div>
							</td>
							<td>
								<div>
									<input type="text" value="<?php echo esc_attr(array_key_exists($index,(array)$this->data['option']['currency_exchange_rate']) ? $this->data['option']['currency_exchange_rate'][$index] : ''); ?>" name="<?php BGCBSHelper::getFormName('currency_exchange_rate['.$index.']'); ?>"/>
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
<?php
		wp_add_inline_script('bgcbs-admin',
		'
			jQuery(document).ready(function($)
			{	
				$(\'#'.BGCBSHelper::getFormName('import_exchange_rate',false).'\').bind(\'click\',function(e) 
				{
					e.preventDefault();
					$(\'#action\').val(\''.PLUGIN_BGCBS_CONTEXT.'_option_page_import_exchange_rate\');
					$(\'#to_form\').submit();
					$(\'#action\').val(\''.PLUGIN_BGCBS_CONTEXT.'_option_page_save\');
				});
			});
		');