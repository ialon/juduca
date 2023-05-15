<?php

/******************************************************************************/
/******************************************************************************/

class BGCBSPostMeta
{
	/**************************************************************************/
	
	static function getPostMeta($post)
	{
		$data=array();
		
		$prefix=PLUGIN_BGCBS_CONTEXT.'_';
		
		if(!is_object($post)) $post=get_post($post);
		
		$meta=get_post_meta($post->ID);
		
		if(!is_array($meta)) $meta=array();
		
		foreach($meta as $metaIndex=>$metaData)
		{
			if(preg_match('/^'.$prefix.'/',$metaIndex))
				$data[preg_replace('/'.$prefix.'/',null,$metaIndex)]=$metaData[0];
		}
		
		switch($post->post_type)
		{
 			case PLUGIN_BGCBS_CONTEXT.'_booking':
				
				self::unserialize($data,array('payment_stripe_data','payment_paypal_data','form_element_panel','form_element_field','agreement'));
  
				$Booking=new BGCBSBooking();
				$Booking->setPostMetaDefault($data);
				
			break;
		
			case PLUGIN_BGCBS_CONTEXT.'_booking_form':
				
				self::unserialize($data,array('currency','style_color'));
  
				$BookingForm=new BGCBSBookingForm();
				$BookingForm->setPostMetaDefault($data);
				
			break;
		
			case PLUGIN_BGCBS_CONTEXT.'_course':
				
				self::unserialize($data,array('course_group_id','payment_id','form_element_panel','form_element_field','agreement'));
				
				$Course=new BGCBSCourse();
				$Course->setPostMetaDefault($data);
				
			break;
		
			case PLUGIN_BGCBS_CONTEXT.'_course_group':
				
				self::unserialize($data,array('schedule_week_day','schedule_date'));
				
				$CourseGroup=new BGCBSCourseGroup();
				$CourseGroup->setPostMetaDefault($data);
				
			break;
		
			case PLUGIN_BGCBS_CONTEXT.'_price_rule':
				
				self::unserialize($data,array('booking_form_id','course_id','course_group_id','registration_date'));
				
				$PriceRule=new BGCBSPriceRule();
				$PriceRule->setPostMetaDefault($data);
				
			break;
		
			case PLUGIN_BGCBS_CONTEXT.'_location':
				
				$Location=new BGCBSLocation();
				$Location->setPostMetaDefault($data);
   
			break;
			
			case PLUGIN_BGCBS_CONTEXT.'_payment':
				
				self::unserialize($data,array('payment_stripe_method'));
				
				$Payment=new BGCBSPayment();
				$Payment->setPostMetaDefault($data);
				
			break;
			
			case PLUGIN_BGCBS_CONTEXT.'_coupon':
				
				$Coupon=new BGCBSCoupon();
				$Coupon->setPostMetaDefault($data);
				
			break;
		
			case PLUGIN_BGCBS_CONTEXT.'_tax_rate':
				
				$TaxRate=new BGCBSTaxRate();
				$TaxRate->setPostMetaDefault($data);
				
			break;
		
			case PLUGIN_BGCBS_CONTEXT.'_email_account':
				
				$EmailAccount=new BGCBSEmailAccount();
				$EmailAccount->setPostMetaDefault($data);
				
			break;
		}
		
		return($data);
	}
	
	/**************************************************************************/
	
	static function unserialize(&$data,$unserializeIndex)
	{
		foreach($unserializeIndex as $index)
		{
			if(isset($data[$index]))
				$data[$index]=maybe_unserialize($data[$index]);
		}
	}
	
	/**************************************************************************/
	
	static function updatePostMeta($post,$name,$value)
	{
		$name=PLUGIN_BGCBS_CONTEXT.'_'.$name;
		$postId=(int)(is_object($post) ? $post->ID : $post);
		
		update_post_meta($postId,$name,$value);
	}
	
	/**************************************************************************/
	
	static function removePostMeta($post,$name)
	{
		$name=PLUGIN_BGCBS_CONTEXT.'_'.$name;
		$postId=(int)(is_object($post) ? $post->ID : $post);
		
		delete_post_meta($postId,$name);
	}
		
	/**************************************************************************/
	
	static function createArray(&$array,$index)
	{
		$array=array($index=>array());
		return($array);
	}

	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/