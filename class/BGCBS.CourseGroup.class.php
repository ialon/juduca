<?php

/******************************************************************************/
/******************************************************************************/

class BGCBSCourseGroup
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
		return(PLUGIN_BGCBS_CONTEXT.'_course_group');
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
					'name'=>esc_html__('Course Groups','bookingo'),
					'singular_name'=>esc_html__('Course Group','bookingo'),
					'add_new'=>esc_html__('Add New','bookingo'),
					'add_new_item'=>esc_html__('Add New Course Group','bookingo'),
					'edit_item'=>esc_html__('Edit Course Group','bookingo'),
					'new_item'=>esc_html__('New Course Group','bookingo'),
					'all_items'=>esc_html__('Course Groups','bookingo'),
					'view_item'=>esc_html__('View Course Group','bookingo'),
					'search_items'=>esc_html__('Search Course Group','bookingo'),
					'not_found'=>esc_html__('No Course Group Found','bookingo'),
					'not_found_in_trash'=>esc_html__('No Course Group in Trash','bookingo'), 
					'parent_item_colon'=>'',
					'menu_name'=>esc_html__('Course Groups','bookingo')
				),	
				'public'=>false,  
				'show_ui'=>true, 
				'show_in_menu'=>'edit.php?post_type='.BGCBSBooking::getCPTName(),
				'capability_type'=>'post',
				'menu_position'=>2,
				'hierarchical'=>false,  
				'rewrite'=>false,
                'capabilities' => [
                    'publish_posts' => 'manage_options',
                    'edit_posts' => 'manage_options',
                    'edit_others_posts' => 'manage_options',
                    'delete_posts' => 'manage_options',
                    'delete_others_posts' => 'manage_options',
                    'read_private_posts' => 'manage_options',
                    'edit_post' => 'manage_options',
                    'delete_post' => 'manage_options',
                    'read_post' => 'manage_options',
                ],
				'supports'=>array('title','thumbnail','page-attributes')  
			)
		);
		
		add_action('save_post',array($this,'savePost'));
		add_action('add_meta_boxes_'.self::getCPTName(),array($this,'addMetaBox'));
		add_filter('postbox_classes_'.self::getCPTName().'_bgcbs_meta_box_course_group',array($this,'adminCreateMetaBoxClass'));
		
		add_filter('manage_edit-'.self::getCPTName().'_columns',array($this,'manageEditColumns')); 
		add_action('manage_'.self::getCPTName().'_posts_custom_column',array($this,'managePostsCustomColumn'));
		add_filter('manage_edit-'.self::getCPTName().'_sortable_columns',array($this,'manageEditSortableColumns'));
	}

	/**************************************************************************/
	
	function addMetaBox()
	{
		add_meta_box(PLUGIN_BGCBS_CONTEXT.'_meta_box_course_group',esc_html__('Main','bookingo'),array($this,'addMetaBoxMain'),self::getCPTName(),'normal','low');		
	}
	
	/**************************************************************************/
	
	function addMetaBoxMain()
	{
		global $post;
		
		$data=array();
		
		$TaxRate=new BGCBSTaxRate();
		$Location=new BGCBSLocation();
		
		$data['meta']=BGCBSPostMeta::getPostMeta($post);
		
		$data['nonce']=BGCBSHelper::createNonceField(PLUGIN_BGCBS_CONTEXT.'_meta_box_course_group');
		
		$data['dictionary']['tax_rate']=$TaxRate->getDictionary();
		$data['dictionary']['location']=$Location->getDictionary();
		$data['dictionary']['target_post']=$this->getTargetPost();
		
		echo BGCBSTemplate::outputS($data,PLUGIN_BGCBS_TEMPLATE_PATH.'admin/meta_box_course_group.php');
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
		$TaxRate=new BGCBSTaxRate();
		
		BGCBSHelper::setDefault($meta,'course_group_start_date','');
		BGCBSHelper::setDefault($meta,'course_group_start_time','');
		BGCBSHelper::setDefault($meta,'course_group_end_date','');
		BGCBSHelper::setDefault($meta,'course_group_end_time','');

		BGCBSHelper::setDefault($meta,'location_id',-1);

		BGCBSHelper::setDefault($meta,'lesson_number',1);
		BGCBSHelper::setDefault($meta,'lesson_length',60);
		
		BGCBSHelper::setDefault($meta,'price_participant_value',0.00);
		BGCBSHelper::setDefault($meta,'price_participant_tax_rate_id',$TaxRate->getDefaultTaxPostId());
		BGCBSHelper::setDefault($meta,'price_label_under_price','');
		BGCBSHelper::setDefault($meta,'price_label_instead_price','');
		BGCBSHelper::setDefault($meta,'price_net_display_enable',0);
		
		BGCBSHelper::setDefault($meta,'participant_number',1);
		BGCBSHelper::setDefault($meta,'participant_number_enable',1);
		
		BGCBSHelper::setDefault($meta,'contact_info','');
		BGCBSHelper::setDefault($meta,'short_description','');
		BGCBSHelper::setDefault($meta,'short_info','');
		
		BGCBSHelper::setDefault($meta,'target_post_id','');
	}
	
	/**************************************************************************/
	
	function savePost($postId)
	{	  
		if(!$_POST) return(false);
		
		if(BGCBSHelper::checkSavePost($postId,PLUGIN_BGCBS_CONTEXT.'_meta_box_course_group_noncename','savePost')===false) return(false);
		
		$option=BGCBSHelper::getPostOption();
		
		$Date=new BGCBSDate();
		$TaxRate=new BGCBSTaxRate();
		$Location=new BGCBSLocation();
		$Validation=new BGCBSValidation();
		
		/***/
		
		$key=array
		(
			'course_group_start_date',
			'course_group_start_time',
			'course_group_end_date',
			'course_group_end_time',
			'location_id',
			'lesson_number',
			'lesson_length',
			'price_participant_value',
			'price_participant_tax_rate_id',
			'price_label_under_price',
			'price_label_instead_price',
			'price_net_display_enable',
			'schedule_week_day',
			'schedule_date',
			'participant_number',
			'participant_number_enable',
			'contact_info',
			'short_description',
			'short_info',
			'target_post_id'
		);
		
		/***/

		$option['course_group_start_date']=$Date->formatDateToStandard($option['course_group_start_date']);
		$option['course_group_start_time']=$Date->formatTimeToStandard($option['course_group_start_time']);
		$option['course_group_end_date']=$Date->formatDateToStandard($option['course_group_end_date']);
		$option['course_group_end_time']=$Date->formatTimeToStandard($option['course_group_end_time']);		
		
		if(!$Validation->isDate($option['course_group_start_date']))
			$option['course_group_start_date']='';
		if(!$Validation->isTime($option['course_group_start_time']))
			$option['course_group_start_time']='00:00';		
		if(!$Validation->isDate($option['course_group_end_date']))
			$option['course_group_end_date']='';
		if(!$Validation->isTime($option['course_group_end_time']))
			$option['course_group_end_time']='00:00';				
		
		/***/
		
		$location=$Location->getDictionary();
		if(!array_key_exists($option['location_id'],$location))
			$option['location_id']=-1;
		
		/***/
		
		if(!$Validation->isNumber($option['lesson_number'],1,99999))
			$option['lesson_number']=1;	
		if(!$Validation->isNumber($option['lesson_length'],1,99999))
			$option['lesson_length']=60;
		
		/***/
		
		if(!$Validation->isPrice($option['price_participant_value'])) 
			$option['price_participant_value']=0.00;
			
		$option['price_participant_value']=BGCBSPrice::formatToSave($option['price_participant_value']);
		
		$taxRate=$TaxRate->getDictionary();
		if(!array_key_exists($option['price_participant_tax_rate_id'],$taxRate))
			$option['price_participant_tax_rate_id']=-1;	
		
		if(!$Validation->isBool($option['price_net_display_enable']))
			$option['price_net_display_enable']=0;		
		
		/***/
		
		if(!$Validation->isNumber($option['participant_number'],1,99999))
			$option['participant_number']=1;	
		
		if(!$Validation->isBool($option['participant_number_enable']))
			$option['participant_number_enable']=1;			
				
		/***/
		
		$scheduleWeekDay=array();
		
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
		
		$option['schedule_week_day']=$scheduleWeekDay;
		
		/***/
		
		$scheduleDate=array();
		
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
		
		$option['schedule_date']=$scheduleDate;
		
		/***/
		
		foreach($key as $index)
			BGCBSPostMeta::updatePostMeta($postId,$index,$option[$index]); 
		
		/***/
	}
	
	/**************************************************************************/
	
	function getDictionary($attr=array())
	{
		global $post;
		
		$dictionary=array();
		
		$default=array
		(
			'course_group_id'=>0
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
		
		if($attribute['course_group_id'])
			$argument['post__in']=is_array($attribute['course_group_id']) ? $attribute['course_group_id'] : array($attribute['course_group_id']);

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
	
	function getCourseGroup()
	{
		$argument=array
		(
			'post_type'=>self::getCPTName(),
			'post_status'=>'publish',
			'posts_per_page'=>(int)get_option('posts_per_page'),
			'paged'=>(int)SwimAcademy_ThemeHelper::getPageNumber(),
			'orderby'=>array('menu_order'=>'asc','date'=>'desc')
		);
		
		$query=new WP_Query($argument);
		
		return($query);
	}
	
	/**************************************************************************/
	
	function manageEditColumns($column)
	{
		$column=array
		(
			'cb'=> $column['cb'],
			'title'=> esc_html__('Title','bookingo'),
			'course_period'=>esc_html__('Course period','bookingo'),
			// 'location'=>esc_html__('Location','bookingo'),
			// 'price'=>esc_html__('Price','bookingo'),
			'participant'=>esc_html__('Participants / Registered / Confirmed','bookingo')
		);
   
		return($column);		  
	}
	
	/**************************************************************************/
	
	function managePostsCustomColumn($column)
	{
		global $post;
		
		$Date=new BGCBSDate();
		$Booking=new BGCBSBooking();
		$TaxRate=new BGCBSTaxRate();
		$Location=new BGCBSLocation();
		$Validation=new BGCBSValidation();
		
		$meta=BGCBSPostMeta::getPostMeta($post);
		
		switch($column)
		{
			case 'course_period':
				
				$html=null;
				
				if($Validation->isNotEmpty($meta['course_group_start_date']))
					$html.=sprintf(esc_html__('From: %s','bookingo'),$Date->formatDateToDisplay($meta['course_group_start_date']).' '.esc_html($Date->formatTimeToDisplay($meta['course_group_start_time'])));
				
				if($Validation->isNotEmpty($meta['course_group_end_date']))
				{
					if($Validation->isNotEmpty($html)) $html.='&nbsp;-&nbsp;';
					$html.=sprintf(esc_html__('To: %s','bookingo'),$Date->formatDateToDisplay($meta['course_group_end_date']).' '.esc_html($Date->formatTimeToDisplay($meta['course_group_end_time'])));
				}
				
				echo $html;
				
			break;
			
			case 'location':
				
				$dictionary=$Location->getDictionary();
				
				if(array_key_exists($meta['location_id'],$dictionary))
				{
					$data=array
					(
						'name'=>$dictionary[$meta['location_id']]['post']->post_title,
						'street'=>$dictionary[$meta['location_id']]['meta']['address_street'],
						'street_number'=>$dictionary[$meta['location_id']]['meta']['address_street_number'],
						'postcode'=>$dictionary[$meta['location_id']]['meta']['address_postcode'],
						'city'=>$dictionary[$meta['location_id']]['meta']['address_city'],
						'state'=>$dictionary[$meta['location_id']]['meta']['address_state'],
						'country'=>$dictionary[$meta['location_id']]['meta']['address_country']
					);
					
					echo BGCBSHelper::displayAddress($data);
				}
				
			break;
			
			case 'price':
				
				$dictionary=$TaxRate->getDictionary();
			
				$html=BGCBSPrice::format($meta['price_participant_value'],BGCBSOption::getOption('currency'));
				$html.=sprintf(esc_html__(' (+%s%% tax)','bookingo'),$TaxRate->getTaxRateValue($meta['price_participant_tax_rate_id'],$dictionary));
				
				echo esc_html($html);
				
			break;
		
			case 'participant':

				$participant=$Booking->getNumberParticipant($post->ID);
				
				echo esc_html($meta['participant_number'].' / '.$participant['registered'].' / '.$participant['confirmed']);
				
			break;
		}
	}
	
	/**************************************************************************/
	
	function manageEditSortableColumns($column)
	{
		return($column);	   
	}
	
	/**************************************************************************/
	
	function calculatePrice($bookingForm,$courseGroup=null)
	{
		$Coupon=new BGCBSCoupon();
		$Currency=new BGCBSCurrency();
		$PriceRule=new BGCBSPriceRule();
		
		/***/
		
		if(!is_null($bookingForm))
		{
			$currency=$bookingForm['currency'];
			$courseGroup=$bookingForm['course_group'][$bookingForm['course_group_id']];
			$exchangeRate=$Currency->getExchangeRate($currency);
		}
		else
		{
			$currency=$Currency->getBaseCurrency();
			$exchangeRate=1;
		}	
		
		/***/
		
		$priceBaseNet=$courseGroup['meta']['price_participant_value']*$exchangeRate;
		$priceTaxRateId=$courseGroup['meta']['price_participant_tax_rate_id'];
		
		/***/
		
		$priceBase=array
		(
			'price_participant_value'=>$priceBaseNet,
			'price_participant_tax_rate_id'=>$priceTaxRateId
		);
		
		if(!is_null($bookingForm))
		{
			/***/
			
			$bookingData=array
			(
				'booking_form_id'=>(int)$bookingForm['booking_form_id'],
				'course_id'=>(int)$bookingForm['course_id'],
				'course_group_id'=>(int)$bookingForm['course_group_id'],
				'registration_date'=>date_i18n('d-m-Y')
			);

			$priceBase=$PriceRule->getPriceFromRule($bookingData,$bookingForm,$priceBase);

			$priceBaseNet=$priceBase['price_participant_value'];
			$priceTaxRateId=$priceBase['price_participant_tax_rate_id'];

			/***/

			if(($coupon=$Coupon->checkCode($bookingForm))!==false)
			{
				if($coupon['meta']['discount_percentage']>0)
					$priceBaseNet=$priceBaseNet*(1-($coupon['meta']['discount_percentage']/100));
				else if($coupon['meta']['discount_fixed']>0)
					$priceBaseNet-=$coupon['meta']['discount_fixed'];

				if($priceBaseNet<0) $priceBaseNet=0;
			}
			
			/***/
		}
		
		$priceBaseGross=BGCBSPrice::calculateGross($priceBaseNet,$priceTaxRateId);

		$priceCalculateNet=$priceBaseNet;
		$priceCalculateNetFormat=BGCBSPrice::format($priceCalculateNet,$currency);
		
		$priceCalculateGross=$priceBaseGross;
		$priceCalculateGrossFormat=BGCBSPrice::format($priceCalculateGross,$currency);
		
		$price=array
		(
			'base'=>array
			(
				'net'=>$priceBaseNet,
				'gross'=>$priceBaseGross,
				'currency'=>$Currency->getBaseCurrency(),
				'tax_rate_id'=>$priceTaxRateId
			),
			'calculate'=>array
			(
				'net'=>$priceCalculateNet,
				'net_format'=>$priceCalculateNetFormat,
				'gross'=>$priceCalculateGross,
				'gross_format'=>$priceCalculateGrossFormat,
				'currency'=>(is_array($bookingForm) && array_key_exists('currency',$bookingForm)) ? $bookingForm['currency']: '',
				'tax_rate_id'=>$priceTaxRateId
			)
		);
			
		return($price);
	}
	
	/**************************************************************************/
	
	function getCurrency($bookingForm,$currency)
	{
		$Currency=new BGCBSCurrency();	
		
		if(!$Currency->isCurrency($currency))
			$currency=$Currency->getFormCurrency();
		if(!$Currency->isCurrency($currency))
			$currency=$Currency->getBaseCurrency();
		
		if(!in_array($currency,$bookingForm['booking_form'][$bookingForm['booking_form_id']]['meta']['currency']))
			$currency=$Currency->getBaseCurrency();
		
		return($currency);
	}
	
	/**************************************************************************/
	
	function createCourseGroupItem($courseGroupId,$courseGroup,$style=1,$htmlSVGImage=null)
	{
		$Course=new BGCBSCourse();
		$Booking=new BGCBSBooking();
		$Validation=new BGCBSValidation();

		/***/
		
		global $bgcbsGlobalData;
		
		if(!isset($bgcbsGlobalData['course_group_to_course']))
		{
			$courseGroupToCourse=array();

			$course=$Course->getDictionary();

			foreach($course as $courseIndex=>$courseValue)
			{
				if(is_array($courseValue['meta']['course_group_id']))
				{
					foreach($courseValue['meta']['course_group_id'] as $courseGroupIndex)
						$courseGroupToCourse[$courseGroupIndex]=array('course_id'=>$courseIndex,'course_title'=>$courseValue['post']->post_title);
				}
			}

			$bgcbsGlobalData['course_group_to_course']=$courseGroupToCourse;
		}
		
		/***/
			
		$html=null;
		$htmlThumbnail=null;
		$htmlDescription=null;
			
		/***/
			
		$price=$this->calculatePrice(null,$courseGroup);
		$participant=$Booking->getNumberParticipant($courseGroupId);
				
		/***/
		
		$url=null;
		
		$targetPostId=(int)$courseGroup['meta']['target_post_id'];
		
		if($targetPostId>0)
		{
			$permalink=get_permalink($courseGroup['meta']['target_post_id']);
			if($permalink!==false)
			{
				$url=add_query_arg('course_group_id',$courseGroupId,$permalink);
			}
		}	
		/***/
		
		$thumbnailURL=get_the_post_thumbnail_url($courseGroupId);
		
		if($thumbnailURL!==false)
		{
			$htmlThumbnail='<img src="'.esc_url($thumbnailURL).'" alt=""/>';
			if(!is_null($url))
			{
				$htmlThumbnail='<a href="'.esc_url($url).'">'.$htmlThumbnail.'</a>';
			}
			
			/***/
			
			$htmlPrice=null;
			if($Validation->isEmpty($courseGroup['meta']['price_label_instead_price']))
			{
				$htmlPrice=
				'
					<div class="bgcbs-box-style-1">
						<span>'.esc_html($price['calculate']['gross_format']).'</span>
						<span>'.esc_html__('/ person','bookingo').'</span>
					</div>					
				';
			}
			
			/***/
			
			$htmlThumbnail=
			'
				<div class="bgcbs-course-group-list-item-layout-top">
					<div class="bgcbs-course-group-list-item-image">
						'.$htmlThumbnail.'
						'.$htmlPrice.'
					</div>
				</div>
			';
		}
		
		if(($Validation->isNotEmpty($courseGroup['meta']['short_description'])) && ((int)$style===1))
		{
			$htmlDescription='<p>'.$courseGroup['meta']['short_description'].'</p>';
		}
		
		/***/
		
		$htmlTitle=null;
		
		if(!is_null($url))
		{
			$htmlTitle='<h3><a href="'.esc_url($url).'">'.esc_html($courseGroup['post']->post_title).'</a></h3>';
		}
		else
		{
			$htmlTitle='<h3>'.esc_html($courseGroup['post']->post_title).'</h3>';
		}
		
		/***/
		
		$htmlStudent=null;
		
		if((int)$courseGroup['meta']['participant_number_enable']===1)
		{
			if((int)$courseGroup['meta']['participant_number']===0) $percentage=0;
			else $percentage=$participant['confirmed']/$courseGroup['meta']['participant_number'];

			$percentage=ceil($percentage*100);		
			
			$htmlStudent=
			'
				<div class="bgcbs-course-group-list-item-divider"><div style="width:'.(int)$percentage.'%"></div></div>

				<div class="bgcbs-course-group-list-item-meta">
					<div>
						<span class="bgcbs-icon-meta-16-students"></span>
						<span>'.sprintf(esc_html__('%s enrolled','bookingo'),$participant['confirmed']).'</span>
					</div>
					<div>
						<span class="bgcbs-icon-meta-16-group"></span>
						<span>'.sprintf(esc_html__('%sx lesson','bookingo'),$courseGroup['meta']['lesson_number']).'</span>
					</div>
				</div>	
			';
		}
		else
		{
			$htmlStudent=
			'
				<div class="bgcbs-course-group-list-item-divider"><div style="width:0px"></div></div>

				<div class="bgcbs-course-group-list-item-meta">
					<div></div>
					<div>
						<span class="bgcbs-icon-meta-16-group"></span>
						<span>'.sprintf(esc_html__('%sx lesson','bookingo'),$courseGroup['meta']['lesson_number']).'</span>
					</div>
				</div>	
			';			
		}
		
		/***/
			
		$html.= 
		'
			<div'.SwimAcademy_ThemeHelper::createClassAttribute(array('bgcbs-course-group-list-item')).'>
				'.$htmlSVGImage.'
				'.$htmlThumbnail.'
				<div class="bgcbs-course-group-list-item-layout-bottom">
					<div class="bgcbs-course-group-list-item-meta">
						<div>
							<span class="bgcbs-icon-meta-16-category"></span>
							<span>'.(array_key_exists($courseGroupId,$bgcbsGlobalData['course_group_to_course']) ? esc_html($bgcbsGlobalData['course_group_to_course'][$courseGroupId]['course_title']) : null).'</span>
						</div>						
					</div>
					'.$htmlTitle.'
					'.$htmlDescription.'
					'.$htmlStudent.'
				</div>
			</div>
		';				
		
		return($html);
	}
	
	/**************************************************************************/
	
	function getTargetPost()
	{
		global $post;
		
		$dictionary=array();
	
		BGCBSHelper::preservePost($post,$bPost);
		
		$argument=array
		(
			'post_type'=>array('post','page'),
			'post_status'=>'publish',
			'posts_per_page'=>-1,
			'orderby'=>array('menu_order'=>'asc','title'=>'asc')
		);

		$query=new WP_Query($argument);
		if($query===false) return($dictionary);
		
		while($query->have_posts())
		{
			$query->the_post();
			$dictionary[$post->ID]['post']=$post;
		}
		
		BGCBSHelper::preservePost($post,$bPost,0);	
		
		return($dictionary);			
	}
	
	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/