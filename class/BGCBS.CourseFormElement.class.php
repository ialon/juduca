<?php

/******************************************************************************/
/******************************************************************************/

class BGCBSCourseFormElement
{
	/**************************************************************************/
	
	function __construct()
	{
		$this->fieldType=array
		(
			1=>array(esc_html__('Text','bookingo')),
			4=>array(esc_html__('Textarea','bookingo')),
			2=>array(esc_html__('Select list','bookingo')),
			3=>array(esc_html__('Checkbox','bookingo')),
            5=>array(esc_html__('Date','bookingo')),
            6=>array(esc_html__('Upload','bookingo')),
		);
	}
	
	/**************************************************************************/
	
	function getFieldType()
	{
		return($this->fieldType);
	}
	
	/**************************************************************************/
	
	function isFieldType($fieldType)
	{
		return(array_key_exists($fieldType,$this->getFieldType()) ? true : false);
	}
	
	/**************************************************************************/
	   
	function save($bookingFormId)
	{
		/***/
		
		$formElementPanel=array();
		$formElementPanelPost=BGCBSHelper::getPostValue('form_element_panel');
		
		if(isset($formElementPanelPost['id']))
		{
			$Validation=new BGCBSValidation();
			
			foreach($formElementPanelPost['id'] as $index=>$value)
			{
				if($Validation->isEmpty($formElementPanelPost['label'][$index])) continue;
				
				if($Validation->isEmpty($value))
					$value=BGCBSHelper::createId();
				
				$formElementPanel[]=array('id'=>$value,'label'=>$formElementPanelPost['label'][$index]);
			}
		}
		
		BGCBSPostMeta::updatePostMeta($bookingFormId,'form_element_panel',$formElementPanel); 
		
		$meta=BGCBSPostMeta::getPostMeta($bookingFormId);
		
		/***/
		
		$formElementField=array();
		$formElementFieldPost=BGCBSHelper::getPostValue('form_element_field');		
		
		if(isset($formElementFieldPost['id']))
		{
			$Validation=new BGCBSValidation();
			
			$panelDictionary=$this->getPanel($meta);
			
			foreach($formElementFieldPost['id'] as $index=>$value)
			{
				if(!isset($formElementFieldPost['label'][$index],$formElementFieldPost['field_type'][$index],$formElementFieldPost['mandatory'][$index],$formElementFieldPost['dictionary'][$index],$formElementFieldPost['message_error'][$index],$formElementFieldPost['panel_id'][$index])) continue;
				
				if($Validation->isEmpty($formElementFieldPost['label'][$index])) continue;
				
				if(!$this->isFieldType($formElementFieldPost['field_type'][$index])) continue;
				
				if(in_array($formElementFieldPost['field_type'][$index],array(2,3)))
				{
					if($Validation->isEmpty($formElementFieldPost['dictionary'][$index])) continue;
				}
				
				if(!$Validation->isBool((int)$formElementFieldPost['mandatory'][$index])) continue;
				else 
				{
					if($formElementFieldPost['mandatory'][$index]==1)
					{	
						if($Validation->isEmpty($formElementFieldPost['message_error'][$index])) continue;
					}
				}
				
				if(!$this->isPanel($formElementFieldPost['panel_id'][$index],$panelDictionary)) continue;
				
				if($Validation->isEmpty($value))
					$value=BGCBSHelper::createId();
				
				$formElementField[]=array('id'=>$value,'label'=>$formElementFieldPost['label'][$index],'field_type'=>$formElementFieldPost['field_type'][$index],'mandatory'=>$formElementFieldPost['mandatory'][$index],'dictionary'=>$formElementFieldPost['dictionary'][$index],'message_error'=>$formElementFieldPost['message_error'][$index],'panel_id'=>$formElementFieldPost['panel_id'][$index]);
			}
		}  
		
		BGCBSPostMeta::updatePostMeta($bookingFormId,'form_element_field',$formElementField);	 
	}
	
	/**************************************************************************/
	
	function getPanel($meta)
	{
		$panel=array
		(
			array
			(
				'id'=>1,
				'label'=>esc_html__('- Course Participant Data -','bookingo')
			),
			array
			(
				'id'=>2,
				'label'=>esc_html__('- Applicant Data -','bookingo')				
			)
		);
			 
		if(isset($meta['form_element_panel']))
		{
			foreach($meta['form_element_panel'] as $value)
				$panel[]=$value;
		}
		
		return($panel);
	}
	
	/**************************************************************************/
	
	function getFieldValueByLabel($label,$meta)
	{
		if(is_array($meta))
		{
			foreach($meta['form_element_field'] as $value)
			{
				if($value['label']==$label) return($value['value']);
			}
		}
		
		return(null);
	}

	/**************************************************************************/
	
	function isPanel($panelId,$panelDictionary)
	{
		foreach($panelDictionary as $value)
		{
			if($value['id']==$panelId) return(true);
		}
		
		return(false);
	}
	
	/**************************************************************************/
	
	function createField($panelId,$meta)
	{
		$html=null;
		
		$Validation=new BGCBSValidation();
		
		if(!array_key_exists('form_element_field',$meta)) return(null);
		
		foreach($meta['form_element_field'] as $value)
		{
			if($value['panel_id']==$panelId)
			{
				$name='form_element_field_'.$value['id'];
				
				if(in_array($value['field_type'],array(1,2,3,4,5,6)))
				{
					$html.=
					'
						<div class="bgcbs-clear-fix">
							<div class="bgcbs-form-field">
								<label>'.esc_html($value['label']).((int)$value['mandatory']===1 ? ' *' : '').'</label>
					';
					
					if(in_array($value['field_type'],array(2,3)))
					{
						$fieldHtml=null;
						$fieldValue=preg_split('/;/',$value['dictionary']);
						
                        if($value['label'] == 'Universidad')
                        {
                            global $wpdb;
                            $fieldValue = [];
                            $universities = $wpdb->get_results("SELECT DISTINCT company_name FROM {$wpdb->prefix}swpm_members_tbl");
                            foreach($universities as $university)
                            {
                                $fieldValue[] = $university->company_name;
                            }
                        }

						if((int)$value['field_type']===2)
						{
							foreach($fieldValue as $fieldValueValue)
							{
								if($Validation->isNotEmpty($fieldValueValue))
									$fieldHtml.='<option value="'.esc_attr($fieldValueValue).'"'.BGCBSHelper::selectedIf($fieldValueValue,BGCBSHelper::getPostValue($name),false).'>'.esc_html($fieldValueValue).'</option>';
							}


                            if(!current_user_can('administrator') && $value['label'] == 'Universidad')
                            {
                                global $user_identity;
                                $university = $wpdb->get_row($wpdb->prepare("SELECT company_name FROM {$wpdb->prefix}swpm_members_tbl WHERE user_name = %d", $user_identity));
                                $html.=
                                '
                                    <input type="text" name="'.BGCBSHelper::getFormName($name,false).'"  value="'.esc_attr($university->company_name).'" disabled />	
                                ';
                            }
                            else {
                                $html .=
                                '
                                    <select name="' . BGCBSHelper::getFormName($name, false) . '">
                                        ' . $fieldHtml . '
                                    </select>
                                ';
                            }
                        }
						else if((int)$value['field_type']===3)
						{
							foreach($fieldValue as $fieldValueValue)
							{
								if($Validation->isNotEmpty($fieldValueValue))
								{
									$fieldHtml.=
									'
										<div class="bgcbs-form-checkbox-field bgcbs-form-checkbox-field-style-1">
											<span></span>
											<div>'.esc_html($fieldValueValue).'</div>
											<input type="hidden" name="'.BGCBSHelper::getFormName($name,false).'[]" value="0"/>
										</div>
									';
								}
							}	
							
							$html.=
							'
								<div class="bgcbs-form-checkbox-field-list bgcbs-form-checkbox-field-list-style-1 bgcbs-clear-fix">
									'.$fieldHtml.'
								</div>
							';								
						}
					}
					elseif((int)$value['field_type']===1)
					{
						$html.=
						'
							<input type="text" name="'.BGCBSHelper::getFormName($name,false).'"  value="'.esc_attr(BGCBSHelper::getPostValue($name)).'"/>	
						';
					}
					elseif((int)$value['field_type']===4)
					{
						$html.=
						'
							<textarea name="'.BGCBSHelper::getFormName($name,false).'">'.esc_html(BGCBSHelper::getPostValue($name)).'</textarea>
						';
					}
                    elseif((int)$value['field_type']===5)
                    {
                        $html.=
                        '
							<input type="date" min="1994-01-01" max="2007-01-01" name="'.BGCBSHelper::getFormName($name,false).'"  value="'.esc_attr(BGCBSHelper::getPostValue($name)).'"/>	
						';
                    }
                    elseif((int)$value['field_type']===6)
                    {
                        $html.=
                            '
							<input type="file" name="'.BGCBSHelper::getFormName($name,false).'" value="'.esc_attr(BGCBSHelper::getPostValue($name)).'"/>	
						';
                    }

					$html.=
					'							
							</div>						
						</div>
					';
				}
			}
		}
		
		return($html);
	}
	
	/**************************************************************************/
	
	function validate($bookingForm,$data)
	{
		$error=array();
		
		$Validation=new BGCBSValidation();
		
		$course=$bookingForm['course'][$bookingForm['course_id']];
		
		if(!array_key_exists('form_element_field',$course['meta'])) return($error);
		
		foreach($course['meta']['form_element_field'] as $value)
		{
			$name='form_element_field_'.$value['id'];
			
			if((int)$value['panel_id']===2)
			{
				if((int)$course['meta']['applicant_data_enable']!==1) continue;
			}
			
			if((int)$value['mandatory']===1)
			{
				$name1=$name2=$name;
				
				if(array_key_exists($name1,$data))
				{
					if(is_array($data[$name1]))
					{
						if((int)$value['field_type']===3)
						{
							$empty=true;
							foreach($data[$name1] as $a=>$b)
							{
								if((int)$b===1)
								{
									$empty=false;
									break;
								}
							}
							
							if($empty)
								$error[]=array('name'=>BGCBSHelper::getFormName($name2,false).'[]','message_error'=>$value['message_error']);
						}
					}
					else
					{					
						if($Validation->isEmpty($data[$name1]))
							$error[]=array('name'=>BGCBSHelper::getFormName($name2,false),'message_error'=>$value['message_error']);
					}
				}
			}

            if((int)$value['field_type']===5)
            {
                $birthdate = DateTimeImmutable::createFromFormat("Y-m-d", $data[$name]);
                $agelimit = DateTimeImmutable::createFromFormat("Y-m-d", "1994-01-01");
                if ($birthdate < $agelimit)
                    $error[]=array('name'=>BGCBSHelper::getFormName($name,false),'message_error'=>esc_html__('The selected birth date exceeds the maximum age allowed.','bookingo'));
            }

            if((int)$value['field_type']===6)
            {
                $uploadname = 'bgcbs_' . $name;
                if(empty($_FILES) || !isset($_FILES[$uploadname]) || $_FILES[$uploadname]['size'] == 0)
                {
                    $error[]=array('name'=>BGCBSHelper::getFormName($name,false),'message_error'=>$value['message_error']);
                }
            }

            if((int)$value['field_type']===1 && $value['label']=='No. de Documento de viaje')
            {
                global $wpdb;
                $bookings = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}posts WHERE post_type = 'bgcbs_booking'");
                foreach($bookings as $booking)
                {
                    $meta=BGCBSPostMeta::getPostMeta($booking);
                    foreach($meta['form_element_field'] as $elementfield)
                    {
                        if (($elementfield['label'] == 'No. de Documento de viaje') && $elementfield['value'] == $data[$name]) {
                            $error[]=array('name'=>BGCBSHelper::getFormName($name,false),'message_error'=>esc_html__('This document number has already been used.','bookingo'));
                        }
                    }
                }
            }
		}
		
		return($error);
	}

	/**************************************************************************/
	
	function send($bookingId,$bookingForm,$data)
	{
		$course=$bookingForm['course'][$bookingForm['course_id']];
		
		if(!array_key_exists('form_element_field',$course['meta'])) return;
		
		foreach($course['meta']['form_element_field'] as $index=>$value)
		{
			$name='form_element_field_'.$value['id']; 
			$course['meta']['form_element_field'][$index]['value']=$data[$name];

            if(!current_user_can('administrator') && $value['label'] == 'Universidad')
            {
                global $wpdb, $user_identity;
                $university = $wpdb->get_row($wpdb->prepare("SELECT company_name FROM {$wpdb->prefix}swpm_members_tbl WHERE user_name = %d", $user_identity));
                $course['meta']['form_element_field'][$index]['value']=$university->company_name;
            }

            if((int)$value['field_type']===6)
            {
                $uploadname = 'bgcbs_' . $name;
                $attachment_id = media_handle_upload($uploadname, $bookingId);
                $course['meta']['form_element_field'][$index]['value']=$attachment_id;
            }
		}
		
		BGCBSPostMeta::updatePostMeta($bookingId,'form_element_panel',$course['meta']['form_element_panel']);
		BGCBSPostMeta::updatePostMeta($bookingId,'form_element_field',$course['meta']['form_element_field']);
	}
	
	/**************************************************************************/
	
	function display($panelId,$booking,$type=1,$argument=array())
	{
		$html=null;
		
		$Validation=new BGCBSValidation();
		
		if(!array_key_exists('form_element_field',$booking['meta'])) return($html);
		
		foreach($booking['meta']['form_element_field'] as $value)
		{
			if($value['panel_id']==$panelId)
			{
				$fieldValue=$value['value'];
				$fieldLabel=$value['label'];
				
				if((int)$value['field_type']===3)
				{
					$fieldValue=null;
					
					$dictionary=preg_split('/;/',$value['dictionary']);
					foreach($dictionary as $dictionaryIndex=>$dictionaryValue)
					{
						if((isset($value['value'][$dictionaryIndex])) && ((int)$value['value'][$dictionaryIndex]===1))
						{
							if($Validation->isNotEmpty($fieldValue)) $fieldValue.=', ';
							$fieldValue.=$dictionaryValue;
						}
					}
				}

				if($type===1)
				{
					$html.=
					'
						<div class="to-clear-fix">
							<span class="to-legend-field">'.esc_html($fieldLabel).'.</span>
							<div class="to-field-disabled">'.esc_html($fieldValue).'</div>								
						</div> 
					';
				}
				else if($type===2)
				{
				   $html.=
					'
						<tr>
							<td '.$argument['style']['cell'][1].'>'.esc_html($fieldLabel).'</td>
							<td '.$argument['style']['cell'][2].'>'.esc_html($fieldValue).'</td>
						</tr>
					'; 
				}
			}
		}
		
		return($html);
	}

	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/