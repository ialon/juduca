<?php

/******************************************************************************/
/******************************************************************************/

class BGCBSBookingReport
{
	/**************************************************************************/
	
	function __construct()
	{
		
	}
	
	/**************************************************************************/
	
	function init()
	{
		add_action('wp_loaded',array($this,'generate'));
		add_action('manage_posts_extra_tablenav',array($this,'createForm'));
	}
	
	/**************************************************************************/
	
	function createForm()
	{
		if(!is_admin()) return;
		if(BGCBSHelper::getGetValue('post_type',false)!==PLUGIN_BGCBS_CONTEXT.'_booking') return;
		
		$output=
		'
			<div id="to-booking-report-form" class="alignleft actions">
					<button class="to-booking-report-form-generate button">'.esc_html__('Export as CSV','bookingo').'</button>
			</div>
			<script type="text/javascript">
				jQuery(document).ready(function($)
				{
					$(\'.to-booking-report-form-generate\').on(\'click\',function()
					{
						var courseGroupId=$(\'[name="course_group_id"]\').val();
						var bookingStatusId=$(\'[name="booking_status_id"]\').val();
						var universityId=$(\'[name="university"]\').val();

						window.location.href="'.admin_url('edit.php?post_type='.BGCBSBooking::getCPTName()).'&bgcbs_booking_report_form_submit=1&bgcbs_course_group_id="+courseGroupId+"&bgcbs_booking_status_id="+bookingStatusId+"&bgcbs_university="+universityId;
						return(false);
					});			
				});
			</script>
		';
		
		echo $output;
	}
	
	/**************************************************************************/
	
	function generate()
	{
		if(!is_admin()) return;
		
		$bookingReport=BGCBSHelper::getGetValue('booking_report_form_submit');
		if((int)$bookingReport!==1) return;
		
		$Booking=new BGCBSBooking();
		$Validation=new BGCBSValidation();
		
		$query=$this->getBooking();
		
		if($query===false) return;
		
		global $post;

		BGCBSHelper::preservePost($post,$bPost);

		$data=array();
		$formElementOrder=array();
		
		$document=null;
	
		$data[]=__('ID','bookingo');
		$data[]=__('Booking status','bookingo');
		$data[]=__('Course','bookingo');
		$data[]=__('Course group','bookingo');
		$data[]=__('Participant first name','bookingo');
		$data[]=__('Participant second name','bookingo');
		$data[]=__('Applicant first name','bookingo');
		$data[]=__('Applicant second name','bookingo');
		$data[]=__('Applicant e-mail address','bookingo');
		$data[]=__('Applicant phone number','bookingo');
		// $data[]=__('Currency','bookingo');
		// $data[]=__('Price net','bookingo');
		// $data[]=__('Price gross','bookingo');
		// $data[]=__('Tax rate','bookingo');
		
		while($query->have_posts())
		{
			$query->the_post();
			
			$booking=$Booking->getBooking($post->ID);
			
			if(is_array($booking['meta']['form_element_field']))
			{
				foreach($booking['meta']['form_element_field'] as $value)
				{
                    if((int)$value['field_type']===6)
                        continue;

					if(array_key_exists($value['label'],$formElementOrder)) continue;
					
					$data[]=(string)$value['label'];
				
					$formElementOrder[$value['label']]=count($formElementOrder);
				}
			}
		}
		
		$query->rewind_posts();
		
		$document.=implode(',',$data)."\r\n";
		
		while($query->have_posts())
		{
			$query->the_post();
			
			/***/
			
			$data=array();
			
			$booking=$Booking->getBooking($post->ID);
			
			/***/
			
			$data[]=$post->ID;
			$data[]=$booking['booking_status_name'];
			$data[]=$booking['meta']['course_name'];
			$data[]=$booking['meta']['course_group_name'];
			
			$data[]=$booking['meta']['participant_first_name'];
			$data[]=$booking['meta']['participant_second_name'];
			
			$data[]=$booking['meta']['applicant_first_name'];
			$data[]=$booking['meta']['applicant_second_name'];
			$data[]=$booking['meta']['applicant_email_address'];
			$data[]=$booking['meta']['applicant_phone_number'];

			// $data[]=$booking['meta']['currency'];
			// $data[]=$booking['billing']['value_net'];
			// $data[]=$booking['billing']['value_gross'];
			// $data[]=$booking['billing']['tax_rate_value'];
			
			/***/
			
			if(is_array($booking['meta']['form_element_field']))
			{
				foreach($booking['meta']['form_element_field'] as $value)
				{
                    if((int)$value['field_type']===6)
                        continue;

					if((int)$value['field_type']===3)
					{
						$fieldValue=null;

						$dictionary=preg_split('/;/',$value['dictionary']);
						
						foreach($dictionary as $dictionaryIndex=>$dictionaryValue)
						{
							if((isset($value['value'][$dictionaryIndex])) && ((int)$value['value'][$dictionaryIndex]===1))
							{
								if($Validation->isNotEmpty($fieldValue)) $fieldValue.=' / ';
								$fieldValue.=$dictionaryValue;
							}
						}
						
						$cdata[$value['label']]=$fieldValue;
					}
					else
                    {
                        if (is_array($value['value']))
                            $value['value'] = implode(';', $value['value']);
                        $cdata[$value['label']]=$value['value'];
                    }
				}
			}

            foreach ($formElementOrder as $label => $index) {
                $data[] = $cdata[$label] ?? '';
            }
            unset($cdata);
			
			/***/
			
			array_walk($data,function(&$value,&$key)
			{
				$value=preg_replace('/\s+/',' ',$value);
			});
				
			$document.=implode(',',$data)."\r\n";
		}
		
		BGCBSHelper::preservePost($post,$bPost,0);
		
		header($_SERVER['SERVER_PROTOCOL'].' 200 OK');
		header('Cache-Control: public');
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Transfer-Encoding: Binary');
		header('Content-Disposition: attachment;filename=juduca.csv');
		echo $document;
		die();
	}
	
	/**************************************************************************/
	
	function getBooking()
	{
		$metaQuery=array();
		
		/***/
		
		list($courseId,$courseGroupId)=preg_split('/_/',BGCBSHelper::getGetValue('course_group_id'));
		
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
		
		/***/
		
		$bookingStatusId=BGCBSHelper::getGetValue('booking_status_id');

		if((int)$bookingStatusId>0)
		{
			array_push($metaQuery,array
			(
				'key'=>PLUGIN_BGCBS_CONTEXT.'_booking_status_id',
				'value'=>$bookingStatusId,
				'compare'=>'IN'
			));           
		} 

		/***/

        $universityIndex=BGCBSHelper::getGetValue('university');

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
		
		$argument=array
		(
			'post_type'=>BGCBSBooking::getCPTName(),
			'post_status'=>'publish',
			'posts_per_page'=>-1
		);
		
		if(count($metaQuery)) $argument['meta_query']=$metaQuery;

		$query=new WP_Query($argument);
		
		return($query);		
	}
	
	/**************************************************************************/
	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/