<?php

/******************************************************************************/
/******************************************************************************/

class BGCBSWooCommerce
{
	/**************************************************************************/
	
	function __construct()
	{
		
	}
	
	/**************************************************************************/
	
	function isEnable($meta)
	{
		return((class_exists('WooCommerce')) && ($meta['woocommerce_enable']));
	}
	
	/**************************************************************************/
	
	function isPayment($paymentId,$dictionary=null)
	{
		if(is_null($dictionary)) $dictionary=$this->getPaymentDictionary();

		foreach($dictionary as $value)
		{
			if($value->{'id'}==$paymentId) return(true);
		}
		
		return(false);
	}
	
	/**************************************************************************/
	
	function getPaymentDictionary()
	{
		$dictionary=WC()->payment_gateways->payment_gateways();
	
		foreach($dictionary as $index=>$value)
		{
			if(!isset($value->enabled)) unset($dictionary[$index]);
			if($value->enabled!='yes') unset($dictionary[$index]);
		}
		
		return($dictionary);
	}
	
	/**************************************************************************/
	
	function getPaymentName($paymentId,$dictionary=null)
	{
		if(is_null($dictionary)) $dictionary=$this->getPaymentDictionary();
		
		foreach($dictionary as $value)
		{
			if($value->{'id'}==$paymentId) return($value->{'method_title'});
		}		
		
		return(null);
	}
	
	/**************************************************************************/
	
	function sendBooking($bookingId)
	{
		global $wpdb;
		
		$User=new BGCBSUser();
		$Booking=new BGCBSBooking();
		$Validation=new BGCBSValidation();
		$BookingStatus=new BGCBSBookingStatus();
		
		if(($booking=$Booking->getBooking($bookingId))===false) return(false);		
		
		$userId=0;
		if($User->isSignIn())
		{
			$userData=$User->getCurrentUserData();
			$userId=$userData->data->ID;
		}
		
		BGCBSHelper::removeUIndex
		(	   
			$booking['meta'],
			'applicant_first_name',
			'applicant_second_name',
			'applicant_email_address',
			'participant_first_name',
			'participant_second_name'
		);
		
		if(($Validation->isNotEmpty($booking['meta']['applicant_first_name'])) && ($Validation->isNotEmpty($booking['meta']['applicant_second_name'])) && ($Validation->isNotEmpty($booking['meta']['applicant_email_address'])))
		{
			$address=array
			(
				'first_name'=>$booking['meta']['applicant_first_name'],
				'last_name'=>$booking['meta']['applicant_second_name'],
				'email'=>$booking['meta']['applicant_email_address']			
			);
		}
		else
		{
			$address=array
			(
				'first_name'=>$booking['meta']['participant_first_name'],
				'last_name'=>$booking['meta']['participant_second_name'],
			);			
		}
				
		$order=wc_create_order();
		$order->set_address($address,'billing');
		$order->set_address($address,'shipping');
		$order->set_payment_method($booking['meta']['payment_id']);
		
		/***/
		
		BGCBSPostMeta::updatePostMeta($order->get_id(),'booking_id',$bookingId);
		BGCBSPostMeta::updatePostMeta($bookingId,'woocommerce_booking_id',$order->get_id());
		
		/***/
		
		if($userId>0) 
		{
			update_post_meta($order->get_id(),'_customer_user',$userId);
		
			foreach($address as $index=>$value) 
			{
				update_user_meta($userId,'billing_'.$index,$value);
				update_user_meta($userId,'shipping_'.$index,$value);
			}		  
		}
			
		/***/
		
		if($booking['billing']['value_net']==0.00)
		{
			$status=$BookingStatus->mapBookingStatus(BGCBSOption::getOption('booking_status_payment_success'));
			if($status!==false)
			{
				$order=new WC_Order($order->get_id());
				$order->update_status($status);
			}
		}
		
		$this->changeStatus($order->get_id(),$bookingId);
		
		/***/

		$product=$this->prepareProduct
		(
			array
			(
				'post'=>array
				(
					'post_title'=>sprintf(esc_html__('Payment for course %s','bookingo'),$booking['meta']['course_name']),
				),
				'meta'=>array
				(
					'bgcbs_price_gross'=>$booking['billing']['value_gross'],
					'bgcbs_tax_value'=>$booking['billing']['tax_rate_value'],
					'_regular_price'=>$booking['billing']['value_net'],
					'_sale_price'=>$booking['billing']['value_net'],
					'_price'=>$booking['billing']['value_net']
				)
			)
		);
				
		$productId=$this->createProduct($product);
		$order->add_product(wc_get_product($productId));
			
		$order->calculate_totals();
				
		/***/
		
		$query=$wpdb->prepare('delete from '.$wpdb->prefix.'woocommerce_order_items where order_id=%d and order_item_type=%s',$order->get_id(),'tax');
		$wpdb->query($query);
			
		/***/
			
		$taxRateId=1;
		$orderItem=$order->get_items();
				
		/***/
			
		$taxArray=array();
		foreach($orderItem as $item)
		{
			$priceNet=get_post_meta($item->get_product_id(),'_price',true);
			$priceGross=get_post_meta($item->get_product_id(),'bgcbs_price_gross',true);
			$taxValue=get_post_meta($item->get_product_id(),'bgcbs_tax_value',true);
			$taxAmount=round($priceGross-$priceNet,2);
			$taxLabel=sprintf(esc_html__('Tax %.2f','bookingo'),$taxValue);

			if(!isset($taxArray[$taxValue]))
			{
				$query=$wpdb->prepare('insert into '.$wpdb->prefix.'woocommerce_order_items(order_item_name,order_item_type,order_id) VALUES (%s,%s,%d)',array($taxLabel,'tax',$order->get_id()));
				$wpdb->query($query);

				$taxItemId=$wpdb->insert_id;

				$taxArray[$taxValue]=array
				(
					'taxItemId'=>$taxItemId,
					'rate_id'=>$taxRateId,
					'label'=>$taxLabel,
					'compound'=>'',
					'tax_amount'=>$taxAmount,
					'shipping_tax_amount'=>0,
				);

				wc_add_order_item_meta($taxArray[$taxValue]['taxItemId'],'rate_id',$taxArray[$taxValue]['rate_id']);
				wc_add_order_item_meta($taxArray[$taxValue]['taxItemId'],'label',$taxArray[$taxValue]['label']);
				wc_add_order_item_meta($taxArray[$taxValue]['taxItemId'],'compound',$taxArray[$taxValue]['compound']);
				wc_add_order_item_meta($taxArray[$taxValue]['taxItemId'],'tax_amount',$taxArray[$taxValue]['tax_amount']);
				wc_add_order_item_meta($taxArray[$taxValue]['taxItemId'],'shipping_tax_amount',$taxArray[$taxValue]['shipping_tax_amount']);
			}
			else
			{
				$taxArray[$taxValue]['tax_amount']+=$taxAmount;
				wc_update_order_item_meta($taxArray[$taxValue]['taxItemId'],'tax_amount',$taxArray[$taxValue]['tax_amount']);		
			}

			$taxData=array
			(
				'total'=>array
				(
					$taxArray[$taxValue]['rate_id']=>(string)$taxAmount,
				),
				'subtotal'=>array
				(
					$taxArray[$taxValue]['rate_id']=>(string)$taxAmount,
				)
			);

			wc_update_order_item_meta($item->get_id(),'_line_subtotal_tax',$taxAmount);
			wc_update_order_item_meta($item->get_id(),'_line_tax',$taxAmount);
			wc_update_order_item_meta($item->get_id(),'_line_tax_data',$taxData);

			$taxRateId++;
		}
					
		update_post_meta($order->get_id(),'_order_tax',$booking['billing']['value_gross']-$booking['billing']['value_net']);
		update_post_meta($order->get_id(),'_order_total',$booking['billing']['value_gross']);
					
		wp_delete_post($productId);
		
		return($order->get_checkout_payment_url());
	}
	
	/**************************************************************************/
	
	function prepareProduct($data)
	{
 		$argument=array
		(
			'post'=>array
			(
				'post_title'=>'',
				'post_content'=>'',
				'post_status'=>'publish',
				'post_type'=>'product',
			),
			'meta'=>array
			(
				'bgcbs_price_gross'=>0,
				'bgcbs_tax_value'=>0,
				'_visibility'=>'visible',
				'_stock_status'=>'instock',
				'_downloadable'=>'no',
				'_virtual'=>'yes',
				'_regular_price'=>0,
				'_sale_price'=>0,
				'_purchase_note'=>'',
				'_featured'=>'no',
				'_weight'=>'',
				'_length'=>'',
				'_width'=>'',
				'_height'=>'',
				'_sku'=>'',
				'_product_attributes'=>array(),
				'_sale_price_dates_from'=>'',
				'_sale_price_dates_to'=>'',
				'_price'=>0,
				'_sold_individually'=>'',
				'_manage_stock'=>'no',
				'_backorders'=>'no',
				'_stock'=>'',
				'total_sales'=>'0',
			),
		);
		
		if(isset($data['post']))
		{
			foreach($data['post'] as $index=>$value)
				$argument['post'][$index]=$value;
		}
		
		if(isset($data['meta']))
		{
			foreach($data['meta'] as $index=>$value)
				$argument['meta'][$index]=$value;
		}		
		
		return($argument);	   
	}
	
	/**************************************************************************/
	
	function createProduct($data)
	{
		$productId=wp_insert_post($data['post']);
		wp_set_object_terms($productId,'simple','product_type');
		foreach($data['meta'] as $key=>$value)
			update_post_meta($productId,$key,$value);
		return($productId);
	}
	
	/**************************************************************************/
	
	function locateTemplate($template,$templateName,$templatePath) 
	{
		global $woocommerce;
	   
		$templateTemp=$template;
		if(!$templatePath) $templatePath=$woocommerce->template_url;
 
		$pluginPath=PLUGIN_BGCBS_PATH.'woocommerce/';
	 
		$template=locate_template(array($templatePath.$templateName,$templateName));
 
		if((!$template) && (file_exists($pluginPath.$templateName)))
			$template=$pluginPath.$templateName;
 
		if(!$template) $template=$templateTemp;
   
		return($template);
	}
	
	/**************************************************************************/
	
	function getUserData()
	{
		$userData=array();
		$userCurrent=wp_get_current_user();
		
		$Country=new WC_Countries();
		$Customer=new WC_Customer($userCurrent->ID);
		
		$billingAddress=$Customer->get_billing();
		
		$userData['client_contact_detail_first_name']=$userCurrent->user_firstname;
		$userData['client_contact_detail_last_name']=$userCurrent->user_lastname;
		$userData['client_contact_detail_email_address']=$userCurrent->user_email;
		$userData['client_contact_detail_phone_number']=null;
		
		$userData['client_billing_detail_enable']=1;
		$userData['client_billing_detail_company_name']=$billingAddress['company'];
		$userData['client_billing_detail_tax_number']=null;
		$userData['client_billing_detail_street_name']=$billingAddress['address_1'];
		$userData['client_billing_detail_street_number']=$billingAddress['address_2'];
		$userData['client_billing_detail_city']=$billingAddress['city'];
		$userData['client_billing_detail_state']=null;
		$userData['client_billing_detail_postal_code']=$billingAddress['postcode'];
		$userData['client_billing_detail_country_code']=$billingAddress['country'];
		
		$state=$billingAddress['state'];
		$country=$billingAddress['country'];
		
		$countryState=$Country->get_states();
		
		if((isset($countryState[$country])) && (isset($countryState[$country][$state])))
			$userData['client_billing_detail_state']=$countryState[$country][$state];
		else $userData['client_billing_detail_state']=$billingAddress['state']; 
			
		return($userData);
	}
	
	/**************************************************************************/
	
	function addAction()
	{
		add_action('woocommerce_order_status_changed',array($this,'changeStatus'));
		add_action('woocommerce_email_customer_details',array($this,'createOrderEmailMessageBody'));
				
		add_action('add_meta_boxes',array($this,'addMetaBox'));
	}
	
	/**************************************************************************/
	
	function addMetaBox()
	{
		global $post;
	
		if($post->post_type=='shop_order')
		{
			$meta=BGCBSPostMeta::getPostMeta($post);
			
			if((is_array($meta)) && (array_key_exists('booking_id',$meta)) && ($meta['booking_id']>0))
			{
				add_meta_box(PLUGIN_BGCBS_CONTEXT.'_meta_box_woocommerce_product',esc_html__('Booking','bookingo'),array($this,'addMetaBoxWooCommerceBooking'),'shop_order','side','low');		
			}
		}
	}
	
	/**************************************************************************/
	
	function addMetaBoxWooCommerceBooking()
	{
		global $post;
		
		$meta=BGCBSPostMeta::getPostMeta($post);
		
		echo 
		'
			<div>
				<div>'.esc_html__('This order has corresponding booking from "Bookingo" plugin. Click on button below to see its details in new window.','bookingo').'</div>
				<br/>
				<a class="button button-primary" href="'.get_edit_post_link($meta['booking_id']).'" target="_blank">'.esc_html__('Open booking','bookingo').'</a>
			</div>
		';
	}
	
	/**************************************************************************/
	
	function changeStatus($orderId=-1,$bookingId=-1)
	{
		$Booking=new BGCBSBooking();
		
		$bookingStatusSynchronizationId=(int)BGCBSOption::getOption('booking_status_synchronization');
		
		if($bookingStatusSynchronizationId===1) return(false);
			
		/***/
		
		$BookingStatus=new BGCBSBookingStatus();
		
		if((int)$orderId!==-1)
		{
			$orderMeta=BGCBSPostMeta::getPostMeta($orderId);		
			if((array_key_exists('booking_id',$orderMeta)) && ($orderMeta['booking_id']>0))
				$bookingId=(int)$orderMeta['booking_id'];
		}
		elseif((int)$bookingId!==-1)
		{
			if(($booking=$Booking->getBooking($bookingId))!==false) 		
			{
				if((array_key_exists('woocommerce_booking_id',$booking['meta'])) && ($booking['meta']['woocommerce_booking_id']>0))
					$orderId=$booking['meta']['woocommerce_booking_id'];
			}
		}
		
		/***/
		
		if((int)$bookingStatusSynchronizationId===2)
		{
			if($bookingId!=-1)
			{
				$order=new WC_Order($orderId);
				
				$status=$BookingStatus->mapBookingStatus($order->get_status());
				
				if($status!==false) 
				{	
					$bookingOld=$Booking->getBooking($bookingId);
					
					BGCBSPostMeta::updatePostMeta($bookingId,'booking_status_id',$status);
					
					$bookingNew=$Booking->getBooking($bookingId);
					
					$Booking->sendEmailBookingChangeStatus($bookingOld,$bookingNew);
				}
			}
		}
		else if((int)$bookingStatusSynchronizationId===3)
		{
			if($orderId!=-1)
			{
				$Booking=new BGCBSBooking();
				if(($booking=$Booking->getBooking($bookingId))!==false) 
				{
					$status=$BookingStatus->mapBookingStatus($booking['meta']['booking_status_id']);
					if($status!==false)
					{
						$order=new WC_Order($orderId);
						$order->update_status($status);
					}
				}
			}			
		}
	}
	
	/**************************************************************************/
	
	function createOrderEmailMessageBody($order,$sent_to_admin=false)
	{		
		if(!($order instanceof WC_Order)) return; 
		
		$Email=new BGCBSEmail();
		$Booking=new BGCBSBooking();
		
		$meta=BGCBSPostMeta::getPostMeta($order->get_id());
		
		$bookingId=(int)$meta['booking_id'];
		
		if($bookingId<=0) return;
		
		if(($booking=$Booking->getBooking($bookingId))===false) return;
		
		$data=array();
		
		$data['style']=$Email->getEmailStyle();
		
		$data['booking']=$booking;
		$data['booking']['booking_title']=$booking['post']->post_title;
		
		$data['document_header_exclude']=1;
				
		if(!$sent_to_admin)
			unset($data['booking']['booking_form_name']);
		
		$Template=new BGCBSTemplate($data,PLUGIN_BGCBS_TEMPLATE_PATH.'email_booking.php');
		$body=$Template->output();
		
		echo $body;
	}
	
	/**************************************************************************/
	
	function getPaymentURLAddress($bookingId)
	{
		$order=wc_get_order($bookingId);
		
		if($order!==false)
			return($order->get_checkout_payment_url());
		
		return(null);
	}
	
	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/