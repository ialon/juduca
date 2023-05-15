		<ul class="to-form-field-list">
			<li>
				<h5><?php esc_html_e('Import demo','bookingo'); ?></h5>
				<span class="to-legend">
<?php
		if(BGCBSPlugin::isSwimAcademyTheme())
		{
			esc_html_e('Import demo for the plugin is not available, because your are using "Swim Academy" theme.','bookingo'); 
			echo '<br>';
			esc_html_e('You should use "Theme Demo Data Installer" to install entire dummy content.','bookingo'); 
		}
		else
		{
			esc_html_e('To import demo content, click on below button.','bookingo');
			echo '<br>';
			esc_html_e('You should run this function only once (the same content will be created when you run it once again).','bookingo');
			echo '<br>';
			esc_html_e('This operation takes a few minutes. This operation is not reversible.','bookingo');
		}
?>
				</span>
<?php
		if(!BGCBSPlugin::isSwimAcademyTheme())
		{
?>
				<input type="button" name="<?php BGCBSHelper::getFormName('import_dummy_content'); ?>" id="<?php BGCBSHelper::getFormName('import_dummy_content'); ?>" class="to-button to-margin-0" value="<?php esc_attr_e('Import','bookingo'); ?>"/>
<?php
		}
?>
			</li>
		</ul>
<?php
		wp_add_inline_script('bgcbs-admin',
		'
			jQuery(document).ready(function($) 
			{
				$(\'#'.BGCBSHelper::getFormName('import_dummy_content',false).'\').bind(\'click\',function(e) 
				{
					e.preventDefault();
					$(\'#action\').val(\''.PLUGIN_BGCBS_CONTEXT.'_option_page_import_demo\');
					$(\'#to_form\').submit();
					$(\'#action\').val(\''.PLUGIN_BGCBS_CONTEXT.'_option_page_save\');
				});
			});
		');