<?php

/******************************************************************************/
/******************************************************************************/

class BGCBSFixerIo
{
	/**************************************************************************/
	
	function __construct()
	{
		
	}
	
	/**************************************************************************/
	
	function getRate()
	{	
		$LogManager=new BGCBSLogManager();
		
		$url='http://data.fixer.io/api/latest?access_key='.BGCBSOption::getOption('fixer_io_api_key').'&base='.BGCBSCurrency::getBaseCurrency();
		
		if(($content=file_get_contents($url))===false)
		{
			$LogManager->add('fixerio',1,$content);	
			return(false);
		}
		
		$data=json_decode($content);
		
		if($data->{'success'})
		{
			return($data->{'rates'});
		}
		
		$LogManager->add('fixerio',1,print_r($data,true));	
		
		return(false);
	}

	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/