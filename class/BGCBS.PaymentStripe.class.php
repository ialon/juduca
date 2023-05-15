<?php

/******************************************************************************/
/******************************************************************************/

class BGCBSPaymentStripe
{
	/**************************************************************************/
	
	function __construct()
	{
		$this->paymentMethod=array
		(
			'alipay'=>array(esc_html__('Alipay','bookingo')),
			'card'=>array(esc_html__('Cards','bookingo')),			
			'ideal'=>array(esc_html__('iDEAL','bookingo')),
			'fpx'=>array(esc_html__('FPX','bookingo')),
			'bacs_debit'=>array(esc_html__('Bacs Direct Debit','bookingo')),
			'bancontact'=>array(esc_html__('Bancontact','bookingo')),
			'giropay'=>array(esc_html__('Giropay','bookingo')),
			'p24'=>array(esc_html__('Przelewy24','bookingo')),
			'eps'=>array(esc_html__('EPS','bookingo')),
			'sofort'=>array(esc_html__('Sofort','bookingo')),
			'sepa_debit'=>array(esc_html__('SEPA Direct Debit','bookingo'))
		);
		
		$this->event=array
		(
			'payment_intent.canceled',
			'payment_intent.created',
			'payment_intent.payment_failed',
			'payment_intent.processing',
			'payment_intent.requires_action',
			'payment_intent.succeeded',
			'payment_method.attached'
		);
		
		asort($this->paymentMethod);
	}
	
	/**************************************************************************/
	
	function getPaymentMethod()
	{
		return($this->paymentMethod);
	}
	
	/**************************************************************************/
	
	function isPaymentMethod($paymentMethod)
	{
		return(array_key_exists($paymentMethod,$this->paymentMethod) ? true : false);
	}
	
	/**************************************************************************/
	
	function getWebhookEndpointUrlAdress()
	{
		$address=add_query_arg('action','payment_stripe',home_url().'/');
		return($address);
	}
	
	/**************************************************************************/
	
	function createWebhookEndpoint($payment)
	{
		$StripeClient=new \Stripe\StripeClient($payment['meta']['payment_stripe_api_key_secret']);
		
		$webhookEndpoint=$StripeClient->webhookEndpoints->create(['url'=>$this->getWebhookEndpointUrlAdress(),'enabled_events'=>$this->event]);		
		
		BGCBSOption::updateOption(array('payment_stripe_webhook_endpoint_id'=>$webhookEndpoint->id));
	}
	
	/**************************************************************************/
	
	function updateWebhookEndpoint($payment,$webhookEndpointId)
	{
		$StripeClient=new \Stripe\StripeClient($payment['meta']['payment_stripe_api_key_secret']);
		$StripeClient->webhookEndpoints->update($webhookEndpointId,['url'=>$this->getWebhookEndpointUrlAdress()]);
	}
	
	/**************************************************************************/
	
	function createSession($booking,$bookingForm)
	{
        try
		{
            $Validation=new BGCBSValidation();

            $paymentId=$booking['meta']['payment_id'];

            $payment=$bookingForm['dictionary']['payment'][$paymentId];

            /***/

            Stripe\Stripe::setApiKey($payment['meta']['payment_stripe_api_key_secret']);

            /***/

            $webhookEndpointId=BGCBSOption::getOption('payment_stripe_webhook_endpoint_id');
            if($Validation->isEmpty($webhookEndpointId)) $this->createWebhookEndpoint($payment);
            else
            {
                try
                {
                    $this->updateWebhookEndpoint($payment,$webhookEndpointId);
                } 
                catch (Exception $ex) 
                {
                    $this->createWebhookEndpoint($payment);
                }
            }

            /***/

            $productId=$payment['meta']['payment_stripe_product_id'];

            if($Validation->isEmpty($productId))
            {
                $product=\Stripe\Product::create(
                [
                    'name'=>esc_html__('Bookingo','bookingo')
                ]);		

                $productId=$product->id;

                BGCBSPostMeta::updatePostMeta($paymentId,'payment_stripe_product_id',$productId);
            }

            /***/

            $price=\Stripe\Price::create(
            [
                'product'=>$productId,
                'unit_amount'=>$booking['billing']['value_gross']*100,
                'currency'=>$booking['meta']['currency']
            ]);

            /***/

            $currentURLAddress=home_url();
            if($Validation->isEmpty($payment['meta']['payment_stripe_success_url_address']))
                $payment['meta']['payment_stripe_success_url_address']=$currentURLAddress;
            if($Validation->isEmpty($payment['meta']['payment_stripe_cancel_url_address']))
                $payment['meta']['payment_stripe_cancel_url_address']=$currentURLAddress;

            $session=\Stripe\Checkout\Session::create
            (
                [
                    'payment_method_types'=>$payment['meta']['payment_stripe_method'],
                    'mode'=>'payment',
                    'line_items'=>
                    [
                        [
                            'price'=>$price->id,
                            'quantity'=>1
                        ]
                    ],
                    'success_url'=>$payment['meta']['payment_stripe_success_url_address'],
                    'cancel_url'=>$payment['meta']['payment_stripe_cancel_url_address']
                ]		
            );

            BGCBSPostMeta::updatePostMeta($booking['post']->ID,'payment_stripe_intent_id',$session->payment_intent);

            return($session->id);
        }
  		catch(Exception $ex) 
		{
			$LogManager=new BGCBSLogManager();
			$LogManager->add('stripe',1,$ex->__toString());	
			return(false);
		}
	}
	
	/**************************************************************************/
	
	function receivePayment()
	{
		$LogManager=new BGCBSLogManager();
		
		if(!array_key_exists('action',$_REQUEST)) return(false);
		
		if($_REQUEST['action']=='payment_stripe')
		{
			$LogManager->add('stripe',2,__('[1] Receiving a payment.','bookingo'));	
			
			global $post;
			
			$event=null;
			$content=@file_get_contents('php://input');
	
			try 
			{
				$event=\Stripe\Event::constructFrom(json_decode($content,true));
			} 
			catch(\UnexpectedValueException $e) 
			{
				$LogManager->add('stripe',2,__('[2] Error during parsing data in JSON format.','bookingo'));	
				http_response_code(400);
				exit();
			}
			
			if(in_array($event->type,$this->event))
			{
				$LogManager->add('stripe',2,__('[4] Checking a booking.','bookingo'));	
				
				$Booking=new BGCBSBooking();
				$BookingStatus=new BGCBSBookingStatus();
				
				$argument=array
				(
					'post_type'=>BGCBSBooking::getCPTName(),
					'posts_per_page'=>-1,
					'meta_query'=>array
					(
						array
						(
							'key'=>PLUGIN_BGCBS_CONTEXT.'_payment_stripe_intent_id',
							'value'=>$event->data->object->id
						)					  
					)
				);
				
				BGCBSHelper::preservePost($post,$bPost);
				
				$query=new WP_Query($argument);
				if($query!==false) 
				{
					if($query->found_posts)
					{
						$LogManager->add('stripe',2,sprintf(__('[6] Booking %s is found.','bookingo'),$event->data->object->id));	
					
						while($query->have_posts())
						{
							$query->the_post();

							$meta=BGCBSPostMeta::getPostMeta($post);

							if(!array_key_exists('payment_stripe_data',$meta)) $meta['payment_stripe_data']=array();

							$meta['payment_stripe_data'][]=$event;

							BGCBSPostMeta::updatePostMeta($post->ID,'payment_stripe_data',$meta['payment_stripe_data']);
							
							$LogManager->add('stripe',2,__('[7] Updating a booking about transaction details.','bookingo'));

							if($event->type=='payment_intent.succeeded')
							{
								if(BGCBSOption::getOption('booking_status_payment_success')!=-1)
								{
									$oldBookingStatusId=$meta['booking_status_id'];
									$newBookingStatusId=BGCBSOption::getOption('booking_status_payment_success');

									if($BookingStatus->isBookingStatus($newBookingStatusId))
									{
										$LogManager->add('stripe',2,__('[11] Updating booking status.','bookingo'));
										
										if($oldBookingStatusId!==$newBookingStatusId)
										{
											BGCBSPostMeta::updatePostMeta($post->ID,'booking_status_id',$newBookingStatusId);

											if((int)BGCBSOption::getOption('booking_status_synchronization')===3)
											{
												$WooCommerce=new BGCBSWooCommerce();
												$WooCommerce->changeStatus(-1,$post->ID);
											}

											$bookingStatus=$BookingStatus->getBookingStatus($newBookingStatusId);

											$recipient=array();
											$recipient[0]=array($meta['applicant_email_address']);

											$subject=sprintf(__('Booking "%s" has changed status to "%s"','bookingo'),$post->post_title,$bookingStatus[0]);

											global $bgcbs_logEvent;

											$bgcbs_logEvent=4;
											$Booking->sendEmail($post->ID,BGCBSOption::getOption('sender_default_email_account_id'),'booking_change_status',$recipient[0],$subject);		   
										}
									}
								}
								else
								{
									$LogManager->add('stripe',2,__('[9] Changing status of the booking after successful payment is off.','bookingo'));	
								}
							}
							else
							{
								$LogManager->add('stripe',2,sprintf(__('[8] Event %s is not supported.','bookingo'),$event->type));	
							}

							break;
						}
					}
					else
					{
						$LogManager->add('stripe',2,sprintf(__('[5] Booking %s is not found.','bookingo'),$event->data->object->id));	
					}
				}
			
				BGCBSHelper::preservePost($post,$bPost,0);
			}
			else 
			{
				$LogManager->add('stripe',2,sprintf(__('[3] Event %s is not supported.','bookingo'),$event->type));	
			}
			
			http_response_code(200);
			exit();
		}
	}
	
	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/