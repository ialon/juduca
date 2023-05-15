<?php

/******************************************************************************/
/******************************************************************************/

class BGCBSLogManager
{
	/**************************************************************************/
	
	function __construct()
	{
		$this->type=array
		(
			'mail'=>array
			(
				1=>array
				(
					'description'=>esc_html__('Sending an notification about new booking to the customer.','bookingo')
				),
				2=>array
				(
					'description'=>esc_html__('Sending an notification about new booking on defined e-mail addresses.','bookingo')
				),			   
				3=>array
				(
					'description'=>esc_html__('Sending an notification about new changes in the booking to the customer.','bookingo')
				)
			),
			'nexmo'=>array
			(
				1=>array
				(
					'description'=>esc_html__('Sending an notification about new booking on defined phone number.','bookingo')
				)
			),
			'twilio'=>array
			(
				1=>array
				(
					'description'=>esc_html__('Sending an notification about new booking on defined phone number.','bookingo')
				)				
			),
			'telegram'=>array
			(
				1=>array
				(
					'description'=>esc_html__('Sending an notification about new booking on defined phone number.','bookingo')
				)				
			),
			'stripe'=>array
			(
				1=>array
				(
					'description'=>esc_html__('Creating a payment.','bookingo')
				),
				2=>array
				(
					'description'=>esc_html__('Receiving a payment.','bookingo')
				)	
			),
			'paypal'=>array
			(
				1=>array
				(
					'description'=>esc_html__('Creating a payment.','bookingo')
				),
				2=>array
				(
					'description'=>esc_html__('Receiving a payment.','bookingo')
				)	
			),
			'fixerio'=>array
			(
				1=>array
				(
					'description'=>esc_html__('Importing an exchange rates.','bookingo')
				)	
			)
		);
	}
		
	/**************************************************************************/
	
	function add($type,$event,$message)
	{	
		$Validation=new BGCBSValidation();
		
		if($Validation->isEmpty($message)) return;
		
		$logType=$this->get($type);
		
		array_unshift($logType,array
		(
			'event'=>$event,
			'timestamp'=>strtotime('now'),
			'message'=>$message
		));
		
		if(count($logType)>9) $logType=array_slice($logType,0,10);
		
		$logFull=$this->get();
		$logFull[$type]=$logType;
		
		update_option(PLUGIN_BGCBS_OPTION_PREFIX.'_log',$logFull);
	}
	
	/**************************************************************************/
	
	function get($type=null)
	{
		$log=get_option(PLUGIN_BGCBS_OPTION_PREFIX.'_log');

		if(!is_array($log)) $log=array();
		if(is_null($type)) return($log);
		
		if(!array_key_exists($type,$log)) $log[$type]=array();
		if(!is_array($log[$type])) $log[$type]=array();
		
		return($log[$type]);
	}
	
	/**************************************************************************/
	
	function show($type)
	{
		$log=$this->get($type);
		
		if(!count($log)) return;
		
		$Validation=new BGCBSValidation();
		
		$i=0;
		$html=null;
		
		foreach($log as $value)
		{
			if($Validation->isNotEmpty($html)) $html.='<br/>';
			
			$html.=
			'
				<li>
					<div class="to-field-disabled to-field-disabled-full-width">
						['.(++$i).']['.date_i18n('d-m-Y G:i:s',$value['timestamp']).']<br/>
						<b>'.esc_html($this->type[$type][$value['event']]['description']).'</b><br/><br/>
						'.nl2br(esc_html($value['message'])).'
					</div>
				</li>
			';
		}
		
		$html='<ul>'.$html.'</ul>';
		
		return($html);
	}
	
	/**************************************************************************/

	function logWPMailError($wp_error)
	{
		global $bgcbs_logEvent;
		
		$this->add('mail',$bgcbs_logEvent,print_r($wp_error,true));
	}

	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/