<?php

/******************************************************************************/
/******************************************************************************/

class BGCBSCourseAgreement
{
	/**************************************************************************/
	
	function __construct()
	{
	   
	}
	
	/**************************************************************************/
	   
	function save($courseId)
	{
		$agreement=array();
		$agreementPost=BGCBSHelper::getPostValue('agreement');		
		
		if(isset($agreementPost['id']))
		{
			$Validation=new BGCBSValidation();
			
			foreach($agreementPost['id'] as $index=>$value)
			{
				if(!isset($agreementPost['text'][$index])) continue;
				
				if($Validation->isEmpty($agreementPost['text'][$index])) continue;
				if(!$Validation->isBool($agreementPost['mandatory'][$index])) continue;
				
				if($Validation->isEmpty($value))
					$value=BGCBSHelper::createId();
				
				$agreement[]=array('id'=>$value,'text'=>$agreementPost['text'][$index],'mandatory'=>$agreementPost['mandatory'][$index]);
			}
		}	  
		
		BGCBSPostMeta::updatePostMeta($courseId,'agreement',$agreement);		
	}
	
	/**************************************************************************/
	
	function validate($bookingForm,$data)
	{
		$course=$bookingForm['course'][$bookingForm['course_id']];
		
		if(!array_key_exists('agreement',$course['meta'])) return(false);
		
		foreach($course['meta']['agreement'] as $value)
		{
			$name='agreement_'.$value['id'];  
            if((!array_key_exists('mandatory',$value)) || ((int)$value['mandatory']===1))
            {
                if((int)$data[$name]!==1) return(true);
            }
		}
		
		return(false);
	}
	
	/**************************************************************************/
	
	function createAgreement($bookingForm)
	{
		$html=null;
		$Validation=new BGCBSValidation();
		
		$courseMeta=$bookingForm['course'][$bookingForm['course_id']]['meta'];
	
		if(!array_key_exists('agreement',$courseMeta)) return($html);
		
		foreach($courseMeta['agreement'] as $value)
		{
			$html.=
			'
				<li>
					<div class="bgcbs-form-checkbox-field bgcbs-form-checkbox-field-style-2">
						<span></span>
						<div>'.wp_kses($value['text'],array('a'=>array('href'=>array(),'target'=>array()))).'</div>
						<input type="hidden" name="'.BGCBSHelper::getFormName('agreement_'.$value['id'],false).'" value="0"/> 
					</div>  
				</li>
			';
		}
		
		if($Validation->isNotEmpty($html))
		{
			$html=
			'
				<div class="bgcbs-course-agreement-form">
				
					<h2>'.esc_html__('Consent and Statements','bookingo').'</h2>
				
					<div>

						<ul class="bgcbs-list-reset">'.$html.'</ul>

					</div>

				</div>
			';
		}
		
		return($html);
	}
	
	/**************************************************************************/
	
	function display($booking,$type=1)
	{
		$html=null;
		
		if(!array_key_exists('agreement',$booking['meta'])) return($html);
		
		foreach($booking['meta']['agreement'] as $value)
		{
			if($type==1)
			{
				$html.=
				'
					<tr>
						<td><div>'.$value['text'].'</div></td>
						<td><div>'.((int)$value['value']===1 ? esc_html__('Yes','bookingo') : esc_html__('No','bookingo')).'</div></td>
					</tr>
				';					
			}
			else if($type==2)
			{
				$html.=
				'
					<tr>
						<td><div><b>'.((int)$value['value']===1 ? esc_html__('Yes','bookingo') : esc_html__('No','bookingo')).'</b> '.$value['text'].'</div></td>
					</tr>
				';					
			}
		}
		
		return($html);
	}
	
	/**************************************************************************/
	
	function send($bookingId,$bookingForm,$data)
	{
		$course=$bookingForm['course'][$bookingForm['course_id']];
		
		if(!array_key_exists('agreement',$course['meta'])) return;
		
		foreach($course['meta']['agreement'] as $index=>$value)
		{
			$name='agreement_'.$value['id']; 
			$course['meta']['agreement'][$index]['value']=(int)$data[$name];
		}
		
		BGCBSPostMeta::updatePostMeta($bookingId,'agreement',$course['meta']['agreement']);
	}
	
	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/