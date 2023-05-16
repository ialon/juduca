<?php

/******************************************************************************/
/******************************************************************************/

class BGCBSPayment
{
	/**************************************************************************/
	
	function __construct()
	{
		$this->paymentType=array
		(
			'1'=>array(esc_html__('Custom','bookingo'),'custom'),
			'2'=>array(esc_html__('Stripe','bookingo'),'stripe'),
			'3'=>array(esc_html__('PayPal','bookingo'),'paypal')
		);
	}
	
	/**************************************************************************/
	
	function getPaymentTypeName($paymentType)
	{
		if($this->isPaymentType($paymentType))
			return($this->paymentType[$paymentType][0]);
		else return(null);
	}
	
	/**************************************************************************/
	
	function getPaymentType()
	{
		return($this->paymentType);
	}
	
	/**************************************************************************/
	
	function isPaymentType($paymentType)
	{
		return(array_key_exists($paymentType,$this->getPaymentType()) ? true : false);
	}
	
	/**************************************************************************/
	
	public function init()
	{
		$this->registerCPT();
	}
	
	/**************************************************************************/

	public static function getCPTName()
	{
		return(PLUGIN_BGCBS_CONTEXT.'_payment');
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
					'name'=>esc_html__('Payments','bookingo'),
					'singular_name'=>esc_html__('Payment','bookingo'),
					'add_new'=>esc_html__('Add New','bookingo'),
					'add_new_item'=>esc_html__('Add New Payment','bookingo'),
					'edit_item'=>esc_html__('Edit Payment','bookingo'),
					'new_item'=>esc_html__('New Payment','bookingo'),
					'all_items'=>esc_html__('Payments','bookingo'),
					'view_item'=>esc_html__('View Payment','bookingo'),
					'search_items'=>esc_html__('Search Payment','bookingo'),
					'not_found'=>esc_html__('No Payment Found','bookingo'),
					'not_found_in_trash'=>esc_html__('No Payment in Trash','bookingo'), 
					'parent_item_colon'=>'',
					'menu_name'=>esc_html__('Payments','bookingo')
				),	
				'public'=>false,  
				// 'show_ui'=>true,
				// 'show_in_menu'=>'edit.php?post_type='.BGCBSBooking::getCPTName(),
				'capability_type'=>'post',
				'menu_position'=>2,
				'hierarchical'=>false,  
				'rewrite'=>false,  
				'supports'=>array('title','thumbnail')  
			)
		);
		
		add_action('save_post',array($this,'savePost'));
		add_action('add_meta_boxes_'.self::getCPTName(),array($this,'addMetaBox'));
		add_filter('postbox_classes_'.self::getCPTName().'_bgcbs_meta_box_payment',array($this,'adminCreateMetaBoxClass'));
		
		add_filter('manage_edit-'.self::getCPTName().'_columns',array($this,'manageEditColumns')); 
		add_action('manage_'.self::getCPTName().'_posts_custom_column',array($this,'managePostsCustomColumn'));
		add_filter('manage_edit-'.self::getCPTName().'_sortable_columns',array($this,'manageEditSortableColumns'));
	}

	/**************************************************************************/
	
	function addMetaBox()
	{
		add_meta_box(PLUGIN_BGCBS_CONTEXT.'_meta_box_payment',esc_html__('Main','bookingo'),array($this,'addMetaBoxMain'),self::getCPTName(),'normal','low');		
	}
	
	/**************************************************************************/
	
	function addMetaBoxMain()
	{
		global $post;
		
		$PaymentStripe=new BGCBSPaymentStripe();
		
		$data=array();
		
		$data['meta']=BGCBSPostMeta::getPostMeta($post);
		
		$data['nonce']=BGCBSHelper::createNonceField(PLUGIN_BGCBS_CONTEXT.'_meta_box_payment');
		
		$data['dictionary']['payment_type']=$this->getPaymentType();
		$data['dictionary']['payment_stripe_method']=$PaymentStripe->getPaymentMethod();
		
		echo BGCBSTemplate::outputS($data,PLUGIN_BGCBS_TEMPLATE_PATH.'admin/meta_box_payment.php');
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
		BGCBSHelper::setDefault($meta,'payment_type',1);
		
		BGCBSHelper::setDefault($meta,'payment_custom_success_url_address','');
		
		BGCBSHelper::setDefault($meta,'payment_paypal_email_address','');
		BGCBSHelper::setDefault($meta,'payment_paypal_success_url_address','');
		BGCBSHelper::setDefault($meta,'payment_paypal_cancel_url_address','');
		BGCBSHelper::setDefault($meta,'payment_paypal_sandbox_mode_enable',0);
		
		BGCBSHelper::setDefault($meta,'payment_stripe_api_key_secret','');
		BGCBSHelper::setDefault($meta,'payment_stripe_api_key_publishable','');
		BGCBSHelper::setDefault($meta,'payment_stripe_method',array('card'));
		BGCBSHelper::setDefault($meta,'payment_stripe_product_id','');
		BGCBSHelper::setDefault($meta,'payment_stripe_success_url_address','');
		BGCBSHelper::setDefault($meta,'payment_stripe_cancel_url_address','');
	}
	
	/**************************************************************************/
	
	function savePost($postId)
	{	  
		if(!$_POST) return(false);
		
		if(BGCBSHelper::checkSavePost($postId,PLUGIN_BGCBS_CONTEXT.'_meta_box_payment_noncename','savePost')===false) return(false);
		
		$Validation=new BGCBSValidation();
		$PaymentStripe=new BGCBSPaymentStripe();
		
		$option=BGCBSHelper::getPostOption();
		
		$key=array
		(
			'payment_type',
			'payment_custom_success_url_address',
			'payment_stripe_api_key_secret',
			'payment_stripe_api_key_publishable',
			'payment_stripe_method',
			'payment_stripe_product_id',
			'payment_stripe_success_url_address',
			'payment_stripe_cancel_url_address',
			'payment_paypal_email_address',
			'payment_paypal_success_url_address',
			'payment_paypal_cancel_url_address',
			'payment_paypal_sandbox_mode_enable',
		);
				
		/***/
		
		if(!$this->isPaymentType($option['payment_type']))
			$option['payment_type']=1;
		
		/***/
		
		if(is_array($option['payment_stripe_method']))
		{
			foreach($option['payment_stripe_method'] as $index=>$value)
			{
				if(!$PaymentStripe->isPaymentMethod($value))
					unset($option['payment_stripe_method'][$index]);
			}
		}
			
		if((!is_array($option['payment_stripe_method'])) || (!count($option['payment_stripe_method'])))
			$option['payment_stripe_method']=array('card');		
		
		/***/
		
		if(!$Validation->isBool($option['payment_paypal_sandbox_mode_enable']))
			$option['payment_paypal_sandbox_mode_enable']=0;
		
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
			'payment_id'=>0
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
		
		if($attribute['payment_id'])
			$argument['p']=$attribute['payment_id'];

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
			'payment_type'=>esc_html__('Payment type','bookingo')
		);
   
		return($column);		  
	}
	
	/**************************************************************************/
	
	function managePostsCustomColumn($column)
	{
		global $post;
		
		$meta=BGCBSPostMeta::getPostMeta($post);
		
		switch($column)
		{
			case 'payment_type':
				
				echo esc_html($this->getPaymentTypeName($meta['payment_type']));
				
			break;
		}
	}
	
	/**************************************************************************/
	
	function manageEditSortableColumns($column)
	{
		return($column);	   
	}
	
	/**************************************************************************/
	
	function getPaymentLink($bookingId,$postId)
	{
		return(add_query_arg(array('action'=>'booking_pay','booking_id'=>$bookingId),get_permalink($postId)));
	}
	
	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/