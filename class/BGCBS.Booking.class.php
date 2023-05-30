<?php

/******************************************************************************/
/******************************************************************************/

class BGCBSBooking
{
	/**************************************************************************/
	
 	function __construct()
	{
 	 	
	}
 	 	
 	/**************************************************************************/
 	
 	public function init()
 	{
 	 	$this->registerCPT();
 	}
 	
	/**************************************************************************/

 	public static function getCPTName()
 	{
 	 	return(PLUGIN_BGCBS_CONTEXT.'_booking');
 	}
 	
 	/**************************************************************************/
 	
 	private function registerCPT()
 	{
		register_post_type
		(
 	 	 	self::getCPTName(),
 	 	 	array
 	 	 	(
 	 	 	 	'labels'=>array
 	 	 	 	(
 	 	 	 	 	'name'=>esc_html__('Bookings','bookingo'),
 	 	 	 	 	'singular_name'=>esc_html__('Booking','bookingo'),
 	 	 	 	 	'edit_item'=>esc_html__('Edit Booking','bookingo'),
 	 	 	 	 	'all_items'=>esc_html__('Bookings','bookingo'),
 	 	 	 	 	'view_item'=>esc_html__('View Booking','bookingo'),
 	 	 	 	 	'search_items'=>esc_html__('Search Bookings','bookingo'),
 	 	 	 	 	'not_found'=>esc_html__('No Bookings Found','bookingo'),
 	 	 	 	 	'not_found_in_trash'=>esc_html__('No Bookings Found in Trash','bookingo'), 
 	 	 	 	 	'parent_item_colon'=>'',
 	 	 	 	 	'menu_name'=>esc_html__('Bookingo','bookingo')
 	 	 	 	),	
 	 	 	 	'public'=>false,  
 	 	 	 	'menu_icon'=>'dashicons-calendar-alt',
 	 	 	 	'show_ui'=>true,  
 	 	 	 	'capability_type'=>'post',
 	 	 	 	'capabilities'=>array
 	 	 	 	(
 	 	 	 	 	 'create_posts'=>'do_not_allow',
 	 	 	 	),
 	 	 	 	'map_meta_cap'=>true, 
 	 	 	 	'menu_position'=>100,
 	 	 	 	'hierarchical'=>false,  
 	 	 	 	'rewrite'=>false,  
 	 	 	 	'supports'=>array('title')  
 	 	 	)
 	 	);
 	 	
 	 	add_action('save_post',array($this,'savePost'));
 	 	
 	 	add_action('add_meta_boxes_'.self::getCPTName(),array($this,'addMetaBox'));
 	 	add_filter('postbox_classes_'.self::getCPTName().'_bgcbs_meta_box_booking_form',array($this,'adminCreateMetaBoxClass'));
 	 	
		add_filter('manage_edit-'.self::getCPTName().'_columns',array($this,'manageEditColumns')); 
		add_action('manage_'.self::getCPTName().'_posts_custom_column',array($this,'managePostsCustomColumn'));
		add_filter('manage_edit-'.self::getCPTName().'_sortable_columns',array($this,'manageEditSortableColumns'));
 	 	
 	 	add_action('restrict_manage_posts',array($this,'restrictManagePosts'));
 	 	add_filter('parse_query',array($this,'parseQuery'));
 	}
 	
 	/**************************************************************************/
 	
 	function addMetaBox()
 	{
 	 	add_meta_box(PLUGIN_BGCBS_CONTEXT.'_meta_box_booking_form',esc_html__('Main','bookingo'),array($this,'addMetaBoxMain'),self::getCPTName(),'normal','low');
 	 	add_meta_box(PLUGIN_BGCBS_CONTEXT.'_meta_box_booking_form_woocommerce',esc_html__('WooCommerce','bookingo'),array($this,'addMetaBoxWooCommerce'),self::getCPTName(),'side','low');		
 	}
 	
 	/**************************************************************************/
 	
	function addMetaBoxMain()
	{
 	 	global $post;
 	 	 	 	
 	 	$data=$this->getBooking($post->ID);
 	 	 	
 	 	$data['nonce']=BGCBSHelper::createNonceField(PLUGIN_BGCBS_CONTEXT.'_meta_box_booking');
 	 	
		echo BGCBSTemplate::outputS($data,PLUGIN_BGCBS_TEMPLATE_PATH.'admin/meta_box_booking.php');
	}
	
	/**************************************************************************/
	
	function addMetaBoxWooCommerce()
	{
		global $post;
		
		$booking=$this->getBooking($post->ID);
		
		if(isset($booking['meta']['woocommerce_booking_id']) && (int)$booking['meta']['woocommerce_booking_id']>0)
		{
			echo 
			'
				<div>
					<div>'.esc_html__('This booking has corresponding wooCommerce order. Click on button below to see its details in new window.','bookingo').'</div>
					<br/>
					<a class="button button-primary" href="'.get_edit_post_link($booking['meta']['woocommerce_booking_id']).'" target="_blank">'.esc_html__('Open booking','bookingo').'</a>
				</div>
			';
		}
		else
		{
			echo 
			'
				<div>
					<div>'.esc_html__('This booking hasn\'t corresponding wooCommerce order.','bookingo').'</div>
				</div>
			';			
		}
	}
 	
 	/**************************************************************************/
 	
 	function getBooking($bookingId)
 	{
		$post=get_post($bookingId);
		if(is_null($post)) return(false);
 	 	
 	 	$booking=array();
 	 	
 	 	$BookingStatus=new BGCBSBookingStatus();
 	 	
 	 	$booking['post']=$post;
 	 	$booking['meta']=BGCBSPostMeta::getPostMeta($post);		
  
 	 	if($BookingStatus->isBookingStatus($booking['meta']['booking_status_id']))
 	 	{
 	 	 	$bookingStatus=$BookingStatus->getBookingStatus($booking['meta']['booking_status_id']);
 	 	 	$booking['booking_status_name']=$bookingStatus[0];
 	 	}
 	 	  
 	 	/***/
 	 	
 	 	$booking['dictionary']['booking_status']=$BookingStatus->getBookingStatus();
		
		$booking['billing']['value_net']=$booking['meta']['price_participant_value'];
		$booking['billing']['value_net_format']=BGCBSPrice::format($booking['meta']['price_participant_value'],$booking['meta']['currency']);
		
		$booking['billing']['value_gross']=BGCBSPrice::calculateGross($booking['meta']['price_participant_value'],0,$booking['meta']['price_participant_tax_rate_value'])	;
		$booking['billing']['value_gross_format']=BGCBSPrice::format($booking['billing']['value_gross'],$booking['meta']['currency']);
		
		$booking['billing']['value_tax']=$booking['billing']['value_gross']-$booking['billing']['value_net'];
		$booking['billing']['value_tax_format']=BGCBSPrice::format($booking['billing']['value_tax'],$booking['meta']['currency']);
		
		$booking['billing']['tax_rate_value']=$booking['meta']['price_participant_tax_rate_value'];
	
 	 	/***/
 	 	
 	 	return($booking);
 	}
 	
 	/**************************************************************************/
 	
 	function adminCreateMetaBoxClass($class) 
 	{
 	 	array_push($class,'to-postbox-1');
 	 	return($class);
 	}
 	
 	/**************************************************************************/
 	
 	function sendBooking($bookingForm)
 	{ 	  
		$Coupon=new BGCBSCoupon();
		$TaxRate=new BGCBSTaxRate();
		$Validation=new BGCBSValidation();
		$WooCommerce=new BGCBSWooCommerce();
		$BookingForm=new BGCBSBookingForm();
		$CourseGroup=new BGCBSCourseGroup();
		$BookingStatus=new BGCBSBookingStatus();
 	 	$CourseAgreement=new BGCBSCourseAgreement();
		$CourseFormElement=new BGCBSCourseFormElement();
		
		/***/
		
		$response=array();
		
		$data=BGCBSHelper::getPostOption();	
		
		$taxRateDictionary=$TaxRate->getDictionary();
		
		/***/
		
		if(!is_array($bookingForm=$BookingForm->checkBookingForm($data['booking_form_id'],$data['currency'],$bookingFormError)))
		{
			 $this->setErrorGlobal($response,$bookingFormError['message']);
			 BGCBSHelper::createJSONResponse($response);
		}
		
		/***/
		
		$BookingForm->validate($bookingForm,$data);	
		
		/***/
		
		$course=$bookingForm['course'][$bookingForm['course_id']];
		$courseGroup=$bookingForm['course_group'][$bookingForm['course_group_id']];	
		
 	 	$bookingId=wp_insert_post(array
 	 	(
 	 	 	'post_type'=>self::getCPTName(),
 	 	 	'post_status'=>'publish'
 	 	));
 	 	
 	 	if($bookingId===0) return(false);
 	 	
 	 	wp_update_post(array
 	 	(
 			'ID'=>$bookingId,
			'post_title'=>$this->getBookingTitle($bookingId) 	 	   
 	 	));
		
		BGCBSPostMeta::updatePostMeta($bookingId,'currency',$bookingForm['currency']);
		
		BGCBSPostMeta::updatePostMeta($bookingId,'booking_status_id',$bookingForm['booking_form'][$bookingForm['booking_form_id']]['meta']['booking_status_id_default']);
				
		BGCBSPostMeta::updatePostMeta($bookingId,'booking_form_id',$bookingForm['booking_form_id']);
		
		BGCBSPostMeta::updatePostMeta($bookingId,'course_id',$bookingForm['course_id']);
		BGCBSPostMeta::updatePostMeta($bookingId,'course_name',$course['post']->post_title);
		
		BGCBSPostMeta::updatePostMeta($bookingId,'course_group_id',$bookingForm['course_group_id']);
		BGCBSPostMeta::updatePostMeta($bookingId,'course_group_name',$courseGroup['post']->post_title);
 	 	
		BGCBSPostMeta::updatePostMeta($bookingId,'participant_first_name',$data['participant_first_name']);
		BGCBSPostMeta::updatePostMeta($bookingId,'participant_second_name',$data['participant_second_name']);
		
		BGCBSPostMeta::updatePostMeta($bookingId,'applicant_first_name',$data['applicant_first_name']);
		BGCBSPostMeta::updatePostMeta($bookingId,'applicant_second_name',$data['applicant_second_name']);		
		BGCBSPostMeta::updatePostMeta($bookingId,'applicant_email_address',$data['applicant_email_address']);		
		BGCBSPostMeta::updatePostMeta($bookingId,'applicant_phone_number',$data['applicant_phone_number']);		

		BGCBSPostMeta::updatePostMeta($bookingId,'coupon_id',-1);
		BGCBSPostMeta::updatePostMeta($bookingId,'coupon_code','');
		BGCBSPostMeta::updatePostMeta($bookingId,'coupon_discount_fixed',0);
		BGCBSPostMeta::updatePostMeta($bookingId,'coupon_discount_percentage',0);
		
		if(($coupon=$Coupon->checkCode($bookingForm))!==false)
		{
			BGCBSPostMeta::updatePostMeta($bookingId,'coupon_id',$coupon['post']->ID);
			BGCBSPostMeta::updatePostMeta($bookingId,'coupon_code',$coupon['post']->post_title);
			BGCBSPostMeta::updatePostMeta($bookingId,'coupon_discount_fixed',$coupon['meta']['discount_fixed']);
			BGCBSPostMeta::updatePostMeta($bookingId,'coupon_discount_percentage',$coupon['meta']['discount_percentage']);			
		}
		
		$price=$CourseGroup->calculatePrice($bookingForm);
		
		BGCBSPostMeta::updatePostMeta($bookingId,'price_participant_value',$price['calculate']['net']);
		BGCBSPostMeta::updatePostMeta($bookingId,'price_participant_tax_rate_id',$price['calculate']['tax_rate_id']);
		BGCBSPostMeta::updatePostMeta($bookingId,'price_participant_tax_rate_value',$TaxRate->getTaxRateValue($price['calculate']['tax_rate_id'],$taxRateDictionary));
		
		BGCBSPostMeta::updatePostMeta($bookingId,'price_label_instead_price',$courseGroup['meta']['price_label_instead_price']);
		
		if(((int)BGCBSOption::getOption('booking_status_sum_zero')===1) && ($price['calculate']['net']==0.00) && ($BookingStatus->isBookingStatus(BGCBSOption::getOption('booking_status_payment_success'))))
		{
			BGCBSPostMeta::updatePostMeta($bookingId,'booking_status_id',BGCBSOption::getOption('booking_status_payment_success'));
		}
		
		/***/
		
		$CourseAgreement->send($bookingId,$bookingForm,$data);
		$CourseFormElement->send($bookingId,$bookingForm,$data);
		
		/***/
		
		$this->sendEmailBooking($bookingId,$data['post_id']);
		
		/***/
		
 	 	if((int)$course['meta']['nexmo_sms_enable']===1)
 	 	{
 	 	 	$Nexmo=new BGCBSNexmo();
 	 	 	$Nexmo->sendSMS($course['meta']['nexmo_sms_api_key'],$course['meta']['nexmo_sms_api_key_secret'],$course['meta']['nexmo_sms_sender_name'],$course['meta']['nexmo_sms_recipient_phone_number'],$course['meta']['nexmo_sms_message']);
 	 	}
 	 	
 	 	if((int)$course['meta']['twilio_sms_enable']===1)
 	 	{
 	 	 	$Twilio=new BGCBSTwilio();
 	 	 	$Twilio->sendSMS($course['meta']['twilio_sms_api_sid'],$course['meta']['twilio_sms_api_token'],$course['meta']['twilio_sms_sender_phone_number'],$course['meta']['twilio_sms_recipient_phone_number'],$course['meta']['twilio_sms_message']);
 	 	}
 	 	
 		if($course['meta']['telegram_enable']==1)
 	 	{
 	 	 	$Telegram=new BGCBSTelegram();
 	 	 	$Telegram->sendMessage($course['meta']['telegram_token'],$course['meta']['telegram_group_id'],$course['meta']['telegram_message']);
 	 	}	
		
		/***/
		
		if($WooCommerce->isEnable($bookingForm['booking_form'][$bookingForm['booking_form_id']]['meta']))
		{
			$response['redirect_url']=$WooCommerce->sendBooking($bookingId);
				
			if($Validation->isNotEmpty($courseGroup['meta']['price_label_instead_price'])) unset($response['redirect_url']);
		}
		
		/***/		
		
		$response['booking_id']=$bookingId;
		
		if(($booking=$this->getBooking($bookingId)))
		{
			$response['value_gross_format']=$booking['billing']['value_gross_format'];
		}
		
		BGCBSHelper::createJSONResponse($response);
 	}
	
	/**************************************************************************/
	
	function validatePayBooking($bookingId,$validatePaymentCount=true,&$error=array())
	{
		$Validation=new BGCBSValidation();
		$BookingForm=new BGCBSBookingForm();
		$BookingStatus=new BGCBSBookingStatus();

		/***/

		if(($booking=$this->getBooking($bookingId))===false)
		{
			$error=array('id'=>1,'message'=>esc_html__('Cannot find booking with provided ID.','bookingo'));
			return(false);
		}

		/***/

		$bookingFormError=array();
		if(($bookingForm=$BookingForm->checkBookingForm($booking['meta']['booking_form_id'],null,$bookingFormError))===false)
		{
			$error=array('id'=>$bookingFormError['id'],'message'=>$bookingFormError['message']);
			return(false);
		}

		/***/

		$b=array_fill(0,2,false);

		$b[0]=array_key_exists($booking['meta']['course_id'],$bookingForm['course']);
		$b[1]=array_key_exists($booking['meta']['course_group_id'],$bookingForm['course_group']);

		if(in_array(false,$b,true))
		{
			$error=array('id'=>1,'message'=>esc_html__('Cannot find course or course group.','bookingo'));
			return(false);           
		}

		/***/

		if($validatePaymentCount)
		{
			$paymentDictionary=$bookingForm['dictionary']['payment'];
			if(!count($paymentDictionary))
			{
				$error=array('id'=>3,'message'=>esc_html__('Cannot find at least one payment method.','bookingo'));
				return(false);			
			}
		}

		/***/

		if(!in_array($booking['meta']['booking_status_id'],array(1,5,7)))
		{
			$error=array('id'=>4,'message'=>sprintf(esc_html__('It is not possible to pay for a booking with status %s.','bookingo'),$BookingStatus->getBookingStatusName($booking['meta']['booking_status_id'])));
			return(false);            
		}
		
		/***/
		
		if($Validation->isNotEmpty($booking['meta']['price_label_instead_price']))
		{
			$error=array('id'=>5,'message'=>esc_html__('It is not possible to pay for a booking, because the price is hidden.','bookingo'));
			return(false);            
		}		
		
		/***/
		
		if($booking['billing']['value_gross']==0)
		{
			$error=array('id'=>5,'message'=>esc_html__('Booking is paid.','bookingo'));
			return(false);            
		}		

		/***/

		return($bookingForm);
	}
	
	/**************************************************************************/
	
	function payBooking()
	{
		$Validation=new BGCBSValidation();
		$BookingForm=new BGCBSBookingForm();
		
		$response=array();
		
		$data=BGCBSHelper::getPostOption();
		
		$error=array();
		
		if(($bookingForm=$this->validatePayBooking($data['booking_id'],true,$error))===false)
		{
			$BookingForm->setErrorGlobal($response,$error['message']);  
			BGCBSHelper::createJSONResponse($response);			
		}
		
		$paymentDictionary=$bookingForm['dictionary']['payment'];
		if(!array_key_exists($data['payment_id'],$paymentDictionary))
		{
			$BookingForm->setErrorGlobal($response,esc_html__('Please select payment method.','booking'));  
			BGCBSHelper::createJSONResponse($response);					
		}
		
		BGCBSPostMeta::updatePostMeta($data['booking_id'],'payment_id',$data['payment_id']);
		BGCBSPostMeta::updatePostMeta($data['booking_id'],'payment_name',$paymentDictionary[$data['payment_id']]['post']->post_title);
		
		if(($booking=$this->getBooking($data['booking_id']))===false)
		{
			$BookingForm->setErrorGlobal($response,esc_html__('Booking cannot be found.','booking'));  
			BGCBSHelper::createJSONResponse($response);			
		}
		
		/***/
		
		$response['payment_type']=$paymentDictionary[$data['payment_id']]['meta']['payment_type'];
		
		switch($paymentDictionary[$data['payment_id']]['meta']['payment_type'])
		{
			case 1:

				if($Validation->isNotEmpty($paymentDictionary[$data['payment_id']]['meta']['payment_custom_success_url_address']))
					$response['redirect_url']=$paymentDictionary[$data['payment_id']]['meta']['payment_custom_success_url_address'];
				
			break;

			case 2:

				$PaymentStripe=new BGCBSPaymentStripe();
                
                $sessionId=$PaymentStripe->createSession($booking,$bookingForm);
                
                if($sessionId===false)
                {
                    $BookingForm->setErrorGlobal($response,esc_html__('There is an error during processing the payment.','booking'));  
                    BGCBSHelper::createJSONResponse($response);		                    
                }
                else
                {
                    $response['stripe_api_key_publishable']=$paymentDictionary[$data['payment_id']]['meta']['payment_stripe_api_key_publishable'];
                	$response['stripe_session_id']=$sessionId;
                }
                
			break;

			case 3:

				$PaymentPayPal=new BGCBSPaymentPayPal();

				$response['paypal_form']=$PaymentPayPal->createPaymentForm($data['post_id'],$booking,$bookingForm);

			break;
		}
		
		BGCBSHelper::createJSONResponse($response);
	}
	
	/**************************************************************************/
	
	function sendEmailBooking($bookingId,$postId=-1)
	{
		$Course=new BGCBSCourse();
		
		if(($booking=$this->getBooking($bookingId))===false) return(false);

		$courseDictionary=$Course->getDictionary();
		
		$courseId=$booking['meta']['course_id'];
		
		if(!array_key_exists($courseId,$courseDictionary)) return(false);
		
		$subject=sprintf(__('New booking "%s" is received','bookingo'),$this->getBookingTitle($bookingId));
 	 	
 	 	$recipient=array();
 	 	$recipient[0]=array($booking['meta']['applicant_email_address']);
 	 	$recipient[1]=preg_split('/;/',$courseDictionary[$courseId]['meta']['booking_new_recipient_email_address']);
 	 	
		global $bgcbs_logEvent;

		if((int)$courseDictionary[$courseId]['meta']['email_notification_booking_new_client_enable']===1)
		{
			$bgcbs_logEvent=1;
			$this->sendEmail($bookingId,$courseDictionary[$courseId]['meta']['booking_new_sender_email_account_id'],'booking_new_client',$recipient[0],$subject,$postId);
		}
	
		if((int)$courseDictionary[$courseId]['meta']['email_notification_booking_new_admin_enable']===1)
		{
			$bgcbs_logEvent=2;
			$this->sendEmail($bookingId,$courseDictionary[$courseId]['meta']['booking_new_sender_email_account_id'],'booking_new_admin',$recipient[1],$subject,$postId);
		}
	}
 	
	/**************************************************************************/
	
 	function sendEmail($bookingId,$emailAccountId,$template,$recipient,$subject,$postId=-1)
 	{
		$Payment=new BGCBSPayment();
		
 	 	$Email=new BGCBSEmail();
 	 	$EmailAccount=new BGCBSEmailAccount();
		
 	 	if(($booking=$this->getBooking($bookingId))===false) return(false);
 	 	
 	 	if(($emailAccount=$EmailAccount->getDictionary(array('email_account_id'=>$emailAccountId)))===false) return(false);
 	 	
 	 	if(!isset($emailAccount[$emailAccountId])) return(false);
 	 	
 	 	$data=array();
 	 	
 	 	$emailAccount=$emailAccount[$emailAccountId];
 	 	
 	 	/***/
 	 	
 	 	global $bgcbs_phpmailer;
 	 	
 	 	$bgcbs_phpmailer['sender_name']=$emailAccount['meta']['sender_name'];
 	 	$bgcbs_phpmailer['sender_email_address']=$emailAccount['meta']['sender_email_address'];
 	 	
 	 	$bgcbs_phpmailer['smtp_auth_enable']=$emailAccount['meta']['smtp_auth_enable'];
 	 	$bgcbs_phpmailer['smtp_auth_debug_enable']=$emailAccount['meta']['smtp_auth_debug_enable'];
 	 	
 	 	$bgcbs_phpmailer['smtp_auth_username']=$emailAccount['meta']['smtp_auth_username'];
 	 	$bgcbs_phpmailer['smtp_auth_password']=$emailAccount['meta']['smtp_auth_password'];
 	 	
 	 	$bgcbs_phpmailer['smtp_auth_host']=$emailAccount['meta']['smtp_auth_host'];
 	 	$bgcbs_phpmailer['smtp_auth_port']=$emailAccount['meta']['smtp_auth_port'];
 	 	
 	 	$bgcbs_phpmailer['smtp_auth_secure_connection_type']=$emailAccount['meta']['smtp_auth_secure_connection_type'];
 	 	
 	 	/***/
 	 	
 	 	if(in_array($template,array('booking_new_admin','booking_new_client','booking_change_status')))
 	 	{
 	 	 	$templateFile='email_booking.php';
 	 	 	
 	 	 	$booking['booking_title']=$booking['post']->post_title;
 	 	 	if($template==='booking_new_admin')
 	 	 	 	$booking['booking_title']='<a href="'.admin_url('post.php?post='.(int)$booking['post']->ID.'&action=edit').'">'.esc_html($booking['booking_title']).'</a>'; 	 	
 	 	}
		
 	 	/***/
		
		$data['booking']=$booking;
 	 	
 	 	$data['style']=$Email->getEmailStyle();
 	 	
		$data['payment_url']=$Payment->getPaymentLink($bookingId,$postId);
 	 	
 	 	/***/
 	 	 	 	
 	 	$Template=new BGCBSTemplate($data,PLUGIN_BGCBS_TEMPLATE_PATH.$templateFile);
 	 	$body=$Template->output();

 	 	/***/
		
 	 	$Email->send($recipient,$subject,$body);
 	}
    
	/**************************************************************************/

	function sendEmailBookingChangeStatus($bookingOld,$bookingNew)
	{
		if($bookingOld['meta']['booking_status_id']==$bookingNew['meta']['booking_status_id']) return;

		$BookingStatus=new BGCBSBookingStatus();
		$bookingStatus=$BookingStatus->getBookingStatus($bookingNew['meta']['booking_status_id']);

		$recipient=array();
		$recipient[0]=array($bookingNew['meta']['applicant_email_address']);

		$subject=sprintf(__('Booking "%s" has changed status to "%s"','bookingo'),$bookingNew['post']->post_title,$bookingStatus[0]);

		global $bgcbs_logEvent;

		$bgcbs_logEvent=3;
		$this->sendEmail($bookingNew['post']->ID,BGCBSOption::getOption('sender_default_email_account_id'),'booking_change_status',$recipient[0],$subject); 	 	   
	}
	
 	/**************************************************************************/
 	
 	function getBookingTitle($bookingId)
 	{
 	 	return(sprintf(esc_html__('Booking #%s','bookingo'),$bookingId));
 	}
 	
	/**************************************************************************/
 	
	function setPostMetaDefault(&$meta)
	{
		BGCBSHelper::setDefault($meta,'price_label_instead_price','');
	}
 	
 	/**************************************************************************/
 	
	function savePost($postId)
	{		
		if(!$_POST) return(false);

		if(BGCBSHelper::checkSavePost($postId,PLUGIN_BGCBS_CONTEXT.'_meta_box_booking_noncename','savePost')===false) return(false);

		$bookingOld=$this->getBooking($postId);

		$BookingStatus=new BGCBSBookingStatus();

		if($BookingStatus->isBookingStatus(BGCBSHelper::getPostValue('booking_status_id')))
		   BGCBSPostMeta::updatePostMeta($postId,'booking_status_id',BGCBSHelper::getPostValue('booking_status_id')); 

		$bookingNew=$this->getBooking($postId);

		$this->sendEmailBookingChangeStatus($bookingOld,$bookingNew);

		$WooCommerce=new BGCBSWooCommerce();
		$WooCommerce->changeStatus(-1,$postId);
	}

 	/**************************************************************************/
 	
 	function manageEditColumns($column)
 	{
 	 	$addColumn=array
 	 	(
 	 	 	'status'=>esc_html__('Booking status','bookingo'),
 	 	 	'course'=>esc_html__('Course','bookingo'),
 	 	 	'course_group'=>esc_html__('Course group','bookingo'),
 	 	 	'participant'=>esc_html__('Participant','bookingo'),
 	 	 	'university'=>'Universidad',
 	 	 	'date'=>$column['date']
 	 	);
   
 	 	unset($column['date']);
 	 	
 	 	foreach($addColumn as $index=>$value)
 	 	 	$column[$index]=$value;
 	 	
		return($column); 	 	  
 	}
 	
 	/**************************************************************************/
 	
 	function managePostsCustomColumn($column)
 	{
		global $post;
		
		$BookingStatus=new BGCBSBookingStatus();
 	 	
		$meta=BGCBSPostMeta::getPostMeta($post);
 	 	
		switch($column) 
		{
			case 'status':
				
 	 	 	 	$bookingStatus=$BookingStatus->getBookingStatus($meta['booking_status_id']);
 	 	 	 	echo '<div class="to-booking-status to-booking-status-'.(int)$meta['booking_status_id'].'">'.esc_html($bookingStatus[0]).'</div>';
 	 	 	 	
			break;
		
 	 	 	case 'course':
 	 	 	 	
				edit_post_link(esc_html($meta['course_name']),null,null,$meta['course_id'],'to-link-target-blank');
				
 	 	 	break;
		
 	 	 	case 'course_group':
 	 	 	 	
				edit_post_link(esc_html($meta['course_group_name']),null,null,$meta['course_group_id'],'to-link-target-blank');
 	 	 	 	
 	 	 	break;
 	 	
 	 	 	case 'participant':
 	 	 	 	
 	 	 	 	echo esc_html($meta['participant_first_name'].' '.$meta['participant_second_name']);
 	 	 	 	
 	 	 	break;
   
 	 	 	case 'university':
 	 	 	 	
				echo $this->getUniversityFromBooking($post);
 	 	 	 	
 	 	 	break;
 	 	}
 	}
 	
 	/**************************************************************************/
 	
 	function manageEditSortableColumns($column)
 	{
		return($column); 	   
 	}
 	
 	/**************************************************************************/
 	
	function restrictManagePosts()
	{
		if(!is_admin()) return;
		if(BGCBSHelper::getGetValue('post_type',false)!==self::getCPTName()) return; 	   

		$html=null;

		/***/

		$BookingStatus=new BGCBSBookingStatus();
		$bookingStatusDirectory=$BookingStatus->getBookingStatus();

		$directory=array();
		foreach($bookingStatusDirectory as $index=>$value)
			$directory[$index]=$value[0];

		asort($directory,SORT_STRING);

		if(!array_key_exists('booking_status_id',$_GET))
			$_GET['booking_status_id']=0;

		$htmlTemp=null;

		foreach($directory as $index=>$value)
			$htmlTemp.='<option value="'.(int)$index.'" '.(((int)BGCBSHelper::getGetValue('booking_status_id',false)==$index) ?  'selected' : null).'>'.esc_html($value).'</option>';

		$html=
		'
			<select name="booking_status_id">
				<option value="0">'.esc_html__('[All statuses]','bookingo').'</option>
				'.$htmlTemp.'
			</select>
		';

		/***/

		$htmlTemp=null;

		$course=$this->getBookingCourse();

		foreach($course as $courseIndex=>$courseGroup)
		{
			$htmlTemp.='<option value="'.esc_attr($courseIndex).'_0" '.((BGCBSHelper::getGetValue('course_group_id',false)==$courseIndex.'_0') ?  'selected' : null).'>- '.esc_html($courseGroup[-1]['name']).'</option>';

			foreach($courseGroup as $courseGroupIndex=>$courseGroupValue)
			{
				if($courseGroupIndex===-1) continue;
				$htmlTemp.='<option value="'.esc_attr($courseIndex.'_'.$courseGroupIndex).'" '.((BGCBSHelper::getGetValue('course_group_id',false)==$courseIndex.'_'.$courseGroupIndex) ?  'selected' : null).'>-- '.esc_html($courseGroupValue['name']).'</option>';
			}

		}

		$html.=
		'
			<select name="course_group_id">
				<option value="0_0">'.esc_html__('[All groups & courses]','bookingo').'</option>
				'.$htmlTemp.'
			</select>
		';

        /***/

        if(!array_key_exists('university',$_GET))
            $_GET['university']=-1;

        $htmlTemp=null;

        $cfe = new BGCBSCourseFormElement();
        $universities = $cfe->getUniversityList();

        foreach($universities as $index=>$value)
            $htmlTemp.='<option value="'.(int)$index.'" '.(((int)BGCBSHelper::getGetValue('university',false)==$index) ?  'selected' : null).'>'.$value.'</option>';


        $html.=
            '
			<select name="university">
				<option value="-1">'.'[Todas las universidades]'.'</option>
				'.$htmlTemp.'
			</select>
		';

		/***/

		echo $html;
	}

	/**************************************************************************/

	function parseQuery($query)
	{
		if(!is_admin()) return;
		if(BGCBSHelper::getGetValue('post_type',false)!==self::getCPTName()) return;
		if($query->query['post_type']!==self::getCPTName()) return; 	   

		$metaQuery=array();
		$Validation=new BGCBSValidation();

		/***/

		$bookingStatusId=BGCBSHelper::getGetValue('booking_status_id',false);
		if($Validation->isEmpty($bookingStatusId)) $bookingStatusId=-0;

		if($bookingStatusId!=0)
		{
			array_push($metaQuery,array
			(
				'key'=>PLUGIN_BGCBS_CONTEXT.'_booking_status_id',
				'value'=>array($bookingStatusId),
				'compare'=>'IN'
			));
		}  

		/***/

        $cgi = BGCBSHelper::getGetValue('course_group_id',false);

        if ($cgi) {
            list($courseId,$courseGroupId)=preg_split('/_/', $cgi);

            if((int)$courseId>0)
            {
                array_push($metaQuery,array
                (
                    'key'=>PLUGIN_BGCBS_CONTEXT.'_course_id',
                    'value'=>$courseId,
                    'compare'=>'IN'
                ));
            }

            if((int)$courseGroupId>0)
            {
                array_push($metaQuery,array
                (
                    'key'=>PLUGIN_BGCBS_CONTEXT.'_course_group_id',
                    'value'=>$courseGroupId,
                    'compare'=>'IN'
                ));
            }
        }

        $universityIndex=BGCBSHelper::getGetValue('university',false);
        if($Validation->isEmpty($universityIndex)) $universityIndex=-1;

        if($universityIndex!=-1)
        {
            $cfe = new BGCBSCourseFormElement();
            $universities = $cfe->getUniversityList();
            $encodedsearch = str_replace("a:1:{", "", serialize(["value"=>$universities[$universityIndex]]));
            array_push($metaQuery,array
            (
                'key'=>PLUGIN_BGCBS_CONTEXT.'_form_element_field',
                'value'=>$encodedsearch,
                'compare'=>'LIKE'
            ));
        }

        /***/

		$order=BGCBSHelper::getGetValue('order',false);
		$orderby=BGCBSHelper::getGetValue('orderby',false);

		if($orderby=='title')
		{
			$query->set('orderby','title');
		}
		elseif($orderby=='date')
		{
			$query->set('orderby','date');
		}

		$query->set('order',$order);

		if(count($metaQuery)) $query->set('meta_query',$metaQuery);
	}
	
	/**************************************************************************/
	
 	function getCouponCodeUsageCount($couponId)
 	{
 	 	$argument=array
		(
			'post_type'=>self::getCPTName(),
			'post_status'=>'publish',
			'posts_per_page'=>-1,
 	 	 	'meta_key'=>PLUGIN_BGCBS_CONTEXT.'_coupon_id',
 	 	 	'meta_value'=>$couponId,
 	 	 	'meta_compare'=>'='
		);
		
 	 	$query=new WP_Query($argument);
		if($query===false) return(false); 
 	 	
 	 	return($query->found_posts);
 	}
	
	/**************************************************************************/
	
	function getNumberParticipant($courseGroupId)
	{
		global $post;
		
		BGCBSHelper::preservePost($post,$bPost);
		
		$participant=array('registered'=>0,'confirmed'=>0);
		
 	 	$argument=array
		(
			'post_type'=>self::getCPTName(),
			'post_status'=>'publish',
			'posts_per_page'=>-1,
 	 	 	'meta_key'=>PLUGIN_BGCBS_CONTEXT.'_course_group_id',
 	 	 	'meta_value'=>$courseGroupId,
 	 	 	'meta_compare'=>'='
		);
		
 	 	$query=new WP_Query($argument);
		if($query===false) return($participant); 		
		
        $university = null;
        if(!current_user_can('administrator'))
        {
            $university = $this->getUniversityFromUser();
        }

		while($query->have_posts())
		{
			$query->the_post();
			
			$meta=BGCBSPostMeta::getPostMeta($post);
			
            if ($university)
            {
                $selecteduniversity = $this->getUniversityFromBooking($post);
                if ($university == $selecteduniversity) {
                    if((int)$meta['booking_status_id']===4) $participant['confirmed']++;
                    $participant['registered']++;
                }
            }
            else
            {
                if((int)$meta['booking_status_id']===4) $participant['confirmed']++;
                $participant['registered']++;
            }
		}
		
		BGCBSHelper::preservePost($post,$bPost,0);
		
		return($participant);
	}
    
	/**************************************************************************/

	function getBookingCourse()
	{
		global $post;

		$dictionary=array();

		BGCBSHelper::preservePost($post,$bPost);

		$argument=array
		(
			'post_type'=>self::getCPTName(),
			'post_status'=>'publish',
			'posts_per_page'=>-1
		);

		$query=new WP_Query($argument);
		if($query===false) return($dictionary); 		

		while($query->have_posts())
		{
			$query->the_post();

			$meta=BGCBSPostMeta::getPostMeta($post);

			$dictionary[$meta['course_id']][-1]=array('name'=>$meta['course_name']);
			$dictionary[$meta['course_id']][$meta['course_group_id']]=array('name'=>$meta['course_group_name']);
		}

		BGCBSHelper::preservePost($post,$bPost,0);

		return($dictionary);      
	}

    /**************************************************************************/

    static function getUniversityFromUser()
    {
        global $wpdb, $user_identity;
        $university = $wpdb->get_row($wpdb->prepare("SELECT company_name FROM {$wpdb->prefix}swpm_members_tbl WHERE user_name = %s", $user_identity));
        return $university->company_name;
    }

    /**************************************************************************/

    function getUniversityFromBooking($post)
    {
        $university = '';

        $meta = BGCBSPostMeta::getPostMeta($post);

        foreach($meta['form_element_field'] as $elementfield)
        {
            if (($elementfield['label'] == 'Universidad')) {
                $university = $elementfield['value'];
            }
        }

        return $university;
    }

	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/