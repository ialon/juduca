<?php

/******************************************************************************/
/******************************************************************************/

class BGCBSCoupon
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
		return(PLUGIN_BGCBS_CONTEXT.'_coupon');
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
					'name'=>esc_html__('Coupons','bookingo'),
					'singular_name'=>esc_html__('Coupons','bookingo'),
					'add_new'=>esc_html__('Add New','bookingo'),
					'add_new_item'=>esc_html__('Add New Coupon','bookingo'),
					'edit_item'=>esc_html__('Edit Coupon','bookingo'),
					'new_item'=>esc_html__('New Coupon','bookingo'),
					'all_items'=>esc_html__('Coupons','bookingo'),
					'view_item'=>esc_html__('View Coupon','bookingo'),
					'search_items'=>esc_html__('Search Coupons','bookingo'),
					'not_found'=>esc_html__('No Coupons Found','bookingo'),
					'not_found_in_trash'=>esc_html__('No Coupons in Trash','bookingo'), 
					'parent_item_colon'=>'',
					'menu_name'=>esc_html__('Coupons','bookingo')
				),	
				'public'=>false,  
				// 'show_ui'=>true,
				// 'show_in_menu'=>'edit.php?post_type='.BGCBSBooking::getCPTName(),
				'capability_type'=>'post',
				'menu_position'=>2,
				'hierarchical'=>false,  
				'rewrite'=>false,  
				'supports'=>false
			)
		);
		
		add_action('save_post',array($this,'savePost'));
		add_action('add_meta_boxes_'.self::getCPTName(),array($this,'addMetaBox'));
		add_filter('postbox_classes_'.self::getCPTName().'_bgcbs_meta_box_coupon',array($this,'adminCreateMetaBoxClass'));
		
		add_filter('manage_edit-'.self::getCPTName().'_columns',array($this,'manageEditColumns')); 
		add_action('manage_'.self::getCPTName().'_posts_custom_column',array($this,'managePostsCustomColumn'));
		add_filter('manage_edit-'.self::getCPTName().'_sortable_columns',array($this,'manageEditSortableColumns'));
	}

	/**************************************************************************/
	
	function addMetaBox()
	{
		add_meta_box(PLUGIN_BGCBS_CONTEXT.'_meta_box_coupon',esc_html__('Main','bookingo'),array($this,'addMetaBoxMain'),self::getCPTName(),'normal','low');		
	}
	
	/**************************************************************************/
	
	function addMetaBoxMain()
	{
		global $post;
		
		$Booking=new BGCBSBooking();
		
		$data=array();
			   
		$data['meta']=BGCBSPostMeta::getPostMeta($post);
		
		$data['nonce']=BGCBSHelper::createNonceField(PLUGIN_BGCBS_CONTEXT.'_meta_box_coupon');
		
		if(!isset($data['meta']['code']))
		{
			$code=$this->generateCode();
			
			wp_update_post(array('ID'=>$post->ID,'post_title'=>$code));
			
			BGCBSPostMeta::updatePostMeta($post->ID,'code',$code);
			BGCBSPostMeta::updatePostMeta($post->ID,'usage_count',0);
			
			$data['meta']=BGCBSPostMeta::getPostMeta($post);
		}
		
		$data['meta']['usage_count']=$Booking->getCouponCodeUsageCount($post->ID);
		
		echo BGCBSTemplate::outputS($data,PLUGIN_BGCBS_TEMPLATE_PATH.'admin/meta_box_coupon.php');
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
		BGCBSHelper::setDefault($meta,'usage_limit','');
		
		BGCBSHelper::setDefault($meta,'discount_percentage',0);
		BGCBSHelper::setDefault($meta,'discount_fixed',0);
		
		BGCBSHelper::setDefault($meta,'active_date_start','');
		BGCBSHelper::setDefault($meta,'active_date_stop','');
	}
	
	/**************************************************************************/
	
	function savePost($postId)
	{	  
		if(!$_POST) return(false);
		
		if(BGCBSHelper::checkSavePost($postId,PLUGIN_BGCBS_CONTEXT.'_meta_box_coupon_noncename','savePost')===false) return(false);
		
		$Date=new BGCBSDate();
		$Validation=new BGCBSValidation();
		
		$option=BGCBSHelper::getPostOption();
			 
		/**/
		
		if(($this->existCode($option['code'],$postId)) || (!$this->validCode($option['code'])))
			$option['code']=$this->generateCode();
		
		if(!$Validation->isNumber($option['usage_limit'],1,9999))
			$option['usage_limit']='';
		 
		/***/
		
		if(!$Validation->isDate($option['active_date_start']))
			$option['active_date_start']='';
		if(!$Validation->isDate($option['active_date_stop']))
			$option['active_date_stop']='';
		if(($Validation->isDate($option['active_date_start'])) && ($Validation->isDate($option['active_date_stop'])))
		{
			if($Date->compareDate($option['active_date_start'],$option['active_date_stop'])==1)
			{
				$option['active_date_start']='';
				$option['active_date_stop']='';
			}
		}	
		
		$option['active_date_start']=$Date->formatDateToStandard($option['active_date_start']);
		$option['active_date_stop']=$Date->formatDateToStandard($option['active_date_stop']);
		
		/***/
	
		if($Validation->isNumber($option['discount_percentage'],1,99,false))
		{
			$option['discount_fixed']=0;
		}
		else $option['discount_percentage']=0;
		
		if(($Validation->isPrice($option['discount_fixed'])) && ($option['discount_fixed']>0))
		{
			$option['discount_percentage']=0;
		}
		else $option['discount_fixed']=0;		
		
		/***/
		
		$option['discount_fixed']=BGCBSPrice::formatToSave($option['discount_fixed']);
		
		$key=array
		(
			'code',
			'usage_limit',
			'active_date_start',
			'active_date_stop',
			'discount_percentage',
			'discount_fixed'
		);
		
		foreach($key as $index)
			BGCBSPostMeta::updatePostMeta($postId,$index,$option[$index]);
		
		wp_update_post(array('ID'=>$postId,'post_title'=>$option['code']));
	}
	
	/**************************************************************************/
	
	function manageEditColumns($column)
	{
		$column=array
		(
			'cb'=>$column['cb'],
			'title'=>esc_html__('Code','bookingo'),
			'usage_limit'=>esc_html__('Usage limit','bookingo'),
			'usage_count'=>esc_html__('Usage count','bookingo'),
			'discount_percentage'=>esc_html__('Percentage discount','bookingo'),
			'discount_fixed'=>esc_html__('Fixed discount','bookingo'),
			'active_date_start'=>esc_html__('Active from','bookingo'),	
			'active_date_stop'=>esc_html__('Active to','bookingo')
		);
   
		return($column);		  
	}
	
	/**************************************************************************/
	
	function managePostsCustomColumn($column)
	{
		global $post;
		
		$Date=new BGCBSDate();
		$Booking=new BGCBSBooking();
		
		$meta=BGCBSPostMeta::getPostMeta($post);
		
		switch($column) 
		{
			case 'usage_limit':
				
				echo esc_html($meta['usage_limit']);
				
			break;
		
			case 'usage_count':
				
				$count=$Booking->getCouponCodeUsageCount($post->ID);
				echo esc_html($count);
				
			break;
		
			case 'discount_percentage':
				
				echo esc_html($meta['discount_percentage']);
				
			break;
		
			case 'discount_fixed':
				
				echo esc_html($meta['discount_fixed']);
				
			break;
		
			case 'active_date_start':

				echo esc_html($Date->formatDateToDisplay($meta['active_date_start']));
				
			break;

			case 'active_date_stop':
				
				echo esc_html($Date->formatDateToDisplay($meta['active_date_stop']));
				
			break;			
		}
	}
	
	/**************************************************************************/
	
	function manageEditSortableColumns($column)
	{
		return($column);	   
	}
	
	/**************************************************************************/
	
	function create()
	{
		$option=BGCBSHelper::getPostOption();

		$response=array('global'=>array('error'=>1));

		$Date=new BGCBSDate();
		$Coupon=new BGCBSCoupon();
		$Notice=new BGCBSNotice();
		$Validation=new BGCBSValidation();
		
		$invalidValue=esc_html__('This field includes invalid value.','bookingo');
		
		if(!$Validation->isNumber($option['coupon_generate_count'],1,999))
			$Notice->addError(BGCBSHelper::getFormName('coupon_generate_count',false),$invalidValue);			
		if(!$Validation->isNumber($option['coupon_generate_usage_limit'],1,9999,true))
			$Notice->addError(BGCBSHelper::getFormName('coupon_generate_usage_limit',false),$invalidValue);			
		
		$option['coupon_generate_active_date_start']=$Date->formatDateToStandard($option['coupon_generate_active_date_start']);
		$option['coupon_generate_active_date_stop']=$Date->formatDateToStandard($option['coupon_generate_active_date_stop']);
		
		if(!$Validation->isDate($option['coupon_generate_active_date_start'],true))
			$Notice->addError(BGCBSHelper::getFormName('coupon_generate_active_date_start',false),$invalidValue);	  
		else if(!$Validation->isDate($option['coupon_generate_active_date_stop'],true))
			$Notice->addError(BGCBSHelper::getFormName('coupon_generate_active_date_stop',false),$invalidValue);			  
		else
		{
			if($Date->compareDate($option['coupon_generate_active_date_start'],$option['coupon_generate_active_date_stop'])==1)
			{
				$Notice->addError(BGCBSHelper::getFormName('coupon_generate_active_date_start',false),esc_html__('Invalid dates range.','bookingo'));
				$Notice->addError(BGCBSHelper::getFormName('coupon_generate_active_date_stop',false),esc_html__('Invalid dates range.','bookingo')); 
			}			
		}
		
		if($Notice->isError())
		{
			$response['local']=$Notice->getError();
		}
		else
		{
			$Coupon->generate($option);
			$response['global']['error']=0;
		}

		$response['global']['notice']=$Notice->createHTML(PLUGIN_BGCBS_TEMPLATE_PATH.'notice.php');

		echo json_encode($response);
		exit;
	}
	
	/**************************************************************************/
	
	function generate($data)
	{
		$Validation=new BGCBSValidation();
		
		for($i=0;$i<$data['coupon_generate_count'];$i++)
		{
			$couponCode=$this->generateCode();
			
			$couponId=wp_insert_post
			(
				array
				(
					'comment_status'=>'closed',
					'ping_status'=>'closed',
					'post_author'=>get_current_user_id(),
					'post_title'=>$couponCode,
					'post_status'=>'publish',
					'post_type'=>self::getCPTName()
				)
			);
			
			if($couponId>0)
			{
				$discountPercentage=$data['coupon_generate_discount_percentage'];
				$discountFixed=$data['coupon_generate_discount_fixed'];
				
				if($Validation->isNumber($discountPercentage,1,99,true))
				{
					$discountFixed=0;
				}
				else 
				{
					$discountPercentage=0;
					if($Validation->isPrice($discountFixed))
					{
						$discountPercentage=0;
					}
					else $discountFixed=0;					 
				}
				
				BGCBSPostMeta::updatePostMeta($couponId,'code',$couponCode);
				
				BGCBSPostMeta::updatePostMeta($couponId,'usage_count',0);
				BGCBSPostMeta::updatePostMeta($couponId,'usage_limit',$data['coupon_generate_usage_limit']);
				
				BGCBSPostMeta::updatePostMeta($couponId,'discount_percentage',$discountPercentage);
				BGCBSPostMeta::updatePostMeta($couponId,'discount_fixed',$discountFixed);
				
				BGCBSPostMeta::updatePostMeta($couponId,'active_date_start',$data['coupon_generate_active_date_start']);
				BGCBSPostMeta::updatePostMeta($couponId,'active_date_stop',$data['coupon_generate_active_date_stop']);
			}
		}
	}
	
	/**************************************************************************/
	
	function generateCode($length=12)
	{
		$code=null;
		
		$char='0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charLength=strlen($char);
		
		for($i=0;$i<$length;$i++)
			$code.=$char[rand(0,$charLength-1)];
		return($code);
	}
	
	/**************************************************************************/
	
	function getDictionary($attr=array())
	{
		global $post;
		
		$dictionary=array();
		
		$default=array
		(
			'coupon_id'=>0
		);
		
		$attribute=shortcode_atts($default,$attr);
		BGCBSHelper::preservePost($post,$bPost);
		
		$argument=array
		(
			'post_type'=>self::getCPTName(),
			'post_status'=>'publish',
			'posts_per_page'=>-1
		);
		
		if($attribute['coupon_id'])
			$argument['p']=$attribute['coupon_id'];

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
	
	function existCode($code,$postId)
	{
		$argument=array
		(
			'post_type'=>self::getCPTName(),
			'post_status'=>'any',
			'post__not_in'=>array($postId),
			'posts_per_page'=>-1,
			'meta_key'=>PLUGIN_BGCBS_CONTEXT.'_code',
			'meta_value'=>$code,
			'meta_compare'=>'='
		);
		
		$query=new WP_Query($argument);
		if($query===false) return(false);
		
		/***/
		
		if($query->found_posts!=1) return(false);		
		
		return(true);
	}
	
	/**************************************************************************/
	
	function validCode($code)
	{
		$Validation=new BGCBSValidation();
		
		if($Validation->isEmpty($code)) return(false);
		
		if(strlen($code)>32) return(false);
		
		return(true);
	}
	
	/**************************************************************************/
	
	function checkCode($bookingForm)
	{
		global $post;
		
		$Date=new BGCBSDate();
		$Booking=new BGCBSBooking();
		$Validation=new BGCBSValidation();
		
		$data=BGCBSHelper::getPostOption();
		
		if((int)$bookingForm['booking_form'][$bookingForm['booking_form_id']]['meta']['coupon_enable']!==1) return(false);
		
		if(!array_key_exists('coupon_code',$data)) return(false);
		if($Validation->isEmpty($data['coupon_code'])) return(false);
		
		/***/
		
		$argument=array
		(
			'post_type'=>self::getCPTName(),
			'post_status'=>'publish',
			'posts_per_page'=>-1,
			'meta_key'=>PLUGIN_BGCBS_CONTEXT.'_code',
			'meta_value'=>$data['coupon_code'],
			'meta_compare'=>'='
		);
		
		$query=new WP_Query($argument);
		if($query===false) return(false);
		
		/***/
		
		if($query->found_posts!=1) return(false);
		
		$query->the_post();
		
		$meta=BGCBSPostMeta::getPostMeta($post);
		
		/***/
		
		if($Validation->isNotEmpty($meta['usage_limit']))
		{	
		   $count=$Booking->getCouponCodeUsageCount($post->ID);
	  
		   if($count===false) return(false);
		   if($count>=$meta['usage_limit']) return(false);
		}
		
		/***/
		
		if($Validation->isNotEmpty($meta['active_date_start']))
		{
			if($Date->compareDate(date_i18n('Y-m-d'),$meta['active_date_start'])===2) return(false);
		}
		
		if($Validation->isNotEmpty($meta['active_date_stop']))
		{
			if($Date->compareDate($meta['active_date_stop'],date_i18n('Y-m-d'))===2) return(false);
		}  
		
		/***/

		return(array('post'=>$post,'meta'=>$meta));
	}
	
	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/