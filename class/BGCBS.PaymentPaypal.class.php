<?php

/******************************************************************************/
/******************************************************************************/

class BGCBSPaymentPaypal
{
	/**************************************************************************/
	
	function __construct()
	{

	}
	
	/**************************************************************************/
	
	function createPaymentForm($postId,$booking,$bookingForm)
	{
		$Validation=new BGCBSValidation();
		
		$paymentId=$booking['meta']['payment_id'];
		
		$payment=$bookingForm['dictionary']['payment'][$paymentId];
		
		$formURL='https://www.paypal.com/cgi-bin/webscr';
		if((int)$payment['meta']['payment_paypal_sandbox_mode_enable']===1)
			$formURL='https://www.sandbox.paypal.com/cgi-bin/webscr';
		
		$successUrl=$payment['meta']['payment_paypal_success_url_address'];
		if($Validation->isEmpty($successUrl)) $successUrl=add_query_arg('action','success',get_the_permalink($postId));
		
		$cancelUrl=$payment['meta']['payment_paypal_cancel_url_address'];
		if($Validation->isEmpty($cancelUrl)) $cancelUrl=add_query_arg('action','cancel',get_the_permalink($postId));	
		
		$html=
		'
			<form action="'.esc_url($formURL).'" method="post" name="bgcbs-form-paypal">
				<input type="hidden" name="cmd" value="_xclick">
				<input type="hidden" name="business" value="'.esc_attr($payment['meta']['payment_paypal_email_address']).'">				
				<input type="hidden" name="item_name" value="'.sprintf(esc_attr__('Payment for course %s','bookingo'),$booking['meta']['course_name']).'">
				<input type="hidden" name="item_number" value="0">
				<input type="hidden" name="amount" value="'.esc_attr($booking['billing']['value_gross']).'">	
				<input type="hidden" name="currency_code" value="'.esc_attr($booking['meta']['currency']).'">
				<input type="hidden" value="1" name="no_shipping">
				<input type="hidden" value="'.get_the_permalink($postId).'?action=ipn" name="notify_url">				
				<input type="hidden" value="'.esc_url($successUrl).'" name="return">
				<input type="hidden" value="'.esc_url($cancelUrl).'" name="cancel_return">
			</form>
		';
		
		return($html);
	}
	
	/**************************************************************************/
	
	function handleIPN()
	{
		$bookingId=(int)$_POST['item_number'];
		if($bookingId<=0) $bookingId=-1;
		
		$Payment=new BGCBSPayment();
		$Booking=new BGCBSBooking();
		$LogManager=new BGCBSLogManager();
		$BookingStatus=new BGCBSBookingStatus();
		
		$LogManager->add('paypal',2,__('[1] Receiving a payment.','bookingo'));	
		
		$booking=$Booking->getBooking($bookingId);
		if((!is_array($booking)) || (!count($booking))) 
		{
			$LogManager->add('paypal',2,sprintf(__('[2] Booking %s is not found.','bookingo'),$bookingId));	
			return;
		}
		
		$paymentId=$booking['meta']['payment_id'];
		
		if(($dictionary=$Payment->getDictionary(array('payment_id'=>$paymentId)))===false) return(false);
		if(!count($dictionary)) 
		{	
			$LogManager->add('paypal',2,sprintf(__('[3] Payment %s is not found.','bookingo'),$booking['meta']['payment_id']));	
			return;
		}
			   
		$request='cmd='.urlencode('_notify-validate');
		
		$postData=BGCBSHelper::stripslashes($_POST);
		
		foreach($postData as $key=>$value) 
			$request.='&'.$key.'='.urlencode($value);

		$address='https://ipnpb.paypal.com/cgi-bin/webscr';
		if($dictionary[$paymentId]['meta']['payment_paypal_sandbox_mode_enable']==1)
			$address='https://ipnpb.sandbox.paypal.com/cgi-bin/webscr';
		
		$LogManager->add('paypal',2,sprintf(__('[4] Sending a request: %s on address: %s.','bookingo'),$request,$address));	
		
		$ch=curl_init();
		curl_setopt($ch,CURLOPT_URL,$address);
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$request);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,1);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);
		$response=curl_exec($ch);
		
		if(curl_errno($ch)) 
		{	
			$LogManager->add('paypal',2,sprintf(__('[5] Error %s during processing cURL request.','bookingo'),curl_error($ch)));	
			return;
		}
		
		if(!strcmp($response,'VERIFIED')==0) 
		{
			$LogManager->add('paypal',2,sprintf(__('[6] Request cannot be verified: %s.','bookingo'),$response));	
			return;
		}
		
		$meta=BGCBSPostMeta::getPostMeta($bookingId);
						
		if(!((array_key_exists('payment_paypal_data',$meta)) && (is_array($meta['payment_paypal_data']))))
			$meta['payment_paypal_data']=array();
		
		$meta['payment_paypal_data'][]=$postData;
		
		BGCBSPostMeta::updatePostMeta($bookingId,'payment_paypal_data',$meta['payment_paypal_data']);
		
		$LogManager->add('paypal',2,__('[7] Updating a booking about transaction details.','bookingo'));
		
		if($postData['payment_status']=='Completed')
		{
			if(BGCBSOption::getOption('booking_status_payment_success')!=-1)
			{
				$oldBookingStatusId=$meta['booking_status_id'];
				$newBookingStatusId=BGCBSOption::getOption('booking_status_payment_success');

				if($BookingStatus->isBookingStatus($newBookingStatusId))
				{
					$LogManager->add('paypal',2,__('[11] Updating booking status.','bookingo'));
					
					if($oldBookingStatusId!==$newBookingStatusId)
					{
						BGCBSPostMeta::updatePostMeta($bookingId,'booking_status_id',$newBookingStatusId);

						if((int)BGCBSOption::getOption('booking_status_synchronization')===3)
						{
							$WooCommerce=new BGCBSWooCommerce();
							$WooCommerce->changeStatus(-1,$bookingId);
						}

						$bookingStatus=$BookingStatus->getBookingStatus($newBookingStatusId);

						$recipient=array();
						$recipient[0]=array($meta['applicant_email_address']);

						$subject=sprintf(__('Booking "%s" has changed status to "%s"','bookingo'),$booking['post']->post_title,$bookingStatus[0]);

						global $bgcbs_logEvent;

						$bgcbs_logEvent=4;
						$Booking->sendEmail($bookingId,BGCBSOption::getOption('sender_default_email_account_id'),'booking_change_status',$recipient[0],$subject);		   
					}
				}
				else
				{
					$LogManager->add('paypal',2,__('[10] Cannot find a valid booking status.','bookingo'));	
				}
			}
			else
			{
				$LogManager->add('paypal',2,__('[9] Changing status of the booking after successful payment is off.','bookingo'));
			}
		}
		else
		{
			$LogManager->add('paypal',2,sprintf(__('[8] Payment status %s is not supported.','bookingo'),$postData['payment_status']));
		}
	}
	
	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/