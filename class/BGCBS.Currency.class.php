<?php

/******************************************************************************/
/******************************************************************************/

class BGCBSCurrency
{
	/**************************************************************************/
	
	function __construct()
	{
		$this->currency=BGCBSGlobalData::setGlobalData('currency',array($this,'init'));
	}
	
	/**************************************************************************/
	
	function init()
	{
		$currency=array
		(
			'AFN'=>array
			(
				'name'=>esc_html__('Afghan afghani','bookingo'),
				'symbol'=>'AFN'
			),
			'ALL'=>array
			(
				'name'=>esc_html__('Albanian lek','bookingo'),
				'symbol'=>'ALL'
			),
			'DZD'=>array
			(
				'name'=>esc_html__('Algerian dinar','bookingo'),
				'symbol'=>'DZD'
			),
			'AOA'=>array
			(
				'name'=>esc_html__('Angolan kwanza','bookingo'),
				'symbol'=>'AOA'
			),
			'ARS'=>array
			(
				'name'=>esc_html__('Argentine peso','bookingo'),
				'symbol'=>'ARS'
			),
			'AMD'=>array
			(
				'name'=>esc_html__('Armenian dram','bookingo'),
				'symbol'=>'AMD'
			),
			'AWG'=>array
			(
				'name'=>esc_html__('Aruban florin','bookingo'),
				'symbol'=>'AWG'
			),
			'AUD'=>array
			(
				'name'=>esc_html__('Australian dollar','bookingo'),
				'symbol'=>'&#36;',
				'separator'=>'.'
			),
			'AZN'=>array
			(
				'name'=>esc_html__('Azerbaijani manat','bookingo'),
				'symbol'=>'AZN'
			),
			'BSD'=>array
			(
				'name'=>esc_html__('Bahamian dollar','bookingo'),
				'symbol'=>'BSD'
			),
			'BHD'=>array
			(
				'name'=>esc_html__('Bahraini dinar','bookingo'),
				'symbol'=>'BHD',
				'separator'=>'&#1643;'
			),
			'BDT'=>array
			(
				'name'=>esc_html__('Bangladeshi taka','bookingo'),
				'symbol'=>'BDT'
			),
			'BBD'=>array
			(
				'name'=>esc_html__('Barbadian dollar','bookingo'),
				'symbol'=>'BBD'
			),
			'BYR'=>array
			(
				'name'=>esc_html__('Belarusian ruble','bookingo'),
				'symbol'=>'BYR'
			),
			'BZD'=>array
			(
				'name'=>esc_html__('Belize dollar','bookingo'),
				'symbol'=>'BZD'
			),
			'BTN'=>array
			(
				'name'=>esc_html__('Bhutanese ngultrum','bookingo'),
				'symbol'=>'BTN'
			),
			'BOB'=>array
			(
				'name'=>esc_html__('Bolivian boliviano','bookingo'),
				'symbol'=>'BOB'
			),
			'BAM'=>array
			(
				'name'=>esc_html__('Bosnia and Herzegovina konvertibilna marka','bookingo'),
				'symbol'=>'BAM'
			),
			'BWP'=>array
			(
				'name'=>esc_html__('Botswana pula','bookingo'),
				'symbol'=>'BWP',
				'separator'=>'.'
			),
			'BRL'=>array
			(
				'name'=>esc_html__('Brazilian real','bookingo'),
				'symbol'=>'&#82;&#36;'
			),
			'GBP'=>array
			(
				'name'=>esc_html__('British pound','bookingo'),
				'symbol'=>'&pound;',
				'position'=>'left',
				'separator'=>'.',
			),
			'BND'=>array
			(
				'name'=>esc_html__('Brunei dollar','bookingo'),
				'symbol'=>'BND',
				'separator'=>'.'
			),
			'BGN'=>array
			(
				'name'=>esc_html__('Bulgarian lev','bookingo'),
				'symbol'=>'BGN'
			),
			'BIF'=>array
			(
				'name'=>esc_html__('Burundi franc','bookingo'),
				'symbol'=>'BIF'
			),
			'KYD'=>array
			(
				'name'=>esc_html__('Cayman Islands dollar','bookingo'),
				'symbol'=>'KYD'
			),
			'KHR'=>array
			(
				'name'=>esc_html__('Cambodian riel','bookingo'),
				'symbol'=>'KHR'
			),
			'CAD'=>array
			(
				'name'=>esc_html__('Canadian dollar','bookingo'),
				'symbol'=>'CAD',
				'separator'=>'.'
			),
			'CVE'=>array
			(
				'name'=>esc_html__('Cape Verdean escudo','bookingo'),
				'symbol'=>'CVE'
			),
			'XAF'=>array
			(
				'name'=>esc_html__('Central African CFA franc','bookingo'),
				'symbol'=>'XAF'
			),
			'GQE'=>array
			(
				'name'=>esc_html__('Central African CFA franc','bookingo'),
				'symbol'=>'GQE'
			),
			'XPF'=>array
			(
				'name'=>esc_html__('CFP franc','bookingo'),
				'symbol'=>'XPF'
			),
			'CLP'=>array
			(
				'name'=>esc_html__('Chilean peso','bookingo'),
				'symbol'=>'CLP'
			),
			'CNY'=>array
			(
				'name'=>esc_html__('Chinese renminbi','bookingo'),
				'symbol'=>'&yen;'
			),
			'COP'=>array
			(
				'name'=>esc_html__('Colombian peso','bookingo'),
				'symbol'=>'COP'
			),
			'KMF'=>array
			(
				'name'=>esc_html__('Comorian franc','bookingo'),
				'symbol'=>'KMF'
			),
			'CDF'=>array
			(
				'name'=>esc_html__('Congolese franc','bookingo'),
				'symbol'=>'CDF'
			),
			'CRC'=>array
			(
				'name'=>esc_html__('Costa Rican colon','bookingo'),
				'symbol'=>'CRC'
			),
			'HRK'=>array
			(
				'name'=>esc_html__('Croatian kuna','bookingo'),
				'symbol'=>'HRK'
			),
			'CUC'=>array
			(
				'name'=>esc_html__('Cuban peso','bookingo'),
				'symbol'=>'CUC'
			),
			'CZK'=>array
			(
				'name'=>esc_html__('Czech koruna','bookingo'),
				'symbol'=>'&#75;&#269;'
			),
			'DKK'=>array
			(
				'name'=>esc_html__('Danish krone','bookingo'),
				'symbol'=>'&#107;&#114;'
			),
			'DJF'=>array
			(
				'name'=>esc_html__('Djiboutian franc','bookingo'),
				'symbol'=>'DJF'
			),
			'DOP'=>array
			(
				'name'=>esc_html__('Dominican peso','bookingo'),
				'symbol'=>'DOP',
				'separator'=>'.'
			),
			'XCD'=>array
			(
				'name'=>esc_html__('East Caribbean dollar','bookingo'),
				'symbol'=>'XCD'
			),
			'EGP'=>array
			(
				'name'=>esc_html__('Egyptian pound','bookingo'),
				'symbol'=>'EGP'
			),
			'ERN'=>array
			(
				'name'=>esc_html__('Eritrean nakfa','bookingo'),
				'symbol'=>'ERN'
			),
			'EEK'=>array
			(
				'name'=>esc_html__('Estonian kroon','bookingo'),
				'symbol'=>'EEK'
			),
			'ETB'=>array
			(
				'name'=>esc_html__('Ethiopian birr','bookingo'),
				'symbol'=>'ETB'
			),
			'EUR'=>array
			(
				'name'=>esc_html__('European euro','bookingo'),
				'symbol'=>'&euro;',
                'position'=>'right'
			),
			'FKP'=>array
			(
				'name'=>esc_html__('Falkland Islands pound','bookingo'),
				'symbol'=>'FKP'
			),
			'FJD'=>array
			(
				'name'=>esc_html__('Fijian dollar','bookingo'),
				'symbol'=>'FJD',
				'separator'=>'.'
			),
			'GMD'=>array
			(
				'name'=>esc_html__('Gambian dalasi','bookingo'),
				'symbol'=>'GMD'
			),
			'GEL'=>array
			(
				'name'=>esc_html__('Georgian lari','bookingo'),
				'symbol'=>'GEL'
			),
			'GHS'=>array
			(
				'name'=>esc_html__('Ghanaian cedi','bookingo'),
				'symbol'=>'GHS'
			),
			'GIP'=>array
			(
				'name'=>esc_html__('Gibraltar pound','bookingo'),
				'symbol'=>'GIP'
			),
			'GTQ'=>array
			(
				'name'=>esc_html__('Guatemalan quetzal','bookingo'),
				'symbol'=>'GTQ',
				'separator'=>'.'
			),
			'GNF'=>array
			(
				'name'=>esc_html__('Guinean franc','bookingo'),
				'symbol'=>'GNF'
			),
			'GYD'=>array
			(
				'name'=>esc_html__('Guyanese dollar','bookingo'),
				'symbol'=>'GYD'
			),
			'HTG'=>array
			(
				'name'=>esc_html__('Haitian gourde','bookingo'),
				'symbol'=>'HTG'
			),
			'HNL'=>array
			(
				'name'=>esc_html__('Honduran lempira','bookingo'),
				'symbol'=>'HNL',
				'separator'=>'.'
			),
			'HKD'=>array
			(
				'name'=>esc_html__('Hong Kong dollar','bookingo'),
				'symbol'=>'&#36;',
				'separator'=>'.'
			),
			'HUF'=>array
			(
				'name'=>esc_html__('Hungarian forint','bookingo'),
				'symbol'=>'&#70;&#116;'
			),
			'ISK'=>array
			(
				'name'=>esc_html__('Icelandic krona','bookingo'),
				'symbol'=>'ISK'
			),
			'INR'=>array
			(
				'name'=>esc_html__('Indian rupee','bookingo'),
				'symbol'=>'&#8377;',
				'separator'=>'.'
			),
			'IDR'=>array
			(
				'name'=>esc_html__('Indonesian rupiah','bookingo'),
				'symbol'=>'Rp',
				'position'=>'left'
			),
			'IRR'=>array
			(
				'name'=>esc_html__('Iranian rial','bookingo'),
				'symbol'=>'IRR',
				'separator'=>'&#1643;'
			),
			'IQD'=>array
			(
				'name'=>esc_html__('Iraqi dinar','bookingo'),
				'symbol'=>'IQD',
				'separator'=>'&#1643;'
			),
			'ILS'=>array
			(
				'name'=>esc_html__('Israeli new sheqel','bookingo'),
				'symbol'=>'&#8362;',
				'separator'=>'.'
			),
			'YER'=>array
			(
				'name'=>esc_html__('Yemeni rial','bookingo'),
				'symbol'=>'YER'
			),
			'JMD'=>array
			(
				'name'=>esc_html__('Jamaican dollar','bookingo'),
				'symbol'=>'JMD'
			),
			'JPY'=>array
			(
				'name'=>esc_html__('Japanese yen','bookingo'),
				'symbol'=>'&yen;',
				'separator'=>'.'
			),
			'JOD'=>array
			(
				'name'=>esc_html__('Jordanian dinar','bookingo'),
				'symbol'=>'JOD'
			),
			'KZT'=>array
			(
				'name'=>esc_html__('Kazakhstani tenge','bookingo'),
				'symbol'=>'KZT'
			),
			'KES'=>array
			(
				'name'=>esc_html__('Kenyan shilling','bookingo'),
				'symbol'=>'KES'
			),
			'KGS'=>array
			(
				'name'=>esc_html__('Kyrgyzstani som','bookingo'),
				'symbol'=>'KGS'
			),
			'KWD'=>array
			(
				'name'=>esc_html__('Kuwaiti dinar','bookingo'),
				'symbol'=>'KWD',
				'separator'=>'&#1643;'
			),
			'LAK'=>array
			(
				'name'=>esc_html__('Lao kip','bookingo'),
				'symbol'=>'LAK'
			),
			'LVL'=>array
			(
				'name'=>esc_html__('Latvian lats','bookingo'),
				'symbol'=>'LVL'
			),
			'LBP'=>array
			(
				'name'=>esc_html__('Lebanese lira','bookingo'),
				'symbol'=>'LBP'
			),
			'LSL'=>array
			(
				'name'=>esc_html__('Lesotho loti','bookingo'),
				'symbol'=>'LSL'
			),
			'LRD'=>array
			(
				'name'=>esc_html__('Liberian dollar','bookingo'),
				'symbol'=>'LRD'
			),
			'LYD'=>array
			(
				'name'=>esc_html__('Libyan dinar','bookingo'),
				'symbol'=>'LYD'
			),
			'LTL'=>array
			(
				'name'=>esc_html__('Lithuanian litas','bookingo'),
				'symbol'=>'LTL'
			),
			'MOP'=>array
			(
				'name'=>esc_html__('Macanese pataca','bookingo'),
				'symbol'=>'MOP'
			),
			'MKD'=>array
			(
				'name'=>esc_html__('Macedonian denar','bookingo'),
				'symbol'=>'MKD'
			),
			'MGA'=>array
			(
				'name'=>esc_html__('Malagasy ariary','bookingo'),
				'symbol'=>'MGA'
			),
			'MYR'=>array
			(
				'name'=>esc_html__('Malaysian ringgit','bookingo'),
				'symbol'=>'&#82;&#77;',
				'separator'=>'.'
			),
			'MWK'=>array
			(
				'name'=>esc_html__('Malawian kwacha','bookingo'),
				'symbol'=>'MWK'
			),
			'MVR'=>array
			(
				'name'=>esc_html__('Maldivian rufiyaa','bookingo'),
				'symbol'=>'MVR'
			),
			'MRO'=>array
			(
				'name'=>esc_html__('Mauritanian ouguiya','bookingo'),
				'symbol'=>'MRO'
			),
			'MUR'=>array
			(
				'name'=>esc_html__('Mauritian rupee','bookingo'),
				'symbol'=>'MUR'
			),
			'MXN'=>array
			(
				'name'=>esc_html__('Mexican peso','bookingo'),
				'symbol'=>'&#36;',
				'separator'=>'.'
			),
			'MMK'=>array
			(
				'name'=>esc_html__('Myanma kyat','bookingo'),
				'symbol'=>'MMK'
			),
			'MDL'=>array
			(
				'name'=>esc_html__('Moldovan leu','bookingo'),
				'symbol'=>'MDL'
			),
			'MNT'=>array
			(
				'name'=>esc_html__('Mongolian tugrik','bookingo'),
				'symbol'=>'MNT'
			),
			'MAD'=>array
			(
				'name'=>esc_html__('Moroccan dirham','bookingo'),
				'symbol'=>'MAD',
				'position'=>'right'
			),
			'MZM'=>array
			(
				'name'=>esc_html__('Mozambican metical','bookingo'),
				'symbol'=>'MZM'
			),
			'NAD'=>array
			(
				'name'=>esc_html__('Namibian dollar','bookingo'),
				'symbol'=>'NAD'
			),
			'NPR'=>array
			(
				'name'=>esc_html__('Nepalese rupee','bookingo'),
				'symbol'=>'NPR'
			),
			'ANG'=>array
			(
				'name'=>esc_html__('Netherlands Antillean gulden','bookingo'),
				'symbol'=>'ANG'
			),
			'TWD'=>array
			(
				'name'=>esc_html__('New Taiwan dollar','bookingo'),
				'symbol'=>'&#78;&#84;&#36;',
				'separator'=>'.'
			),
			'NZD'=>array
			(
				'name'=>esc_html__('New Zealand dollar','bookingo'),
				'symbol'=>'&#36;',
				'separator'=>'.'
			),
			'NIO'=>array
			(
				'name'=>esc_html__('Nicaraguan cordoba','bookingo'),
				'symbol'=>'NIO',
				'separator'=>'.'
			),
			'NGN'=>array
			(
				'name'=>esc_html__('Nigerian naira','bookingo'),
				'symbol'=>'NGN',
				'separator'=>'.'
			),
			'KPW'=>array
			(
				'name'=>esc_html__('North Korean won','bookingo'),
				'symbol'=>'KPW',
				'separator'=>'.'
			),
			'NOK'=>array
			(
				'name'=>esc_html__('Norwegian krone','bookingo'),
				'symbol'=>'&#107;&#114;'
			),
			'OMR'=>array
			(
				'name'=>esc_html__('Omani rial','bookingo'),
				'symbol'=>'OMR',
				'separator'=>'&#1643;'
			),
			'TOP'=>array
			(
				'name'=>esc_html__('Paanga','bookingo'),
				'symbol'=>'TOP'
			),
			'PKR'=>array
			(
				'name'=>esc_html__('Pakistani rupee','bookingo'),
				'symbol'=>'PKR',
				'separator'=>'.'
			),
			'PAB'=>array
			(
				'name'=>esc_html__('Panamanian balboa','bookingo'),
				'symbol'=>'PAB',
				'separator'=>'.'
			),
			'PGK'=>array
			(
				'name'=>esc_html__('Papua New Guinean kina','bookingo'),
				'symbol'=>'PGK'
			),
			'PYG'=>array
			(
				'name'=>esc_html__('Paraguayan guarani','bookingo'),
				'symbol'=>'PYG'
			),
			'PEN'=>array
			(
				'name'=>esc_html__('Peruvian nuevo sol','bookingo'),
				'symbol'=>'PEN'
			),
			'PHP'=>array
			(
				'name'=>esc_html__('Philippine peso','bookingo'),
				'symbol'=>'&#8369;'
			),
			'PLN'=>array
			(
				'name'=>esc_html__('Polish zloty','bookingo'),
				'symbol'=>'&#122;&#322;',
				'position'=>'right',
				'space'=>true
			),
			'QAR'=>array
			(
				'name'=>esc_html__('Qatari riyal','bookingo'),
				'symbol'=>'QAR',
				'separator'=>'&#1643;'
			),
			'RON'=>array
			(
				'name'=>esc_html__('Romanian leu','bookingo'),
				'symbol'=>'lei'
			),
			'RUB'=>array
			(
				'name'=>esc_html__('Russian ruble','bookingo'),
				'symbol'=>'RUB'
			),
			'RWF'=>array
			(
				'name'=>esc_html__('Rwandan franc','bookingo'),
				'symbol'=>'RWF'
			),
			'SHP'=>array
			(
				'name'=>esc_html__('Saint Helena pound','bookingo'),
				'symbol'=>'SHP'
			),
			'WST'=>array
			(
				'name'=>esc_html__('Samoan tala','bookingo'),
				'symbol'=>'WST'
			),
			'STD'=>array
			(
				'name'=>esc_html__('Sao Tome and Principe dobra','bookingo'),
				'symbol'=>'STD'
			),
			'SAR'=>array
			(
				'name'=>esc_html__('Saudi riyal','bookingo'),
				'symbol'=>'SAR',
				'separator'=>'&#1643;'
			),
			'SCR'=>array
			(
				'name'=>esc_html__('Seychellois rupee','bookingo'),
				'symbol'=>'SCR'
			),
			'RSD'=>array
			(
				'name'=>esc_html__('Serbian dinar','bookingo'),
				'symbol'=>'RSD'
			),
			'SLL'=>array
			(
				'name'=>esc_html__('Sierra Leonean leone','bookingo'),
				'symbol'=>'SLL'
			),
			'SGD'=>array
			(
				'name'=>esc_html__('Singapore dollar','bookingo'),
				'symbol'=>'&#36;',
				'separator'=>'.'
			),
			'SYP'=>array
			(
				'name'=>esc_html__('Syrian pound','bookingo'),
				'symbol'=>'SYP',
				'separator'=>'&#1643;'
			),
			'SKK'=>array
			(
				'name'=>esc_html__('Slovak koruna','bookingo'),
				'symbol'=>'SKK'
			),
			'SBD'=>array
			(
				'name'=>esc_html__('Solomon Islands dollar','bookingo'),
				'symbol'=>'SBD'
			),
			'SOS'=>array
			(
				'name'=>esc_html__('Somali shilling','bookingo'),
				'symbol'=>'SOS'
			),
			'ZAR'=>array
			(
				'name'=>esc_html__('South African rand','bookingo'),
				'symbol'=>'&#82;'
			),
			'KRW'=>array
			(
				'name'=>esc_html__('South Korean won','bookingo'),
				'symbol'=>'&#8361;',
				'separator'=>'.'
			),
			'XDR'=>array
			(
				'name'=>esc_html__('Special Drawing Rights','bookingo'),
				'symbol'=>'XDR'
			),
			'LKR'=>array
			(
				'name'=>esc_html__('Sri Lankan rupee','bookingo'),
				'symbol'=>'LKR',
				'separator'=>'.'
			),
			'SDG'=>array
			(
				'name'=>esc_html__('Sudanese pound','bookingo'),
				'symbol'=>'SDG'
			),
			'SRD'=>array
			(
				'name'=>esc_html__('Surinamese dollar','bookingo'),
				'symbol'=>'SRD'
			),
			'SZL'=>array
			(
				'name'=>esc_html__('Swazi lilangeni','bookingo'),
				'symbol'=>'SZL'
			),
			'SEK'=>array
			(
				'name'=>esc_html__('Swedish krona','bookingo'),
				'symbol'=>'&#107;&#114;'
			),
			'CHF'=>array
			(
				'name'=>esc_html__('Swiss franc','bookingo'),
				'symbol'=>'&#67;&#72;&#70;',
				'separator'=>'.'
			),
			'TJS'=>array
			(
				'name'=>esc_html__('Tajikistani somoni','bookingo'),
				'symbol'=>'TJS'
			),
			'TZS'=>array
			(
				'name'=>esc_html__('Tanzanian shilling','bookingo'),
				'symbol'=>'TZS'
			),
			'THB'=>array
			(
				'name'=>esc_html__('Thai baht','bookingo'),
				'symbol'=>'&#3647;'
			),
			'TTD'=>array
			(
				'name'=>esc_html__('Trinidad and Tobago dollar','bookingo'),
				'symbol'=>'TTD'
			),
			'TND'=>array
			(
				'name'=>esc_html__('Tunisian dinar','bookingo'),
				'symbol'=>'TND'
			),
			'TRY'=>array
			(
				'name'=>esc_html__('Turkish new lira','bookingo'),
				'symbol'=>'&#84;&#76;'
			),
			'TMM'=>array
			(
				'name'=>esc_html__('Turkmen manat','bookingo'),
				'symbol'=>'TMM'
			),
			'AED'=>array
			(
				'name'=>esc_html__('UAE dirham','bookingo'),
				'symbol'=>'AED'
			),
			'UGX'=>array
			(
				'name'=>esc_html__('Ugandan shilling','bookingo'),
				'symbol'=>'UGX'
			),
			'UAH'=>array
			(
				'name'=>esc_html__('Ukrainian hryvnia','bookingo'),
				'symbol'=>'UAH'
			),
			'USD'=>array
			(
				'name'=>esc_html__('United States dollar','bookingo'),
				'symbol'=>'&#36;',
				'position'=>'left',
				'separator'=>'.',
				'separator2'=>  ','
			),
			'UYU'=>array
			(
				'name'=>esc_html__('Uruguayan peso','bookingo'),
				'symbol'=>'UYU'
			),
			'UZS'=>array
			(
				'name'=>esc_html__('Uzbekistani som','bookingo'),
				'symbol'=>'UZS'
			),
			'VUV'=>array
			(
				'name'=>esc_html__('Vanuatu vatu','bookingo'),
				'symbol'=>'VUV'
			),
			'VEF'=>array
			(
				'name'=>esc_html__('Venezuelan bolivar','bookingo'),
				'symbol'=>'VEF'
			),
			'VND'=>array
			(
				'name'=>esc_html__('Vietnamese dong','bookingo'),
				'symbol'=>'VND'
			),
			'XOF'=>array
			(
				'name'=>esc_html__('West African CFA franc','bookingo'),
				'symbol'=>'XOF'
			),
			'ZMK'=>array
			(
				'name'=>esc_html__('Zambian kwacha','bookingo'),
				'symbol'=>'ZMK'
			),
			'ZWD'=>array
			(
				'name'=>esc_html__('Zimbabwean dollar','bookingo'),
				'symbol'=>'ZWD'
			),
			'RMB'=>array
			(
				'name'=>esc_html__('Chinese Yuan','bookingo'),
				'symbol'=>'&yen;',
				'separator'=>'.'
			)
		);
		
		$currency=$this->useDefault($currency);

		return($currency);
	}
	
	/**************************************************************************/
	
	function useDefault($currency)
	{
		foreach($currency as $index=>$value)
		{
			if(!array_key_exists('separator',$value))
				$currency[$index]['separator']='.';
			if(!array_key_exists('separator2',$value))
				$currency[$index]['separator2']='';
			if(!array_key_exists('position',$value))
				$currency[$index]['position']='left';	
			if(!array_key_exists('space',$value))
				$currency[$index]['space']=false;	
		}
		
		return($currency);
	}
	
	/**************************************************************************/
	
	function getCurrency($currency=null)
	{
		if(is_null($currency))
			return($this->currency);
		else return($this->currency[$currency]);
	}
	
	/**************************************************************************/
	
	function isCurrency($currency)
	{
		return(array_key_exists($currency,$this->getCurrency()));
	}
	
	/**************************************************************************/

	function getBaseCurrency()
	{
		return(BGCBSOption::getOption('currency'));
	}
	
	/**************************************************************************/
	
	function getFormCurrency()
	{
		if(array_key_exists('currency',$_GET))
			$currency=BGCBSHelper::getGetValue('currency',false);
		else $currency=BGCBSHelper::getPostValue('currency');
		
		if(!$this->isCurrency($currency))
			$currency=$this->getBaseCurrency();
		
		return($currency);
	}
	
	/**************************************************************************/
	
	function getExchangeRate($currency)
	{
		$rate=1;
		
		if($this->getBaseCurrency()!=$currency)
		{
			$rate=0;
			$dictionary=BGCBSOption::getOption('currency_exchange_rate');
			
			if(array_key_exists($currency,$dictionary))
				$rate=$dictionary[$currency];
		}
		
		return($rate);
	}
	
	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/