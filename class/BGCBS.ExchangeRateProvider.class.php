<?php

/******************************************************************************/
/******************************************************************************/

class BGCBSExchangeRateProvider
{
	/**************************************************************************/
	
	function __construct()
	{
		$this->provider=array
		(
			1=>array(esc_html__('Fixer.io','bookingo'))
		);
	}
	
	/**************************************************************************/
	
	function getProvider()
	{
		return($this->provider);
	}

	/**************************************************************************/
	
	function isProvider($provider)
	{
		return(array_key_exists($provider,$this->provider));
	}
	
	/**************************************************************************/
	
	function importExchangeRate()
	{	  
		$post=BGCBSHelper::getPostOption();
		$option=BGCBSOption::getOptionObject();
		
		$rate=false;
		$currency=array();
		$response=array('global'=>array('error'=>1,'reload'=>0));
				
		if((int)$post['exchange_rate_provider']===1)
		{
			$FixerIo=new BGCBSFixerIo();
			$rate=$FixerIo->getRate();
		}
		
		if($rate!==false)
		{
			$Currency=new BGCBSCurrency();

			foreach($Currency->getCurrency() as $index=>$value)
			{
				if(isset($rate->{$index}))
					$currency[$index]=number_format($rate->{$index},2,'.','');
			}
			
			if(!is_array($option['currency_exchange_rate']))
				$option['currency_exchange_rate']=array();
						
			$option['currency_exchange_rate']=array_merge($option['currency_exchange_rate'],$currency);
			
			$response['global']['error']=0;
			$response['global']['reload']=1;
			
			$subtitle=esc_html__('Exchange rates have been imported. Page will be reloaded...','bookingo');
			
			BGCBSOption::updateOption($option);
		}
		else
		{
			$response['global']['error']=1;
			$subtitle=esc_html__('Exchange rates cannot be imported.','bookingo');
		}
		
		$Notice=new BGCBSNotice();

		$response['global']['notice']=$Notice->createHTML(PLUGIN_BGCBS_TEMPLATE_PATH.'/notice.php',false,$response['global']['error'],$subtitle);
		
		echo json_encode($response);
		exit;		
	}
	
	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/