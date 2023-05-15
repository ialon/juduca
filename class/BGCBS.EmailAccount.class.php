<?php

/******************************************************************************/
/******************************************************************************/

class BGCBSEmailAccount
{
	/**************************************************************************/
	
	function __construct()
	{
		$this->secureConnectionType=array
		(
			'none'=>array(esc_html__('- None -','bookingo')),
			'ssl'=>array(esc_html__('SSL','bookingo')),
			'tls'=>array(esc_html__('TLS','bookingo')),
		);
	}
	
	/**************************************************************************/
	
	function isSecureConnectionType($name)
	{
		return(array_key_exists($name,$this->getSecureConnectionType()) ? true : false);
	}
	
	/**************************************************************************/
	
	function getSecureConnectionType()
	{
		return($this->secureConnectionType);
	}
	
	/**************************************************************************/
	
	public function init()
	{
		$this->registerCPT();
	}
	
	/**************************************************************************/

	public static function getCPTName()
	{
		return(PLUGIN_BGCBS_CONTEXT.'_email_account');
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
					'name'=>esc_html__('E-mail Accounts','bookingo'),
					'singular_name'=>esc_html__('E-mail Accounts','bookingo'),
					'add_new'=>esc_html__('Add New','bookingo'),
					'add_new_item'=>esc_html__('Add New E-mail Account','bookingo'),
					'edit_item'=>esc_html__('Edit E-mail Account','bookingo'),
					'new_item'=>esc_html__('New E-mail Account','bookingo'),
					'all_items'=>esc_html__('E-mail Accounts','bookingo'),
					'view_item'=>esc_html__('View E-mail Account','bookingo'),
					'search_items'=>esc_html__('Search E-mail Accounts','bookingo'),
					'not_found'=>esc_html__('No E-mail Accounts Found','bookingo'),
					'not_found_in_trash'=>esc_html__('No E-mail Accounts in Trash','bookingo'), 
					'parent_item_colon'=>'',
					'menu_name'=>esc_html__('E-mail Accounts','bookingo')
				),	
				'public'=>false,  
				'show_ui'=>true, 
				'show_in_menu'=>'edit.php?post_type='.BGCBSBooking::getCPTName(),
				'capability_type'=>'post',
				'menu_position'=>2,
				'hierarchical'=>false,  
				'rewrite'=>false,  
				'supports'=>array('title')  
			)
		);
		
		add_action('save_post',array($this,'savePost'));
		add_action('add_meta_boxes_'.self::getCPTName(),array($this,'addMetaBox'));
		add_filter('postbox_classes_'.self::getCPTName().'_bgcbs_meta_box_email_account',array($this,'adminCreateMetaBoxClass'));
		
		add_filter('manage_edit-'.self::getCPTName().'_columns',array($this,'manageEditColumns')); 
		add_action('manage_'.self::getCPTName().'_posts_custom_column',array($this,'managePostsCustomColumn'));
		add_filter('manage_edit-'.self::getCPTName().'_sortable_columns',array($this,'manageEditSortableColumns'));
	}

	/**************************************************************************/
	
	function addMetaBox()
	{
		add_meta_box(PLUGIN_BGCBS_CONTEXT.'_meta_box_email_account',esc_html__('Main','bookingo'),array($this,'addMetaBoxMain'),self::getCPTName(),'normal','low');		
	}
	
	/**************************************************************************/
	
	function addMetaBoxMain()
	{
		global $post;
		
		$data=array();
		
		$data['meta']=BGCBSPostMeta::getPostMeta($post);
		
		$data['nonce']=BGCBSHelper::createNonceField(PLUGIN_BGCBS_CONTEXT.'_meta_box_email_account');
		
		$data['dictionary']['secure_connection_type']=$this->secureConnectionType;
		
		echo BGCBSTemplate::outputS($data,PLUGIN_BGCBS_TEMPLATE_PATH.'admin/meta_box_email_account.php');
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
		BGCBSHelper::setDefault($meta,'sender_name','');
		BGCBSHelper::setDefault($meta,'sender_email_address','');
		
		BGCBSHelper::setDefault($meta,'smtp_auth_enable','0');
		BGCBSHelper::setDefault($meta,'smtp_auth_username','');
		BGCBSHelper::setDefault($meta,'smtp_auth_password','');
		BGCBSHelper::setDefault($meta,'smtp_auth_host','');
		BGCBSHelper::setDefault($meta,'smtp_auth_port','');
		BGCBSHelper::setDefault($meta,'smtp_auth_secure_connection_type','none');
		BGCBSHelper::setDefault($meta,'smtp_auth_debug_enable','0');
	}
	
	/**************************************************************************/
	
	function savePost($postId)
	{	  
		if(!$_POST) return(false);
		
		if(BGCBSHelper::checkSavePost($postId,PLUGIN_BGCBS_CONTEXT.'_meta_box_email_account_noncename','savePost')===false) return(false);
		
		$Validation=new BGCBSValidation();
		
		$option=BGCBSHelper::getPostOption();

		if(!$Validation->isBool($option['smtp_auth_enable']))
			$option['smtp_auth_enable']=0;
		
		if(!$this->isSecureConnectionType($option['smtp_auth_secure_connection_type']))
			$option['smtp_auth_secure_connection_type']='none';
		
		if(!$Validation->isBool($option['smtp_auth_debug_enable']))
			$option['smtp_auth_debug_enable']=0;
		
		$field=array
		(
			'sender_name',
			'sender_email_address',
			'smtp_auth_enable',
			'smtp_auth_username',
			'smtp_auth_password',
			'smtp_auth_host',
			'smtp_auth_port',
			'smtp_auth_secure_connection_type',
			'smtp_auth_debug_enable'
		);

		foreach($field as $value)
			BGCBSPostMeta::updatePostMeta($postId,$value,$option[$value]);
	}
	
	/**************************************************************************/
	
	function getDictionary($attr=array())
	{
		global $post;
		
		$dictionary=array();
		
		$default=array
		(
			'email_account_id'=>0
		);
		
		$attribute=shortcode_atts($default,$attr);
		BGCBSHelper::preservePost($post,$bPost);
		
		$argument=array
		(
			'post_type'=>self::getCPTName(),
			'post_status'=>'publish',
			'posts_per_page'=>-1,
			'orderby'=>array('title'=>'asc')
		);
		
		if($attribute['email_account_id'])
			$argument['p']=$attribute['email_account_id'];

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
			'title'=>$column['title'],
			'sender'=>esc_html__('Sender','bookingo')
		);
   
		return($column);		  
	}
	
	/**************************************************************************/
	
	function managePostsCustomColumn($column)
	{
		global $post;
		
		$meta=BGCBSPostMeta::getPostMeta($post);
		
		$Validation=new BGCBSValidation();
		
		switch($column) 
		{
			case 'sender':
				
				$html=null;
				
				if($Validation->isNotEmpty($meta['sender_name']))
					$html.=esc_html($meta['sender_name']);
				
				if($Validation->isNotEmpty($meta['sender_email_address']))
					$html.=' <a href="mailto:'.esc_attr($meta['sender_email_address']).'">&lt;'.esc_html($meta['sender_email_address']).'&gt;</a>';
				
				echo trim($html);
				
			break;
		}
	}
	
	/**************************************************************************/
	
	function manageEditSortableColumns($column)
	{
		return($column);	   
	}
	
	/**************************************************************************/
	
	function sendTestEmail()
	{
		$Email=new BGCBSEmail();
		$EmailAccount=new BGCBSEmailAccount();
		
		$response=array('error'=>1,'error_message'=>esc_html__('Cannot set details needed to send an e-mail message.','bookingo'));
		
		$emailAccountId=(int)BGCBSHelper::getPostValue('email_account_id',false);
		
		if(($dictionary=$EmailAccount->getDictionary(array('email_account_id'=>$emailAccountId)))===false)
			BGCBSHelper::createJSONResponse($response);
		
		$emailAccount=$dictionary[$emailAccountId];
		
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
		
		$data=array();
		
		$data['style']=$Email->getEmailStyle();
		
		$Template=new BGCBSTemplate($data,PLUGIN_BGCBS_TEMPLATE_PATH.'email_test.php');
		$body=$Template->output();
		
		/***/
		
		global $bgcbs_logEvent;
		
		$bgcbs_logEvent=-1;
		
		add_action('wp_mail_failed','logWPMailErrorLocal',10,1);
		
		function logWPMailErrorLocal($wp_error)
		{
			global $bgcbsGlobalData;
			$bgcbsGlobalData['wp_mail_error']=$wp_error;
		} 
		
		/***/
	 
		global $bgcbsGlobalData;
		
		$Email->send(array(BGCBSHelper::getPostValue('receiver_email_address',false)),esc_html__('Test message','bookingo'),$body);
	   
		$response['error']=0;
		$response['email_response']=esc_html(print_r($bgcbsGlobalData['wp_mail_error'],true));
		
		BGCBSHelper::createJSONResponse($response);
	}
	
	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/