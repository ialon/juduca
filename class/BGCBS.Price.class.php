<?php

/******************************************************************************/
/******************************************************************************/

class BGCBSPrice
{
	/**************************************************************************/
	
	static function format($value,$currencyIndex)
	{
		$Currency=new BGCBSCurrency();
		$currency=$Currency->getCurrency($currencyIndex);
		
		$value=self::numberFormat($value,$currencyIndex);
		
		if($currency['position']=='left') 
			$value=$currency['symbol'].($currency['space'] ? ' ' : '').$value;
		else $value.=($currency['space'] ? ' ' : '').$currency['symbol'];
		
		return($value);
	}
	
	/**************************************************************************/
	
	static function formatToSave($value,$empty=false)
	{
		$Validation=new BGCBSValidation();
		
		if(($Validation->isEmpty($value)) && ($empty)) return('');
		
		$value=preg_replace('/,/','.',$value);
		$value=number_format($value,2,'.','');
		return($value);
	}
	
	/**************************************************************************/
	
	static function numberFormat($value,$currencyIndex)
	{
		$Currency=new BGCBSCurrency();
		$Validation=new BGCBSValidation();
		
		$currency=$Currency->getCurrency($currencyIndex);
	
		if($Validation->isEmpty($value)) $value=0.00;
		
		$value=number_format($value,2,$currency['separator'],$currency['separator2']);
		return($value);
	}
	
	/**************************************************************************/
	
	static function calculateGross($value,$taxRateId=0,$taxValue=0)
	{
		if($taxRateId!=0)
		{
			$TaxRate=new BGCBSTaxRate();
			$dictionary=$TaxRate->getDictionary();
			
			if(array_key_exists($taxRateId,$dictionary))
				$taxValue=$dictionary[$taxRateId]['meta']['tax_rate_value'];
		}
		
		$value*=(1+($taxValue/100));
		
		return($value);
	}

	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/