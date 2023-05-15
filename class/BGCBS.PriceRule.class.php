<?php

/******************************************************************************/
/******************************************************************************/

class BGCBSPriceRule
{
	/**************************************************************************/
	
	function __construct()
	{
		$this->priceSourceType=array
		(
			1=>array(esc_html__('Set directly in rule','bookingo')),
			2=>array(esc_html__('Calculation based on registration dates','bookingo'))
		);
		
		$this->priceAlterType=array
		(
			1=>array(esc_html__('- Inherited -','bookingo')),
			2=>array(esc_html__('Set value','bookingo')),			
			3=>array(esc_html__('Increase by value','bookingo')),
			4=>array(esc_html__('Decrease by value','bookingo')), 
			5=>array(esc_html__('Increase by percentage','bookingo')),
			6=>array(esc_html__('Decrease by percentage','bookingo')) 
		);
		
		$this->priceUseType=array
		(
			'participant'=>array(esc_html__('Participant','bookingo'))
		);
	}
	
	/**************************************************************************/
	
	function getPriceIndexName($index,$type='value')
	{
		return('price_'.$index.'_'.$type);
	}
	
	/**************************************************************************/
	
	function getPriceAlterType()
	{
		return($this->priceAlterType);
	}
	
	/**************************************************************************/
	
	function isPriceAlterType($priceAlterType)
	{
		return(array_key_exists($priceAlterType,$this->priceAlterType));
	}
	
	/**************************************************************************/
	
	function getPriceUseType()
	{
		return($this->priceUseType);
	}
	
	/**************************************************************************/
	
	function isPriceUseType($priceUseType)
	{
		return(array_key_exists($priceUseType,$this->priceUseType));
	}
	
	/**************************************************************************/
	
	function getPriceSourceType()
	{
		return($this->priceSourceType);
	}
	
	/**************************************************************************/
	
	function isPriceSourceType($type)
	{
		return(array_key_exists($type,$this->getPriceSourceType()));
	}
	
	/**************************************************************************/
	
	function getPriceSourceTypeName($type)
	{
		if(!$this->isPriceSourceType($type)) return('');
		return($this->priceSourceType[$type][0]);
	}
	
	/**************************************************************************/
	
	function extractPriceFromData($price,$data)
	{
		BGCBSHelper::removeUIndex($data,'price_type');
		
		$priceComponent=array('value','alter_type_id','tax_rate_id');
		
		foreach($this->getPriceUseType() as $priceUseTypeIndex=>$priceUseTypeValue)
		{
			foreach($priceComponent as $priceComponentIndex=>$priceComponentValue)
			{				
				$key=$this->getPriceIndexName($priceUseTypeIndex,$priceComponentValue);
				if(isset($data[$key])) $price[$key]=$data[$key];
				else
				{
					if($priceComponentValue==='alter_type_id') $price[$key]=2;
				}
			}
		}
		
		$price['price_type']=$data['price_type'];

		return($price);
	}

	/**************************************************************************/
	
	public function init()
	{
		$this->registerCPT();
	}
	
	/**************************************************************************/

	public static function getCPTName()
	{
		return(PLUGIN_BGCBS_CONTEXT.'_price_rule');
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
					'name'=>esc_html__('Pricing Rules','bookingo'),
					'singular_name'=>esc_html__('Pricing Rule','bookingo'),
					'add_new'=>esc_html__('Add New','bookingo'),
					'add_new_item'=>esc_html__('Add New Pricing Rule','bookingo'),
					'edit_item'=>esc_html__('Edit Pricing Rule','bookingo'),
					'new_item'=>esc_html__('New Pricing Rule','bookingo'),
					'all_items'=>esc_html__('Pricing Rules','bookingo'),
					'view_item'=>esc_html__('View Pricing Rule','bookingo'),
					'search_items'=>esc_html__('Search Pricing Rules','bookingo'),
					'not_found'=>esc_html__('No Pricing Rules Found','bookingo'),
					'not_found_in_trash'=>esc_html__('No Pricing Rules in Trash','bookingo'), 
					'parent_item_colon'=>'',
					'menu_name'=>esc_html__('Pricing Rules','bookingo')
				),	
				'public'=>false,  
				'show_ui'=>true, 
				'show_in_menu'=>'edit.php?post_type='.BGCBSBooking::getCPTName(),
				'capability_type'=>'post',
				'menu_position'=>2,
				'hierarchical'=>false,  
				'rewrite'=>false,  
				'supports'=>array('title','page-attributes')  
			)
		);
		
		add_action('save_post',array($this,'savePost'));
		add_action('add_meta_boxes_'.self::getCPTName(),array($this,'addMetaBox'));
		add_filter('postbox_classes_'.self::getCPTName().'_bgcbs_meta_box_price_rule',array($this,'adminCreateMetaBoxClass'));
		
		add_filter('manage_edit-'.self::getCPTName().'_columns',array($this,'manageEditColumns')); 
		add_action('manage_'.self::getCPTName().'_posts_custom_column',array($this,'managePostsCustomColumn'));
		add_filter('manage_edit-'.self::getCPTName().'_sortable_columns',array($this,'manageEditSortableColumns'));
	}

	/**************************************************************************/
	
	function addMetaBox()
	{
		add_meta_box(PLUGIN_BGCBS_CONTEXT.'_meta_box_price_rule',esc_html__('Main','bookingo'),array($this,'addMetaBoxMain'),self::getCPTName(),'normal','low');		
	}
	
	/**************************************************************************/
	
	function addMetaBoxMain()
	{
		global $post;
		
		$data=array();
		
		$Course=new BGCBSCourse();
		$TaxRate=new BGCBSTaxRate();
		$CourseGroup=new BGCBSCourseGroup();
		$BookingForm=new BGCBSBookingForm();
		
		$data['meta']=BGCBSPostMeta::getPostMeta($post);
		
		$data['nonce']=BGCBSHelper::createNonceField(PLUGIN_BGCBS_CONTEXT.'_meta_box_price_rule');

		$data['dictionary']['course']=$Course->getDictionary();
		$data['dictionary']['course_group']=$CourseGroup->getDictionary();
		
		$data['dictionary']['tax_rate']=$TaxRate->getDictionary();
		$data['dictionary']['booking_form']=$BookingForm->getDictionary();

		$data['dictionary']['price_alter_type']=$this->getPriceAlterType();
		$data['dictionary']['price_source_type']=$this->getPriceSourceType();
		
		echo BGCBSTemplate::outputS($data,PLUGIN_BGCBS_TEMPLATE_PATH.'admin/meta_box_price_rule.php');
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
		
		BGCBSHelper::setDefault($meta,'booking_form_id',array(-1));
		
		BGCBSHelper::setDefault($meta,'course_id',array(-1));
		BGCBSHelper::setDefault($meta,'course_group_id',array(-1));
		
		BGCBSHelper::setDefault($meta,'registration_date',array());
		
		BGCBSHelper::setDefault($meta,'price_source_type',1);
		BGCBSHelper::setDefault($meta,'process_next_rule_enable',0);
		
		foreach($this->getPriceUseType() as $index=>$value)
		{
			BGCBSHelper::setDefault($meta,'price_'.$index.'_value','0.00');
			BGCBSHelper::setDefault($meta,'price_'.$index.'_alter_type_id',2);
			BGCBSHelper::setDefault($meta,'price_'.$index.'_tax_rate_id',$TaxRate->getDefaultTaxPostId());   
		}
	}
	
	/**************************************************************************/
	
	function savePost($postId)
	{	  
		if(!$_POST) return(false);
		
		if(BGCBSHelper::checkSavePost($postId,PLUGIN_BGCBS_CONTEXT.'_meta_box_price_rule_noncename','savePost')===false) return(false);
		
		$Date=new BGCBSDate();
		$TaxRate=new BGCBSTaxRate();
		$BookingForm=new BGCBSBookingForm();
		
		$Course=new BGCBSCourse();
		$CourseGroup=new BGCBSCourseGroup();
		
		$Validation=new BGCBSValidation();
		
		$option=BGCBSHelper::getPostOption();
		
		/***/
		
		$dictionary=array
		(
			'booking_form_id'=>array
			(
				'dictionary'=>$BookingForm->getDictionary()
			),
			'course_id'=>array
			(
				'dictionary'=>$Course->getDictionary()
			),			
			'course_group_id'=>array
			(
				'dictionary'=>$CourseGroup->getDictionary()
			)
		);
		
		foreach($dictionary as $dIndex=>$dValue)
		{
			$option[$dIndex]=(array)BGCBSHelper::getPostValue($dIndex);
			if(in_array(-1,$option[$dIndex]))
			{
				$option[$dIndex]=array(-1);
			}
			else
			{
				foreach($option[$dIndex] as $oIndex=>$oValue)
				{
					if(!isset($dValue['dictionary']))
						unset($option[$dIndex][$oIndex]);				
				}
			}			 
		}
		
		/***/
		
		$date=array();
	   
		foreach($option['registration_date']['start'] as $index=>$value)
		{
			$d=array($value,$option['registration_date']['stop'][$index],$option['registration_date']['price'][$index]);
			
			$d[0]=$Date->formatDateToStandard($d[0]);
			$d[1]=$Date->formatDateToStandard($d[1]);
			
			if(!$Validation->isDate($d[0])) continue;
			if(!$Validation->isDate($d[1])) continue;
			
			if($Date->compareDate($d[0],$d[1])==1) continue;
			
			if(!$Validation->isPrice($d[2],true)) $d[2]=0.00;
			
			array_push($date,array('start'=>$d[0],'stop'=>$d[1],'price'=>BGCBSPrice::formatToSave($d[2],true)));
		}

		$option['registration_date']=$date;

		/***/
		
		if(!$this->isPriceSourceType($option['price_source_type']))
			$option['price_source_type']=1;
		if(!$Validation->isBool($option['process_next_rule_enable']))
			$option['process_next_rule_enable']=0;
		
		/***/
		
		foreach($this->getPriceUseType() as $index=>$value)
		{
			if(!$Validation->isPrice($option['price_'.$index.'_value'],false))
				$option['price_'.$index.'_value']=0.00;
			
			$option['price_'.$index.'_value']=BGCBSPrice::formatToSave($option['price_'.$index.'_value']);
			
			if(!$this->isPriceAlterType($option['price_'.$index.'_alter_type_id']))
				$option['price_'.$index.'_alter_type_id']=1;
			
			if(in_array($option['price_'.$index.'_alter_type_id'],array(5,6)))
			{
				if(!$Validation->isNumber($option['price_'.$index.'_alter_type_id'],0,100))
					$option['price_'.$index.'_alter_type_id']=0;
			}
		 
			if((int)$option['price_'.$index.'_tax_rate_id']===-1)
				$option['price_'.$index.'_tax_rate_id']=-1;
			else
			{
				if(!$TaxRate->isTaxRate($option['price_'.$index.'_tax_rate_id']))
					$option['price_'.$index.'_tax_rate_id']=0; 
			}
		}
		
		/***/

		$key=array
		(
			'booking_form_id',
			'course_id',
			'course_group_id',
			'registration_date',
			'price_source_type',
			'process_next_rule_enable'
		);
		
		foreach($this->getPriceUseType() as $index=>$value)
			array_push($key,'price_'.$index.'_value','price_'.$index.'_alter_type_id','price_'.$index.'_tax_rate_id');
		
		array_unique($key);
		
		foreach($key as $value)
			BGCBSPostMeta::updatePostMeta($postId,$value,$option[$value]);
	}
	
	/**************************************************************************/

	function manageEditColumns($column)
	{
		$column=array
		(
			'cb'=>$column['cb'],
			'title'=>$column['title'],
			'detail'=>esc_html__('Conditions and details','bookingo'),
			'price'=>esc_html__('Prices','bookingo')
		);
   
		return($column);		   
	}
	
	/**************************************************************************/
	
	function getPricingRuleAdminListDictionary()
	{
		$dictionary=array();
	
		$Course=new BGCBSCourse();
		$CourseGroup=new BGCBSCourseGroup();
		$BookingForm=new BGCBSBookingForm();
		
		$dictionary['course']=$Course->getDictionary();
		$dictionary['course_group']=$CourseGroup->getDictionary();
		$dictionary['booking_form']=$BookingForm->getDictionary();

		return($dictionary);
	}
	
	/**************************************************************************/
	
	function displayPricingRuleAdminListValue($data,$dictionary,$link=false,$sort=false)
	{
		if(in_array(-1,$data)) return(esc_html__(' - ','bookingo'));
		
		$html=null;
		
		$dataSort=array();

		foreach($data as $value)
		{
			if(!array_key_exists($value,$dictionary)) continue;

			if(array_key_exists('post',$dictionary[$value]))
				$label=$dictionary[$value]['post']->post_title;
			else $label=$dictionary[$value][0];			

			$dataSort[$value]=$label;
		}

		if($sort) asort($dataSort);

		$data=$dataSort;
		
		foreach($data as $index=>$value)
		{
			$label=esc_html($value);
			
			if($link) $label='<a href="'.get_edit_post_link($index).'">'.esc_html($value).'</a>';
			$html.='<div>'.$label.'</div>';
		}
		
		return($html);
	}
	
	/**************************************************************************/
	
	function managePostsCustomColumn($column)
	{
		global $post;
		
		$Date=new BGCBSDate();
		$Validation=new BGCBSValidation();
		
		$meta=BGCBSPostMeta::getPostMeta($post);
		
		$dictionary=BGCBSGlobalData::setGlobalData('pricing_rule_admin_list_dictionary',array($this,'getPricingRuleAdminListDictionary'));
		
		switch($column) 
		{
			case 'detail':
				
				$html=array
				(
					'registration_date'=>''
				);
				
				if((isset($meta['registration_date'])) && (count($meta['registration_date'])))
				{
					foreach($meta['registration_date'] as $value)
					{
						if(!$Validation->isEmpty($html['registration_date'])) $html['registration_date'].='<br>';
						$html['registration_date'].=esc_html($Date->formatDateToDisplay($value['start']).' - '.$Date->formatDateToDisplay($value['stop']));	  
						
						if((int)$meta['price_source_type']===2)
							$html['registration_date'].=esc_html(' ('.BGCBSPrice::format($value['price'],BGCBSOption::getOption('currency')).')');
					}
				}   
				
				foreach($html as $index=>$value)
				{
					if($Validation->isEmpty($value)) $html[$index]=esc_html__(' - ','bookingo');				
				}
				
				/***/
				
				echo 
				'
					<table class="to-table-post-list">
						<tr>
							<td>'.esc_html__('Booking form','bookingo').'</td>
							<td>'.$this->displayPricingRuleAdminListValue($meta['booking_form_id'],$dictionary['booking_form'],true,true).'</td>
						</tr>
						<tr>
							<td>'.esc_html__('Course','bookingo').'</td>
							<td>'.$this->displayPricingRuleAdminListValue($meta['course_id'],$dictionary['course'],true,true).'</td>
						</tr>
						<tr>
							<td>'.esc_html__('Course group','bookingo').'</td>
							<td>'.$this->displayPricingRuleAdminListValue($meta['course_group_id'],$dictionary['course_group'],true,true).'</td>
						</tr>
						<tr>
							<td>'.esc_html__('Registration dates','bookingo').'</td>
							<td>'.$html['registration_date'].'</td>
						</tr>
						<tr>
							<td>'.esc_html__('Priority','bookingo').'</td>
							<td>'.(int)$post->menu_order.'</td>
						</tr>
						<tr>
							<td>'.esc_html__('Next rule processing','bookingo').'</td>
							<td>'.((int)$meta['process_next_rule_enable']===1 ? esc_html__('Enable','bookingo') : esc_html__('Disable','bookingo')).'</td>
						</tr>
					</table>
				';

			break;
		
			case 'price':
				
				echo 
				'
					<table class="to-table-post-list">
						<tr>
							<td>'.esc_html__('Price source type','bookingo').'</td>
							<td>'.$this->getPriceSourceTypeName($meta['price_source_type']).'</td>
						</tr>  
				';
				
				foreach($this->getPriceUseType() as $index=>$value)
				{
					echo 
					'
						<tr>
							<td>'.esc_html($value[0]).'</td>
							<td>'.self::displayPriceAlter($meta,$index).'</td>
						</tr>	
					';
				}
				
				echo
				'
					</table>
				';
				
			break;
		}
	}
	
	/**************************************************************************/
	
	static function displayPriceAlter($meta,$priceUseType)
	{
		$charBefore=null;
		
		if(in_array($meta['price_'.$priceUseType.'_alter_type_id'],array(3,5)))
			$charBefore='+ ';
		if(in_array($meta['price_'.$priceUseType.'_alter_type_id'],array(4,6)))
			$charBefore='- ';		
		
		if(in_array($meta['price_'.$priceUseType.'_alter_type_id'],array(1)))
		{
			return(esc_html__('Inherited','bookingo'));
		}
		elseif(in_array($meta['price_'.$priceUseType.'_alter_type_id'],array(2)))
		{
			return(BGCBSPrice::format($meta['price_'.$priceUseType.'_value'],BGCBSOption::getOption('currency')));
		}
		elseif(in_array($meta['price_'.$priceUseType.'_alter_type_id'],array(3,4)))
		{
			return($charBefore.BGCBSPrice::format($meta['price_'.$priceUseType.'_value'],BGCBSOption::getOption('currency')));
		}
		elseif(in_array($meta['price_'.$priceUseType.'_alter_type_id'],array(5,6)))
		{
			return($charBefore.$meta['price_'.$priceUseType.'_value'].'%');
		}
	}
	
	/**************************************************************************/
	
	function manageEditSortableColumns($column)
	{
		return($column);	   
	}
	
   /**************************************************************************/
	
	function getDictionary($attr=array())
	{
		global $post;
		
		$dictionary=array();
		
		$default=array
		(
			'price_rule_id'=>0
		);
		
		$attribute=shortcode_atts($default,$attr);
		BGCBSHelper::preservePost($post,$bPost);
		
		$argument=array
		(
			'post_type'=>self::getCPTName(),
			'post_status'=>'publish',
			'posts_per_page'=>-1,
			'orderby'=>array('menu_order'=>'desc')
		);
		
		if($attribute['price_rule_id'])
			$argument['p']=$attribute['price_rule_id'];
			   
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
	
	function getPriceFromRule($bookingData,$bookingForm,$price)
	{
		$Date=new BGCBSDate();
		
		$rule=$bookingForm['dictionary']['price_rule'];
		if($rule===false) return($price);

		foreach($rule as $ruleData)
		{
			$pricePerParticipant=-1;
			
			if(!in_array(-1,$ruleData['meta']['booking_form_id']))
			{
				if(!in_array($bookingData['booking_form_id'],$ruleData['meta']['booking_form_id'])) continue;
			}			
			
			if(!in_array(-1,$ruleData['meta']['course_id']))
			{
				if(!in_array($bookingData['course_id'],$ruleData['meta']['course_id'])) continue;
			}	
			
			if(!in_array(-1,$ruleData['meta']['course_group_id']))
			{
				if(!in_array($bookingData['course_group_id'],$ruleData['meta']['course_group_id'])) continue;
			}				
			
			if(is_array($ruleData['meta']['registration_date']))
			{
				if(count($ruleData['meta']['registration_date']))
				{
					$match=false;
					
					if(in_array($ruleData['meta']['price_source_type'],array(2)))
					{
						foreach($ruleData['meta']['registration_date'] as $value)
						{
							if($Date->dateInRange($bookingData['registration_date'],$value['start'],$value['stop']))
							{
								$match=true;
								$pricePerParticipant=$value['price'];
								break;
							}
						}							
					}
					else
					{
						foreach($ruleData['meta']['registration_date'] as $value)
						{
							if($Date->dateInRange($bookingData['registration_date'],$value['start'],$value['stop']))
							{
								$match=true;
								break;
							}
						}
					}

					if(!$match) continue;
				}
			}
			
			if($pricePerParticipant!=-1)
			{
				$price['price_participant_value']=$pricePerParticipant;
				$pricePerParticipant=-1;
			}
			else
			{
				foreach($this->getPriceUseType() as $index=>$value)
				{
					if((int)$ruleData['meta']['price_'.$index.'_alter_type_id']===2)
					{
						$price['price_'.$index.'_value']=$ruleData['meta']['price_'.$index.'_value'];
					}
					elseif(in_array((int)$ruleData['meta']['price_'.$index.'_alter_type_id'],array(3,4))) 
					{
						if((int)$ruleData['meta']['price_'.$index.'_alter_type_id']===3)
							$price['price_'.$index.'_value']+=$ruleData['meta']['price_'.$index.'_value'];
						if((int)$ruleData['meta']['price_'.$index.'_alter_type_id']===4)
							$price['price_'.$index.'_value']-=$ruleData['meta']['price_'.$index.'_value'];
					}
					elseif(in_array((int)$ruleData['meta']['price_'.$index.'_alter_type_id'],array(5,6)))
					{
						if((int)$ruleData['meta']['price_'.$index.'_alter_type_id']===5)
						{
							$price['price_'.$index.'_value']=$price['price_'.$index.'_value']*(1+$ruleData['meta']['price_'.$index.'_value']/100); 
						}
						elseif((int)$ruleData['meta']['price_'.$index.'_alter_type_id']===6)
							$price['price_'.$index.'_value']=$price['price_'.$index.'_value']*(1-$ruleData['meta']['price_'.$index.'_value']/100); 
					}

					if($price['price_'.$index.'_value']<0)
						$price['price_'.$index.'_value']=0;
				}
			}
			
			foreach($this->getPriceUseType() as $index=>$value)
			{
				if((int)$ruleData['meta']['price_'.$index.'_tax_rate_id']!==0)
					$price['price_'.$index.'_tax_rate_id']=$ruleData['meta']['price_'.$index.'_tax_rate_id'];			
			}

			if((int)$ruleData['meta']['process_next_rule_enable']!==1) return($price);
		}
		
		return($price);
	}
	   
	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/