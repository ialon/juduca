<?php

/******************************************************************************/
/******************************************************************************/

class BGCBSCourse
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
		return(PLUGIN_BGCBS_CONTEXT.'_course');
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
					'name'=>esc_html__('Courses','bookingo'),
					'singular_name'=>esc_html__('Course','bookingo'),
					'add_new'=>esc_html__('Add New','bookingo'),
					'add_new_item'=>esc_html__('Add New Course','bookingo'),
					'edit_item'=>esc_html__('Edit Course','bookingo'),
					'new_item'=>esc_html__('New Course','bookingo'),
					'all_items'=>esc_html__('Courses','bookingo'),
					'view_item'=>esc_html__('View Course','bookingo'),
					'search_items'=>esc_html__('Search Course','bookingo'),
					'not_found'=>esc_html__('No Course Found','bookingo'),
					'not_found_in_trash'=>esc_html__('No Course in Trash','bookingo'), 
					'parent_item_colon'=>'',
					'menu_name'=>esc_html__('Course','bookingo')
				),	
				'public'=>false,  
				'show_ui'=>true, 
				'show_in_menu'=>'edit.php?post_type='.BGCBSBooking::getCPTName(),
				'capability_type'=>'post',
				'menu_position'=>2,
				'hierarchical'=>false,  
				'rewrite'=>false,  
				'supports'=>array('editor','title')  
			)
		);
		
		add_action('save_post',array($this,'savePost'));
		add_action('add_meta_boxes_'.self::getCPTName(),array($this,'addMetaBox'));
		add_filter('postbox_classes_'.self::getCPTName().'_bgcbs_meta_box_course',array($this,'adminCreateMetaBoxClass'));
		
		add_filter('manage_edit-'.self::getCPTName().'_columns',array($this,'manageEditColumns')); 
		add_action('manage_'.self::getCPTName().'_posts_custom_column',array($this,'managePostsCustomColumn'));
		add_filter('manage_edit-'.self::getCPTName().'_sortable_columns',array($this,'manageEditSortableColumns'));
	}

	/**************************************************************************/
	
	function addMetaBox()
	{
		add_meta_box(PLUGIN_BGCBS_CONTEXT.'_meta_box_course',esc_html__('Main','bookingo'),array($this,'addMetaBoxMain'),self::getCPTName(),'normal','low');		
	}
	
	/**************************************************************************/
	
	function addMetaBoxMain()
	{
		global $post;
		
		$Payment=new BGCBSPayment();
		$CourseGroup=new BGCBSCourseGroup();
		$EmailAccount=new BGCBSEmailAccount();
		$CourseFormElement=new BGCBSCourseFormElement();
		
		$data=array();
		
		$data['meta']=BGCBSPostMeta::getPostMeta($post);
		
		$data['nonce']=BGCBSHelper::createNonceField(PLUGIN_BGCBS_CONTEXT.'_meta_box_course');
		
		$data['dictionary']['payment']=$Payment->getDictionary();
		
		$data['dictionary']['course_group']=$CourseGroup->getDictionary();
		
		$data['dictionary']['email_account']=$EmailAccount->getDictionary();
		
		$data['dictionary']['field_type']=$CourseFormElement->getFieldType();
		$data['dictionary']['form_element_panel']=$CourseFormElement->getPanel($data['meta']);
		
		echo BGCBSTemplate::outputS($data,PLUGIN_BGCBS_TEMPLATE_PATH.'admin/meta_box_course.php');
	}
	
	 /**************************************************************************/
	
	function adminCreateMetaBoxClass($class) 
	{
		array_push($class,'to-postbox-1');
		return($class);
	}

	/**************************************************************************/
	
	function setPostMetaDefault(&$meta)
	{
		BGCBSHelper::setDefault($meta,'course_group_id',array(-1));
		
		BGCBSHelper::setDefault($meta,'registration_period_start_date','');
		BGCBSHelper::setDefault($meta,'registration_period_start_time','');
		BGCBSHelper::setDefault($meta,'registration_period_end_date','');
		BGCBSHelper::setDefault($meta,'registration_period_end_time','');		
		
		BGCBSHelper::setDefault($meta,'applicant_data_enable',1);
		
		BGCBSHelper::setDefault($meta,'lesson_number',1);
		BGCBSHelper::setDefault($meta,'lesson_length',60);
		
		BGCBSHelper::setDefault($meta,'payment_id',array(-1));
		
		BGCBSHelper::setDefault($meta,'booking_new_sender_email_account_id',-1);
		BGCBSHelper::setDefault($meta,'booking_new_recipient_email_address','');
		
		BGCBSHelper::setDefault($meta,'email_notification_booking_new_client_enable',1);
		BGCBSHelper::setDefault($meta,'email_notification_booking_new_admin_enable',1);
		
		BGCBSHelper::setDefault($meta,'nexmo_sms_enable',0);
		BGCBSHelper::setDefault($meta,'nexmo_sms_api_key','');
		BGCBSHelper::setDefault($meta,'nexmo_sms_api_key_secret','');
		BGCBSHelper::setDefault($meta,'nexmo_sms_sender_name','');
		BGCBSHelper::setDefault($meta,'nexmo_sms_recipient_phone_number','');
		BGCBSHelper::setDefault($meta,'nexmo_sms_message',esc_html__('New booking has been received.','bookingo'));
	 
		BGCBSHelper::setDefault($meta,'twilio_sms_enable',0);
		BGCBSHelper::setDefault($meta,'twilio_sms_api_sid','');
		BGCBSHelper::setDefault($meta,'twilio_sms_api_token','');
		BGCBSHelper::setDefault($meta,'twilio_sms_sender_phone_number','');
		BGCBSHelper::setDefault($meta,'twilio_sms_recipient_phone_number','');
		BGCBSHelper::setDefault($meta,'twilio_sms_message',esc_html__('New booking has been received.','bookingo'));
		
		BGCBSHelper::setDefault($meta,'telegram_enable',0);
		BGCBSHelper::setDefault($meta,'telegram_token','');
		BGCBSHelper::setDefault($meta,'telegram_group_id','');
		BGCBSHelper::setDefault($meta,'telegram_message',esc_html__('New booking has been received.','bookingo'));	
		
		BGCBSHelper::setDefault($meta,'promo_image','');
		BGCBSHelper::setDefault($meta,'promo_video_embed_code','');
		
		BGCBSHelper::setDefault($meta,'thank_you_page_header_text',esc_html__('Thank you for sending your booking!','bookingo'));
		BGCBSHelper::setDefault($meta,'thank_you_page_subheader_text','');
        
		BGCBSHelper::setDefault($meta,'payment_form_button_1_label',esc_html__('Back To Home','bookingo'));
		BGCBSHelper::setDefault($meta,'payment_form_button_1_url_address','');
	}
	
	/**************************************************************************/
	
	function savePost($postId)
	{	  
		if(!$_POST) return(false);
		
		if(BGCBSHelper::checkSavePost($postId,PLUGIN_BGCBS_CONTEXT.'_meta_box_course_noncename','savePost')===false) return(false);
		
		$option=BGCBSHelper::getPostOption();
		
		$Date=new BGCBSDate();
		$Payment=new BGCBSPayment();
		$Validation=new BGCBSValidation();
		$CourseGroup=new BGCBSCourseGroup();
		$EmailAccount=new BGCBSEmailAccount();
		$CourseAgreement=new BGCBSCourseAgreement();
		$CourseFormElement=new BGCBSCourseFormElement();
		
		/***/
		
		$key=array
		(
			'course_group_id',
			'registration_period_start_date',
			'registration_period_start_time',
			'registration_period_end_date',
			'registration_period_end_time',	
			'applicant_data_enable',
			'lesson_number',
			'lesson_length',
			'payment_id',
			'booking_new_sender_email_account_id',
			'booking_new_recipient_email_address',
			'email_notification_booking_new_client_enable',
			'email_notification_booking_new_admin_enable',
			'nexmo_sms_enable',
			'nexmo_sms_api_key',
			'nexmo_sms_api_key_secret',
			'nexmo_sms_sender_name',
			'nexmo_sms_recipient_phone_number',
			'nexmo_sms_message',
			'twilio_sms_enable',
			'twilio_sms_api_sid',
			'twilio_sms_api_token',
			'twilio_sms_sender_phone_number',
			'twilio_sms_recipient_phone_number',
			'twilio_sms_message',
			'telegram_enable',
			'telegram_token',
			'telegram_group_id',
			'telegram_message',
			'schedule_week_day',
			'schedule_date',
			'promo_image',
			'promo_video_embed_code',
			'thank_you_page_header_text',
			'thank_you_page_subheader_text',
			'payment_form_button_1_label',
			'payment_form_button_1_url_address'
		);

		/***/
		
		$courseGroup=$CourseGroup->getDictionary();
		if(in_array(-1,$option['course_group_id']))
		{
			$option['course_group_id']=array(-1);
		}
		else
		{
			foreach($option['course_group_id'] as $index=>$value)
			{
				if(!isset($courseGroup[$value]))
					unset($option['course_group_id'][$index]);				
			}
		}  
		
		/***/
		
		$option['registration_period_start_date']=$Date->formatDateToStandard($option['registration_period_start_date']);
		$option['registration_period_start_time']=$Date->formatTimeToStandard($option['registration_period_start_time']);
		$option['registration_period_end_date']=$Date->formatDateToStandard($option['registration_period_end_date']);
		$option['course_group_end_time']=$Date->formatTimeToStandard($option['course_group_end_time']);		
		
		if(!$Validation->isDate($option['registration_period_start_date']))
			$option['registration_period_start_date']='';
		if(!$Validation->isTime($option['registration_period_start_time']))
			$option['registration_period_start_time']='00:00';		
		if(!$Validation->isDate($option['registration_period_end_date']))
			$option['registration_period_end_date']='';
		if(!$Validation->isTime($option['registration_period_end_time']))
			$option['registration_period_end_time']='00:00';
		
		/***/
		
		if(!$Validation->isBool($option['applicant_data_enable']))
			$option['applicant_data_enable']=1;	
		
		/***/
		
		if(!$Validation->isNumber($option['lesson_number'],1,99999))
			$option['lesson_number']=1;	
		if(!$Validation->isNumber($option['lesson_length'],1,99999))
			$option['lesson_length']=60;	
		
		/***/
		
		$dictionary=$Payment->getDictionary();
		if(in_array(-1,$option['payment_id']))
		{
			$option['payment_id']=array(-1);
		}
		else
		{
			foreach($option['payment_id'] as $value)
			{
				if(!isset($dictionary[$value]))
					unset($option['payment_id'][$value]);				
			}
		}  
		
		if(!is_array($option['payment_id'])) $option['payment_id']=array(-1);
		
		
		/***/
		
		$dictionary=$EmailAccount->getDictionary();
		
		if(!array_key_exists($option['booking_new_sender_email_account_id'],$dictionary))
			$option['booking_new_sender_email_account_id']=-1;
		if(!$Validation->isBool($option['email_notification_booking_new_client_enable']))
			$option['email_notification_booking_new_client_enable']=1;
		if(!$Validation->isBool($option['email_notification_booking_new_admin_enable']))
			$option['email_notification_booking_new_admin_enable']=1;
		
		/***/
		
		if(!$Validation->isBool($option['nexmo_sms_enable']))
			$option['nexmo_sms_enable']=0;
		
		/***/
		
		if(!$Validation->isBool($option['twilio_sms_enable']))
			$option['twilio_sms_enable']=0;
		
		/***/
		
		if(!$Validation->isBool($option['telegram_enable']))
			$option['telegram_enable']=0;
		
		/***/
		
		$scheduleWeekDay=array();
		
		if(is_array($option['schedule_week_day']['id']))
		{
			foreach($option['schedule_week_day']['id'] as $index=>$value)
			{
				if((!isset($option['schedule_week_day']['day'][$index])) || (!isset($option['schedule_week_day']['time_start'][$index])) || (!isset($option['schedule_week_day']['time_stop'][$index]))) continue;

				if(!array_key_exists($option['schedule_week_day']['day'][$index],$Date->day)) continue;

				$option['schedule_week_day']['time_start'][$index]=$Date->formatTimeToStandard($option['schedule_week_day']['time_start'][$index]);
				$option['schedule_week_day']['time_stop'][$index]=$Date->formatTimeToStandard($option['schedule_week_day']['time_stop'][$index]);

				if(!$Validation->isTime($option['schedule_week_day']['time_start'][$index])) continue;
				if(!$Validation->isTime($option['schedule_week_day']['time_stop'][$index])) continue;			

				if($Validation->isEmpty($value))
					$value=BGCBSHelper::createId();

				$scheduleWeekDay[]=array('id'=>$value,'day'=>$option['schedule_week_day']['day'][$index],'time_start'=>$option['schedule_week_day']['time_start'][$index],'time_stop'=>$option['schedule_week_day']['time_stop'][$index]);
			}
		}
		
		$option['schedule_week_day']=$scheduleWeekDay;
		
		/***/
		
		$scheduleDate=array();
		
		if(is_array($option['schedule_week_day']['id']))
		{
			foreach($option['schedule_date']['id'] as $index=>$value)
			{
				if((!isset($option['schedule_date']['date'][$index])) || (!isset($option['schedule_date']['time_start'][$index])) || (!isset($option['schedule_date']['time_stop'][$index]))) continue;

				$option['schedule_date']['date'][$index]=$Date->formatDateToStandard($option['schedule_date']['date'][$index]);
				$option['schedule_date']['time_start'][$index]=$Date->formatTimeToStandard($option['schedule_date']['time_start'][$index]);
				$option['schedule_date']['time_stop'][$index]=$Date->formatTimeToStandard($option['schedule_date']['time_stop'][$index]);

				if(!$Validation->isDate($option['schedule_date']['date'][$index])) continue;
				if(!$Validation->isTime($option['schedule_date']['time_start'][$index])) continue;
				if(!$Validation->isTime($option['schedule_date']['time_stop'][$index])) continue;			

				if($Validation->isEmpty($value))
					$value=BGCBSHelper::createId();

				$scheduleDate[]=array('id'=>$value,'date'=>$option['schedule_date']['date'][$index],'time_start'=>$option['schedule_date']['time_start'][$index],'time_stop'=>$option['schedule_date']['time_stop'][$index]);
			}
		}
		
		$option['schedule_date']=$scheduleDate;
		
		/***/
		
		$CourseFormElement->save($postId);
		
		/***/
	
		$CourseAgreement->save($postId);
		
		/***/
		
		foreach($key as $index)
			BGCBSPostMeta::updatePostMeta($postId,$index,$option[$index]); 
	}
	
	/**************************************************************************/
	
	function getDictionary($attr=array())
	{
		global $post;
		
		$dictionary=array();
		
		$default=array
		(
			'course_id'=>0
		);
		
		$attribute=shortcode_atts($default,$attr);
		BGCBSHelper::preservePost($post,$bPost);
		
		$argument=array
		(
			'post_type'=>self::getCPTName(),
			'post_status'=>'publish',
			'posts_per_page'=>-1,
			'orderby'=>array('menu_order'=>'asc','title'=>'asc')
		);
		
		if($attribute['course_id'])
			$argument['p']=$attribute['course_id'];

		$query=new WP_Query($argument);
		if($query===false) return($dictionary);
		
		while($query->have_posts())
		{
			$query->the_post();
			$dictionary[$post->ID]['post']=$post;
			$dictionary[$post->ID]['meta']=BGCBSPostMeta::getPostMeta($post);
			$dictionary[$post->ID]['post']->post_title=BGCBSHelper::filterPostTitle($post->post_title,$post->ID);
		}
		
		BGCBSHelper::preservePost($post,$bPost,0);	
		
		return($dictionary);		
	}
	
	/**************************************************************************/
	
	function manageEditColumns($column)
	{
		$column=array
		(
			'cb'=>$column['cb'],
			'title'=>esc_html__('Title','bookingo'),
			'group'=>esc_html__('Group','bookingo'),
			'registration_period'=>esc_html__('Registration period','bookingo'),
			'payment_method'=>esc_html__('Payment methods','bookingo')
		);
   
		return($column);		  
	}
	
	/**************************************************************************/
	
	function managePostsCustomColumn($column)
	{
		global $post;
		
		$html=null;
		
		$meta=BGCBSPostMeta::getPostMeta($post);
	
		$Date=new BGCBSDate();
		$Payment=new BGCBSPayment();
		$Validation=new BGCBSValidation();
		$CourseGroup=new BGCBSCourseGroup();
		
		switch($column)
		{
			case 'group':
				
				$dictionary=$CourseGroup->getDictionary();
				$html=BGCBSHelper::displayDictionary($dictionary,$meta['course_group_id']);
			
			break;
		
			case 'registration_period':
				
				if($Validation->isNotEmpty($meta['registration_period_start_date']))
					$html.=sprintf(esc_html__('From: %s','bookingo'),$Date->formatDateToDisplay($meta['registration_period_start_date']).' '.esc_html($Date->formatTimeToDisplay($meta['registration_period_start_time'])));
				
				if($Validation->isNotEmpty($meta['registration_period_end_date']))
				{
					if($Validation->isNotEmpty($html)) $html.='&nbsp;-&nbsp;';
					$html.=sprintf(esc_html__('To: %s','bookingo'),$Date->formatDateToDisplay($meta['registration_period_end_date']).' '.esc_html($Date->formatTimeToDisplay($meta['registration_period_end_time'])));
				}
				
			break;

			case 'payment_method':
				
				$dictionary=$Payment->getDictionary();
				$html=BGCBSHelper::displayDictionary($dictionary,$meta['payment_id']);
	
			break;		
		}
		
		echo $html;
	}
	
	/**************************************************************************/
	
	function manageEditSortableColumns($column)
	{
		return($column);	   
	}
	
	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/