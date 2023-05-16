<?php

/******************************************************************************/
/******************************************************************************/

class BGCBSLocation
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
		return(PLUGIN_BGCBS_CONTEXT.'_location');
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
					'name'=>esc_html__('Locations','bookingo'),
					'singular_name'=>esc_html__('Location','bookingo'),
					'add_new'=>esc_html__('Add New','bookingo'),
					'add_new_item'=>esc_html__('Add New Location','bookingo'),
					'edit_item'=>esc_html__('Edit Location','bookingo'),
					'new_item'=>esc_html__('New Location','bookingo'),
					'all_items'=>esc_html__('Locations','bookingo'),
					'view_item'=>esc_html__('View Location','bookingo'),
					'search_items'=>esc_html__('Search Location','bookingo'),
					'not_found'=>esc_html__('No Location Found','bookingo'),
					'not_found_in_trash'=>esc_html__('No Location in Trash','bookingo'), 
					'parent_item_colon'=>'',
					'menu_name'=>esc_html__('Location','bookingo')
				),	
				'public'=>false,  
				// 'show_ui'=>true,
				// 'show_in_menu'=>'edit.php?post_type='.BGCBSBooking::getCPTName(),
				'capability_type'=>'post',
				'menu_position'=>2,
				'hierarchical'=>false,  
				'rewrite'=>false,  
				'supports'=>array('title')  
			)
		);
		
		add_action('save_post',array($this,'savePost'));
		add_action('add_meta_boxes_'.self::getCPTName(),array($this,'addMetaBox'));
		add_filter('postbox_classes_'.self::getCPTName().'_bgcbs_meta_box_location',array($this,'adminCreateMetaBoxClass'));
		
		add_filter('manage_edit-'.self::getCPTName().'_columns',array($this,'manageEditColumns')); 
		add_action('manage_'.self::getCPTName().'_posts_custom_column',array($this,'managePostsCustomColumn'));
		add_filter('manage_edit-'.self::getCPTName().'_sortable_columns',array($this,'manageEditSortableColumns'));
	}

	/**************************************************************************/
	
	function addMetaBox()
	{
		add_meta_box(PLUGIN_BGCBS_CONTEXT.'_meta_box_location',esc_html__('Main','bookingo'),array($this,'addMetaBoxMain'),self::getCPTName(),'normal','low');		
	}
	
	/**************************************************************************/
	
	function addMetaBoxMain()
	{
		global $post;
		
		$Country=new BGCBSCountry();
		
		$data=array();
		
		$data['meta']=BGCBSPostMeta::getPostMeta($post);
		
		$data['nonce']=BGCBSHelper::createNonceField(PLUGIN_BGCBS_CONTEXT.'_meta_box_location');
		
		$data['dictionary']['country']=$Country->getCountry();

		echo BGCBSTemplate::outputS($data,PLUGIN_BGCBS_TEMPLATE_PATH.'admin/meta_box_location.php');
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
		$Country=new BGCBSCountry();
		
		BGCBSHelper::setDefault($meta,'address_street','');
		BGCBSHelper::setDefault($meta,'address_street_number','');
		BGCBSHelper::setDefault($meta,'address_postcode','');
		BGCBSHelper::setDefault($meta,'address_city','');
		BGCBSHelper::setDefault($meta,'address_state','');
		BGCBSHelper::setDefault($meta,'address_country',$Country->getDefaultCountry());
	}
	
	/**************************************************************************/
	
	function savePost($postId)
	{	  
		if(!$_POST) return(false);
		
		if(BGCBSHelper::checkSavePost($postId,PLUGIN_BGCBS_CONTEXT.'_meta_box_location_noncename','savePost')===false) return(false);
		
		$Country=new BGCBSCountry();
		
		$option=BGCBSHelper::getPostOption();
		
		$key=array
		(
			'address_street',
			'address_street_number',
			'address_postcode',
			'address_city',
			'address_state',
			'address_country'
		);		
		
		if(!$Country->isCountry($option['adress_country']))
			$option['country']=$Country->getDefaultCountry();
		
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
			'location_id'=>0
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
		
		if($attribute['location_id'])
			$argument['p']=$attribute['location_id'];

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
			'address'=>esc_html__('Address','bookingo')
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
			case 'address':
				
				$data=array
				(
					'street'=>$meta['address_street'],
					'street_number'=>$meta['address_street_number'],
					'postcode'=>$meta['address_postcode'],
					'city'=>$meta['address_city'],
					'state'=>$meta['address_state'],
					'country'=>$meta['address_country']
				);
				
				echo BGCBSHelper::displayAddress($data);
				
			break;
		}
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