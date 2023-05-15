<?php

/******************************************************************************/
/******************************************************************************/

class BGCBSPlugin
{
	/**************************************************************************/
	
	private $optionDefault;
	private $libraryDefault;

	/**************************************************************************/	
	
	function __construct()
	{
		/***/
		
		$this->libraryDefault=array
		(
			'script'=>	array
			(
				'use'=>1,
				'inc'=>true,
				'path'=>PLUGIN_BGCBS_SCRIPT_URL,
				'file'=>'',
				'in_footer'=>true,
				'dependencies'=>array('jquery'),
			),
			'style'=>array
			(
				'use'=>1,
				'inc'=>true,
				'path'=>PLUGIN_BGCBS_STYLE_URL,
				'file'=>'',
				'dependencies'=>array()
			)
		);
		
		/***/
		
		$this->optionDefault=array
		(
			'logo'=>'',
			'currency'=>'USD',
			'date_format'=>'d-m-Y',
			'time_format'=>'G:i',
			'address_format'=>'1',
			'sender_default_email_account_id'=>'-1',
			'coupon_generate_count'=>'1',
			'coupon_generate_usage_limit'=>'1',
			'coupon_generate_discount_percentage'=>'0',
			'coupon_generate_discount_fixed'=>'0',
			'coupon_generate_active_date_start'=>'',
			'coupon_generate_active_date_stop'=>'',
			'currency_exchange_rate'=>array(),
			'fixer_io_api_key'=>'',
			'booking_status_payment_success'=>'-1',
			'booking_status_sum_zero'=>'0',
			'booking_status_synchronization'=>'1'
		);
		
		/***/
	}
	
	/**************************************************************************/
	
	private function prepareLibrary()
	{
		$this->library=array
		(
			'script'=>array
			(
				'jquery-ui-core'=>array
				(
					'path'=>''
				),
				'jquery-ui-tabs'=>array
				(
					'use'=> 3,
					'path'=>''
				),
				'jquery-ui-button'=>array
				(
					'path'=>''
				),
 				'jquery-ui-slider'=>array
				(
					'path'=>''
				),	
				'jquery-ui-selectmenu'=>array
				(
					'use'=> 2,
					'path'=>''
				),			   
				'jquery-ui-sortable'=>array
				(
					'path'=>''
				),
				'jquery-ui-widget'=>array
				(
					'use'=>2,
					'path'=>''
				),
				'jquery-ui-datepicker'=>array
				(
					'use'=>3,
					'path'=>''
				), 
				'jquery-colorpicker'=>array
				(
					'file'=>'jquery.colorpicker.js'
				),
				'jquery-actual'=>array
				(
					'use'=> 2,
					'file'=>'jquery.actual.min.js'
				),
				'jquery-circle-progress'=>array
				(
					'use'=>2,
					'file'=>'jquery.circle-progress.min.js'
				),
				'jquery-waypoints'=>array
				(
					'use'=>2,
					'file'=>'jquery.waypoints.min.js'
				),
				'jquery-timepicker'=>array
				(
					'use'=>3,
					'file'=>'jquery.timepicker.min.js'
				),
				'jquery-dropkick'=>array
				(
					'file'=>'jquery.dropkick.min.js'
				),
				'jquery-qtip'=>array
				(
					'use'=> 3,
					'file'=>'jquery.qtip.min.js'
				),
				'jquery-blockUI'=>array
				(
					'file'=>'jquery.blockUI.js'
				),
				'resizesensor'=>array
				(
					'use'=>2,
					'file'=>'ResizeSensor.min.js'
				),				
				'jquery-table'=>array
				(
					'file'=>'jquery.table.js'
				),	
				'jquery-infieldlabel'=>array
				(
					'file'=>'jquery.infieldlabel.min.js'
				),
				'jquery-slick'=>array
				(
					'use'=>2,
					'file'=>'slick.js'
				),  			
 				'jquery-scrollTo'=>array
				(
					'use'=>3,
					'file'=>'jquery.scrollTo.min.js'
				),  
 				'clipboard'=>array
				(
					'file'=>'clipboard.min.js'
				),	   
				'jquery-themeOption'=>array
				(
					'file'=>'jquery.themeOption.js'
				),
				'jquery-themeOptionElement'=>array
				(
					'file'=>'jquery.themeOptionElement.js'
				),
				'bgcbs-helper'=>array
				(
					'use'=>3,
					'file'=>'BGCBS.Helper.class.js'
				),
				'bgcbs-admin'=>array
				(
					'file'=>'admin.js'
				),
				'bgcbs-public'=>array
				(
					'use'=>2,
					'file'=>'public.js'
				),
				'bgcbs-bookingo'=>array
				(
					'use'=>2,
					'file'=>'jquery.BGCBSBookingo.js'
				),					
				'google-map'=>array
				(
					'use'=>3,
					'path'=>'',
					'file'=>add_query_arg(array('key'=>urlencode(BGCBSOption::getOption('google_map_api_key')),'libraries'=>'places,drawing'),'//maps.google.com/maps/api/js'),
				),	
			),
			'style'=>array
			(
				'google-font-open-sans'=>array
				(
					'path'=>'', 
					'file'=>add_query_arg(array('family'=>urlencode('Open Sans:300,300i,400,400i,600,600i,700,700i,800,800i'),'subset'=>'cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese'),'//fonts.googleapis.com/css')
				),
				'google-font-nunito'=>	array
				(
					'use'=> 2,
					'path'=>'', 
					'file'=>add_query_arg(array('family'=>urlencode('Nunito:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900'),'subset'=>''),'//fonts.googleapis.com/css')
				),
				'jquery-ui'=>array
				(
					'use'=>3,
					'file'=>'jquery.ui.min.css',
				),
				'jquery-qtip'=>array
				(
					'use'=>3,
					'file'=>'jquery.qtip.min.css',
				),
				'jquery-dropkick'=>array
				(
					'file'=>'jquery.dropkick.css',
				),
				'jquery-dropkick-rtl'=>array
				(				
					'inc'=>false,
					'file'=>'jquery.dropkick.rtl.css',
				),
				'jquery-colorpicker'=>array
				(
					'file'=>'jquery.colorpicker.css',
				),
				'jquery-timepicker'=>array
				(
					'use'=>3,
					'file'=>'jquery.timepicker.min.css',
				),
				'jquery-slick'=>array
				(
					'use'=>2,
					'file'=>'slick.css'
				),  
				'jquery-themeOption'=>array
				(
					'file'=>'jquery.themeOption.css'
				),
				'jquery-themeOption-rtl'=>array
				(
					'inc'=>false,
					'file'=>'jquery.themeOption.rtl.css',
				),
				'bgcbs-themeOption-overwrite'=>array
				(
					'file'=>'jquery.themeOption.overwrite.css'
				),
				'bgcbs-public'=>array
				(
					'use'=>2,
					'file'=>'public.css'
				),
				'bgcbs-public-rtl'=>array
				(
					'use'=>2,
					'inc'=>false,
					'file'=>'public.rtl.css'
				)
			)
		);		
	}	
	
	/**************************************************************************/
	
	private function addLibrary($type,$use)
	{
		if(BGCBSFile::fileExist(BGCBSFile::getMultisiteBlogCSS()))
		{
			$this->library['style']['bgcbs-public-booking-form-']=array
			(
				'use'=>2,
				'path'=>'',
				'file'=>BGCBSFile::getMultisiteBlogCSS('url')
			);
		}
		
		foreach($this->library[$type] as $index=>$value)
			$this->library[$type][$index]=array_merge($this->libraryDefault[$type],$value);
		
		foreach($this->library[$type] as $index=>$data)
		{
			if(!$data['inc']) continue;
			
			if($data['use']!=3)
			{
				if($data['use']!=$use) continue;
			}			
			
			if($type=='script')
			{
				wp_enqueue_script($index,$data['path'].$data['file'],$data['dependencies'],rand(1,10000),$data['in_footer']);
			}
			else 
			{
				wp_enqueue_style($index,$data['path'].$data['file'],$data['dependencies'],rand(1,10000));
			}
		}
	}
	
	/**************************************************************************/
	
	public function pluginActivation()
	{	
		BGCBSOption::createOption();
		
		$optionSave=array();
		$optionCurrent=BGCBSOption::getOptionObject();
			 
		foreach($this->optionDefault as $index=>$value)
		{
			if(!array_key_exists($index,$optionCurrent))
				$optionSave[$index]=$value;
		}
		
		$optionSave=array_merge((array)$optionSave,$optionCurrent);
		foreach($optionSave as $index=>$value)
		{
			if(!array_key_exists($index,$this->optionDefault))
				unset($optionSave[$index]);
		}
		
		BGCBSOption::resetOption();
		BGCBSOption::updateOption($optionSave);
		
		$BookingFormStyle=new BGCBSBookingFormStyle();
		$BookingFormStyle->createCSSFile();
	}
	
	/**************************************************************************/
	
	public function pluginDeactivation()
	{

	}
	
	/**************************************************************************/
	
	public function init()
	{  	
		$Booking=new BGCBSBooking();
		$BookingForm=new BGCBSBookingForm();
		
		$Course=new BGCBSCourse();
		$CourseGroup=new BGCBSCourseGroup();
		
		$PriceRule=new BGCBSPriceRule();
		
		$Location=new BGCBSLocation();
		
		$Payment=new BGCBSPayment();
		
		$Coupon=new BGCBSCoupon();
		
		$TaxRate=new BGCBSTaxRate();
		$EmailAccount=new BGCBSEmailAccount();
		
		$ExchangeRateProvider=new BGCBSExchangeRateProvider();
		
		$LogManager=new BGCBSLogManager();
		
		$BookingReport=new BGCBSBookingReport();
		
		$Booking->init();
		$BookingForm->init();
		
		$Course->init();
		$CourseGroup->init();
		
		$PriceRule->init();
		
		$Location->init();
		
		$Payment->init();
		
		$Coupon->init();
		
		$TaxRate->init();
		$EmailAccount->init();
		
		$BookingReport->init();
		
		add_action('admin_init',array($this,'adminInit'));
		add_action('admin_menu',array($this,'adminMenu'));
		
		add_action('wp_ajax_'.PLUGIN_BGCBS_CONTEXT.'_option_page_save',array($this,'adminOptionPanelSave'));
		
		add_action('wp_ajax_'.PLUGIN_BGCBS_CONTEXT.'_send_booking',array($Booking,'sendBooking'));
		add_action('wp_ajax_nopriv_'.PLUGIN_BGCBS_CONTEXT.'_send_booking',array($Booking,'sendBooking'));
		
		add_action('wp_ajax_'.PLUGIN_BGCBS_CONTEXT.'_pay_booking',array($Booking,'payBooking'));
		add_action('wp_ajax_nopriv_'.PLUGIN_BGCBS_CONTEXT.'_pay_booking',array($Booking,'payBooking'));
		
		add_action('wp_ajax_'.PLUGIN_BGCBS_CONTEXT.'_change_group',array($BookingForm,'changeGroupAjax'));
		add_action('wp_ajax_nopriv_'.PLUGIN_BGCBS_CONTEXT.'_change_group',array($BookingForm,'changeGroupAjax'));
		
		add_action('wp_ajax_'.PLUGIN_BGCBS_CONTEXT.'_apply_coupon',array($BookingForm,'applyCoupon'));
		add_action('wp_ajax_nopriv_'.PLUGIN_BGCBS_CONTEXT.'_apply_coupon',array($BookingForm,'applyCoupon'));		
		
		add_action('wp_ajax_'.PLUGIN_BGCBS_CONTEXT.'_option_page_import_demo',array($this,'importDemo'));
		add_action('wp_ajax_nopriv_'.PLUGIN_BGCBS_CONTEXT.'_option_page_import_demo',array($this,'importDemo'));
		
		add_action('wp_ajax_'.PLUGIN_BGCBS_CONTEXT.'_option_page_create_coupon_code',array($Coupon,'create'));
		add_action('wp_ajax_nopriv_'.PLUGIN_BGCBS_CONTEXT.'_option_page_create_coupon_code',array($Coupon,'create'));
		
		add_action('wp_ajax_'.PLUGIN_BGCBS_CONTEXT.'_option_page_import_exchange_rate',array($ExchangeRateProvider,'importExchangeRate'));
		add_action('wp_ajax_nopriv_'.PLUGIN_BGCBS_CONTEXT.'_option_page_import_exchange_rate',array($ExchangeRateProvider,'importExchangeRate'));
		
		add_action('wp_ajax_'.PLUGIN_BGCBS_CONTEXT.'_test_email_send',array($EmailAccount,'sendTestEmail'));
		add_action('wp_ajax_nopriv_'.PLUGIN_BGCBS_CONTEXT.'_test_email_send',array($EmailAccount,'sendTestEmail'));		
		
		add_action('admin_notices',array($this,'adminNotice'));
		
		add_action('wp_mail_failed',array($LogManager,'logWPMailError'));
		
		add_theme_support('post-thumbnails');
		
		if(!is_admin())
		{
			$PaymentStripe=new BGCBSPaymentStripe();
			
			add_action('wp_enqueue_scripts',array($this,'publicInit'));
			
			add_action('wp_loaded',array($PaymentStripe,'receivePayment'));
		}
		
		$WooCommerce=new BGCBSWooCommerce();
		$WooCommerce->addAction();
	}
	
	/**************************************************************************/

	public function publicInit()
	{
		$this->prepareLibrary();
		
		if(is_rtl())
			$this->library['style']['bgcbs-public-rtl']['inc']=true;
		
		$this->addLibrary('style',2);
		$this->addLibrary('script',2);
	}
	
	/**************************************************************************/
	
	public function adminInit()
	{
		$this->prepareLibrary();
		
		if(is_rtl())
		{
			$this->library['style']['jquery-themeOption-rtl']['inc']=true;
			$this->library['style']['jquery-dropkick-rtl']['inc']=true;
		}
		
		$this->addLibrary('style',1);
		$this->addLibrary('script',1);
		
		$data=array();
		
		$data['jqueryui_buttonset_enable']=(int)PLUGIN_BGCBS_JQUERYUI_BUTTONSET_ENABLE;
		
		wp_localize_script('jquery-themeOption','bgcbsData',array('l10n_print_after'=>'bgcbsData='.json_encode($data).';'));
	}
	
	/**************************************************************************/
	
	public function adminMenu()
	{
		global $submenu;

		add_options_page(esc_html__('Bookingo','bookingo'),esc_html__('Bookingo','bookingo'),'edit_theme_options',PLUGIN_BGCBS_CONTEXT,array($this,'adminCreateOptionPage'));
	}
	
	/**************************************************************************/
	
	public function adminCreateOptionPage()
	{
		$data=array();
		
		$Currency=new BGCBSCurrency();
		$EmailAccount=new BGCBSEmailAccount();
		$BookingStatus=new BGCBSBookingStatus();
		$ExchangeRateProvider=new BGCBSExchangeRateProvider();
		
		$data['option']=BGCBSOption::getOptionObject();
		
		$data['dictionary']['currency']=$Currency->getCurrency();
		
		$data['dictionary']['email_account']=$EmailAccount->getDictionary();
		
		$data['dictionary']['exchange_rate_provider']=$ExchangeRateProvider->getProvider();
		
		$data['dictionary']['booking_status']=$BookingStatus->getBookingStatus();
		$data['dictionary']['booking_status_synchronization']=$BookingStatus->getBookingStatusSynchronization();
		
		wp_enqueue_media();
		
                echo BGCBSTemplate::outputS($data,PLUGIN_BGCBS_TEMPLATE_PATH.'admin/option.php');
	}
	
	/**************************************************************************/
	
	public function adminOptionPanelSave()
	{		
		$option=BGCBSHelper::getPostOption();

		$response=array('global'=>array('error'=>1));

		$Notice=new BGCBSNotice();
		$Currency=new BGCBSCurrency();
		$Validation=new BGCBSValidation();
		$BookingStatus=new BGCBSBookingStatus();
		
		$invalidValue=esc_html__('This field includes invalid value.','bookingo');
		
		/* General */
		if(!$Currency->isCurrency($option['currency']))
			$Notice->addError(BGCBSHelper::getFormName('currency',false),$invalidValue);	
		if($Validation->isEmpty($option['date_format']))
			$Notice->addError(BGCBSHelper::getFormName('date_format',false),$invalidValue);
		if($Validation->isEmpty($option['time_format']))
			$Notice->addError(BGCBSHelper::getFormName('time_format',false),$invalidValue);
		if(!in_array($option['address_format'],array(1,2)))
			$Notice->addError(BGCBSHelper::getFormName('address_format',false),$invalidValue);	
		
		/* Payment */
		if((int)$option['booking_status_payment_success']!==-1)
		{
			if(!$BookingStatus->isBookingStatus($option['booking_status_payment_success']))
				$Notice->addError(BGCBSHelper::getFormName('booking_status_payment_success',false),$invalidValue);	
		}
		if(!$Validation->isBool($option['booking_status_sum_zero']))
			$Notice->addError(BGCBSHelper::getFormName('booking_status_sum_zero',false),$invalidValue);		
		if(!$BookingStatus->isBookingStatusSynchronization($option['booking_status_synchronization']))
			$Notice->addError(BGCBSHelper::getFormName('booking_status_synchronization',false),$invalidValue);	
		
		/* Currency */
		foreach($option['currency_exchange_rate'] as $index=>$value)
		{
			if(!$Currency->isCurrency($index))
			{
				unset($option['currency_exchange_rate'][$index]);
				continue;
			}
			
			if(!$Validation->isFloat($option['currency_exchange_rate'][$index],0,999999999.99,false,5))
			{
				unset($option['currency_exchange_rate'][$index]);
				continue;				
			}
			
			$option['currency_exchange_rate'][$index]=preg_replace('/,/','.',$value);
		}
		
		if($Notice->isError())
		{
			$response['local']=$Notice->getError();
		}
		else
		{
			$response['global']['error']=0;
			BGCBSOption::updateOption($option);
		}

		$response['global']['notice']=$Notice->createHTML(PLUGIN_BGCBS_TEMPLATE_PATH.'notice.php');

		echo json_encode($response);
		exit;
	}
	
	/**************************************************************************/
	
	function importDemo()
	{
		$Demo=new BGCBSDemo();
		$Notice=new BGCBSNotice();
		$Validation=new BGCBSValidation();
		
		$response=array('global'=>array('error'=>1));
		
		$buffer=$Demo->import();
		
		if($buffer!==false)
		{
			$response['global']['error']=0;
			$subtitle=esc_html__('Seems, that demo data has been imported. To make sure if this process has been successfully completed,please check below content of buffer returned by external applications.','bookingo');
		}
		else
		{
			$response['global']['error']=1;
			$subtitle=esc_html__('Dummy data cannot be imported.','bookingo');
		}
			
		$response['global']['notice']=$Notice->createHTML(PLUGIN_BGCBS_TEMPLATE_PATH.'admin/notice.php',true,$response['global']['error'],$subtitle);
		
		if($Validation->isNotEmpty($buffer))
		{
			$response['global']['notice'].=
			'
				<div class="to-buffer-output">
					'.$buffer.'
				</div>
			';
		}
		
		echo json_encode($response);
		exit;					
	}
		
	/**************************************************************************/
	
	function afterSetupTheme()
	{
		$VisualComposer=new BGCBSVisualComposer();
		$VisualComposer->init();
	}
	
	/**************************************************************************/
	
	function adminNotice()
	{

	}
	
	/**************************************************************************/
	
	static function isSwimAcademyTheme()
	{
		$theme=wp_get_theme();
		$themeName=strtolower($theme->get('Name'));

		$parentTheme=is_object($theme->parent()) ? $theme->parent() : null;
		$parentThemeName=strtolower(is_null($parentTheme) ? null : $parentTheme->get('Name'));

		if(($themeName=='swimacademy') || ($parentThemeName=='swimacademy')) return(true);	
		
		return(false);
	}
	
	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/