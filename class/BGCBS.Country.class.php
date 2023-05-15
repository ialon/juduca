<?php

/******************************************************************************/
/******************************************************************************/

class BGCBSCountry
{
	/**************************************************************************/

	function __construct()
	{
		$this->country=BGCBSGlobalData::setGlobalData('country',array($this,'init'));
	}
	
	/**************************************************************************/

	function init()
	{
		$country=array
		(
			'AF'=>array(esc_html__('Afghanistan','bookingo')),
			'AX'=>array(esc_html__('Aland Islands','bookingo')),
			'AL'=>array(esc_html__('Albania','bookingo')),
			'DZ'=>array(esc_html__('Algeria','bookingo')),
			'AS'=>array(esc_html__('American Samoa','bookingo')),
			'AD'=>array(esc_html__('Andorra','bookingo')),
			'AO'=>array(esc_html__('Angola','bookingo')),
			'AI'=>array(esc_html__('Anguilla','bookingo')),
			'AQ'=>array(esc_html__('Antarctica','bookingo')),
			'AG'=>array(esc_html__('Antigua And Barbuda','bookingo')),
			'AR'=>array(esc_html__('Argentina','bookingo')),
			'AM'=>array(esc_html__('Armenia','bookingo')),
			'AW'=>array(esc_html__('Aruba','bookingo')),
			'AU'=>array(esc_html__('Australia','bookingo')),
			'AT'=>array(esc_html__('Austria','bookingo')),
			'AZ'=>array(esc_html__('Azerbaijan','bookingo')),
			'BS'=>array(esc_html__('Bahamas','bookingo')),
			'BH'=>array(esc_html__('Bahrain','bookingo')),
			'BD'=>array(esc_html__('Bangladesh','bookingo')),
			'BB'=>array(esc_html__('Barbados','bookingo')),
			'BY'=>array(esc_html__('Belarus','bookingo')),
			'BE'=>array(esc_html__('Belgium','bookingo')),
			'BZ'=>array(esc_html__('Belize','bookingo')),
			'BJ'=>array(esc_html__('Benin','bookingo')),
			'BM'=>array(esc_html__('Bermuda','bookingo')),
			'BT'=>array(esc_html__('Bhutan','bookingo')),
			'BO'=>array(esc_html__('Bolivia','bookingo')),
			'BA'=>array(esc_html__('Bosnia And Herzegovina','bookingo')),
			'BW'=>array(esc_html__('Botswana','bookingo')),
			'BV'=>array(esc_html__('Bouvet Island','bookingo')),
			'BR'=>array(esc_html__('Brazil','bookingo')),
			'IO'=>array(esc_html__('British Indian Ocean Territory','bookingo')),
			'BN'=>array(esc_html__('Brunei Darussalam','bookingo')),
			'BG'=>array(esc_html__('Bulgaria','bookingo')),
			'BF'=>array(esc_html__('Burkina Faso','bookingo')),
			'BI'=>array(esc_html__('Burundi','bookingo')),
			'KH'=>array(esc_html__('Cambodia','bookingo')),
			'CM'=>array(esc_html__('Cameroon','bookingo')),
			'CA'=>array(esc_html__('Canada','bookingo')),
			'CV'=>array(esc_html__('Cape Verde','bookingo')),
			'KY'=>array(esc_html__('Cayman Islands','bookingo')),
			'CF'=>array(esc_html__('Central African Republic','bookingo')),
			'TD'=>array(esc_html__('Chad','bookingo')),
			'CL'=>array(esc_html__('Chile','bookingo')),
			'CN'=>array(esc_html__('China','bookingo')),
			'CX'=>array(esc_html__('Christmas Island','bookingo')),
			'CC'=>array(esc_html__('Cocos (Keeling) Islands','bookingo')),
			'CO'=>array(esc_html__('Colombia','bookingo')),
			'KM'=>array(esc_html__('Comoros','bookingo')),
			'CG'=>array(esc_html__('Congo','bookingo')),
			'CD'=>array(esc_html__('Congo, Democratic Republic','bookingo')),
			'CK'=>array(esc_html__('Cook Islands','bookingo')),
			'CR'=>array(esc_html__('Costa Rica','bookingo')),
			'CI'=>array(esc_html__('Cote D\'Ivoire','bookingo')),
			'HR'=>array(esc_html__('Croatia','bookingo')),
			'CU'=>array(esc_html__('Cuba','bookingo')),
			'CW'=>array(esc_html__('Curaçao','bookingo')),
			'CY'=>array(esc_html__('Cyprus','bookingo')),
			'CZ'=>array(esc_html__('Czech Republic','bookingo')),
			'DK'=>array(esc_html__('Denmark','bookingo')),
			'DJ'=>array(esc_html__('Djibouti','bookingo')),
			'DM'=>array(esc_html__('Dominica','bookingo')),
			'DO'=>array(esc_html__('Dominican Republic','bookingo')),
			'EC'=>array(esc_html__('Ecuador','bookingo')),
			'EG'=>array(esc_html__('Egypt','bookingo')),
			'SV'=>array(esc_html__('El Salvador','bookingo')),
			'GQ'=>array(esc_html__('Equatorial Guinea','bookingo')),
			'ER'=>array(esc_html__('Eritrea','bookingo')),
			'EE'=>array(esc_html__('Estonia','bookingo')),
			'ET'=>array(esc_html__('Ethiopia','bookingo')),
			'FK'=>array(esc_html__('Falkland Islands (Malvinas)','bookingo')),
			'FO'=>array(esc_html__('Faroe Islands','bookingo')),
			'FJ'=>array(esc_html__('Fiji','bookingo')),
			'FI'=>array(esc_html__('Finland','bookingo')),
			'FR'=>array(esc_html__('France','bookingo')),
			'GF'=>array(esc_html__('French Guiana','bookingo')),
			'PF'=>array(esc_html__('French Polynesia','bookingo')),
			'TF'=>array(esc_html__('French Southern Territories','bookingo')),
			'GA'=>array(esc_html__('Gabon','bookingo')),
			'GM'=>array(esc_html__('Gambia','bookingo')),
			'GE'=>array(esc_html__('Georgia','bookingo')),
			'DE'=>array(esc_html__('Germany','bookingo')),
			'GH'=>array(esc_html__('Ghana','bookingo')),
			'GI'=>array(esc_html__('Gibraltar','bookingo')),
			'GR'=>array(esc_html__('Greece','bookingo')),
			'GL'=>array(esc_html__('Greenland','bookingo')),
			'GD'=>array(esc_html__('Grenada','bookingo')),
			'GP'=>array(esc_html__('Guadeloupe','bookingo')),
			'GU'=>array(esc_html__('Guam','bookingo')),
			'GT'=>array(esc_html__('Guatemala','bookingo')),
			'GG'=>array(esc_html__('Guernsey','bookingo')),
			'GN'=>array(esc_html__('Guinea','bookingo')),
			'GW'=>array(esc_html__('Guinea-Bissau','bookingo')),
			'GY'=>array(esc_html__('Guyana','bookingo')),
			'HT'=>array(esc_html__('Haiti','bookingo')),
			'HM'=>array(esc_html__('Heard Island & Mcdonald Islands','bookingo')),
			'VA'=>array(esc_html__('Holy See (Vatican City State)','bookingo')),
			'HN'=>array(esc_html__('Honduras','bookingo')),
			'HK'=>array(esc_html__('Hong Kong','bookingo')),
			'HU'=>array(esc_html__('Hungary','bookingo')),
			'IS'=>array(esc_html__('Iceland','bookingo')),
			'IN'=>array(esc_html__('India','bookingo')),
			'ID'=>array(esc_html__('Indonesia','bookingo')),
			'IR'=>array(esc_html__('Iran, Islamic Republic Of','bookingo')),
			'IQ'=>array(esc_html__('Iraq','bookingo')),
			'IE'=>array(esc_html__('Ireland','bookingo')),
			'IM'=>array(esc_html__('Isle Of Man','bookingo')),
			'IL'=>array(esc_html__('Israel','bookingo')),
			'IT'=>array(esc_html__('Italy','bookingo')),
			'JM'=>array(esc_html__('Jamaica','bookingo')),
			'JP'=>array(esc_html__('Japan','bookingo')),
			'JE'=>array(esc_html__('Jersey','bookingo')),
			'JO'=>array(esc_html__('Jordan','bookingo')),
			'KZ'=>array(esc_html__('Kazakhstan','bookingo')),
			'KE'=>array(esc_html__('Kenya','bookingo')),
			'KI'=>array(esc_html__('Kiribati','bookingo')),
			'KR'=>array(esc_html__('Korea','bookingo')),
			'KW'=>array(esc_html__('Kuwait','bookingo')),
			'KG'=>array(esc_html__('Kyrgyzstan','bookingo')),
			'LA'=>array(esc_html__('Lao People\'s Democratic Republic','bookingo')),
			'LV'=>array(esc_html__('Latvia','bookingo')),
			'LB'=>array(esc_html__('Lebanon','bookingo')),
			'LS'=>array(esc_html__('Lesotho','bookingo')),
			'LR'=>array(esc_html__('Liberia','bookingo')),
			'LY'=>array(esc_html__('Libyan Arab Jamahiriya','bookingo')),
			'LI'=>array(esc_html__('Liechtenstein','bookingo')),
			'LT'=>array(esc_html__('Lithuania','bookingo')),
			'LU'=>array(esc_html__('Luxembourg','bookingo')),
			'MO'=>array(esc_html__('Macao','bookingo')),
			'MK'=>array(esc_html__('Macedonia','bookingo')),
			'MG'=>array(esc_html__('Madagascar','bookingo')),
			'MW'=>array(esc_html__('Malawi','bookingo')),
			'MY'=>array(esc_html__('Malaysia','bookingo')),
			'MV'=>array(esc_html__('Maldives','bookingo')),
			'ML'=>array(esc_html__('Mali','bookingo')),
			'MT'=>array(esc_html__('Malta','bookingo')),
			'MH'=>array(esc_html__('Marshall Islands','bookingo')),
			'MQ'=>array(esc_html__('Martinique','bookingo')),
			'MR'=>array(esc_html__('Mauritania','bookingo')),
			'MU'=>array(esc_html__('Mauritius','bookingo')),
			'YT'=>array(esc_html__('Mayotte','bookingo')),
			'MX'=>array(esc_html__('Mexico','bookingo')),
			'FM'=>array(esc_html__('Micronesia, Federated States Of','bookingo')),
			'MD'=>array(esc_html__('Moldova','bookingo')),
			'MC'=>array(esc_html__('Monaco','bookingo')),
			'MN'=>array(esc_html__('Mongolia','bookingo')),
			'ME'=>array(esc_html__('Montenegro','bookingo')),
			'MS'=>array(esc_html__('Montserrat','bookingo')),
			'MA'=>array(esc_html__('Morocco','bookingo')),
			'MZ'=>array(esc_html__('Mozambique','bookingo')),
			'MM'=>array(esc_html__('Myanmar','bookingo')),
			'NA'=>array(esc_html__('Namibia','bookingo')),
			'NR'=>array(esc_html__('Nauru','bookingo')),
			'NP'=>array(esc_html__('Nepal','bookingo')),
			'NL'=>array(esc_html__('Netherlands','bookingo')),
			'NC'=>array(esc_html__('New Caledonia','bookingo')),
			'NZ'=>array(esc_html__('New Zealand','bookingo')),
			'NI'=>array(esc_html__('Nicaragua','bookingo')),
			'NE'=>array(esc_html__('Niger','bookingo')),
			'NG'=>array(esc_html__('Nigeria','bookingo')),
			'NU'=>array(esc_html__('Niue','bookingo')),
			'NF'=>array(esc_html__('Norfolk Island','bookingo')),
			'MP'=>array(esc_html__('Northern Mariana Islands','bookingo')),
			'NO'=>array(esc_html__('Norway','bookingo')),
			'OM'=>array(esc_html__('Oman','bookingo')),
			'PK'=>array(esc_html__('Pakistan','bookingo')),
			'PW'=>array(esc_html__('Palau','bookingo')),
			'PS'=>array(esc_html__('Palestinian Territory, Occupied','bookingo')),
			'PA'=>array(esc_html__('Panama','bookingo')),
			'PG'=>array(esc_html__('Papua New Guinea','bookingo')),
			'PY'=>array(esc_html__('Paraguay','bookingo')),
			'PE'=>array(esc_html__('Peru','bookingo')),
			'PH'=>array(esc_html__('Philippines','bookingo')),
			'PN'=>array(esc_html__('Pitcairn','bookingo')),
			'PL'=>array(esc_html__('Poland','bookingo')),
			'PT'=>array(esc_html__('Portugal','bookingo')),
			'PR'=>array(esc_html__('Puerto Rico','bookingo')),
			'QA'=>array(esc_html__('Qatar','bookingo')),
			'RE'=>array(esc_html__('Reunion','bookingo')),
			'RO'=>array(esc_html__('Romania','bookingo')),
			'RU'=>array(esc_html__('Russian Federation','bookingo')),
			'RW'=>array(esc_html__('Rwanda','bookingo')),
			'BL'=>array(esc_html__('Saint Barthelemy','bookingo')),
			'SH'=>array(esc_html__('Saint Helena','bookingo')),
			'KN'=>array(esc_html__('Saint Kitts And Nevis','bookingo')),
			'LC'=>array(esc_html__('Saint Lucia','bookingo')),
			'MF'=>array(esc_html__('Saint Martin','bookingo')),
			'PM'=>array(esc_html__('Saint Pierre And Miquelon','bookingo')),
			'VC'=>array(esc_html__('Saint Vincent And Grenadines','bookingo')),
			'WS'=>array(esc_html__('Samoa','bookingo')),
			'SM'=>array(esc_html__('San Marino','bookingo')),
			'ST'=>array(esc_html__('Sao Tome And Principe','bookingo')),
			'SA'=>array(esc_html__('Saudi Arabia','bookingo')),
			'SN'=>array(esc_html__('Senegal','bookingo')),
			'RS'=>array(esc_html__('Serbia','bookingo')),
			'SC'=>array(esc_html__('Seychelles','bookingo')),
			'SL'=>array(esc_html__('Sierra Leone','bookingo')),
			'SG'=>array(esc_html__('Singapore','bookingo')),
			'SK'=>array(esc_html__('Slovakia','bookingo')),
			'SI'=>array(esc_html__('Slovenia','bookingo')),
			'SB'=>array(esc_html__('Solomon Islands','bookingo')),
			'SO'=>array(esc_html__('Somalia','bookingo')),
			'ZA'=>array(esc_html__('South Africa','bookingo')),
			'GS'=>array(esc_html__('South Georgia And Sandwich Isl.','bookingo')),
			'ES'=>array(esc_html__('Spain','bookingo')),
			'LK'=>array(esc_html__('Sri Lanka','bookingo')),
			'SD'=>array(esc_html__('Sudan','bookingo')),
			'SR'=>array(esc_html__('Suriname','bookingo')),
			'SJ'=>array(esc_html__('Svalbard And Jan Mayen','bookingo')),
			'SZ'=>array(esc_html__('Swaziland','bookingo')),
			'SE'=>array(esc_html__('Sweden','bookingo')),
			'CH'=>array(esc_html__('Switzerland','bookingo')),
			'SY'=>array(esc_html__('Syrian Arab Republic','bookingo')),
			'TW'=>array(esc_html__('Taiwan','bookingo')),
			'TJ'=>array(esc_html__('Tajikistan','bookingo')),
			'TZ'=>array(esc_html__('Tanzania','bookingo')),
			'TH'=>array(esc_html__('Thailand','bookingo')),
			'TL'=>array(esc_html__('Timor-Leste','bookingo')),
			'TG'=>array(esc_html__('Togo','bookingo')),
			'TK'=>array(esc_html__('Tokelau','bookingo')),
			'TO'=>array(esc_html__('Tonga','bookingo')),
			'TT'=>array(esc_html__('Trinidad And Tobago','bookingo')),
			'TN'=>array(esc_html__('Tunisia','bookingo')),
			'TR'=>array(esc_html__('Turkey','bookingo')),
			'TM'=>array(esc_html__('Turkmenistan','bookingo')),
			'TC'=>array(esc_html__('Turks And Caicos Islands','bookingo')),
			'TV'=>array(esc_html__('Tuvalu','bookingo')),
			'UG'=>array(esc_html__('Uganda','bookingo')),
			'UA'=>array(esc_html__('Ukraine','bookingo')),
			'AE'=>array(esc_html__('United Arab Emirates','bookingo')),
			'GB'=>array(esc_html__('United Kingdom','bookingo')),
			'US'=>array(esc_html__('United States','bookingo')),
			'UM'=>array(esc_html__('United States Outlying Islands','bookingo')),
			'UY'=>array(esc_html__('Uruguay','bookingo')),
			'UZ'=>array(esc_html__('Uzbekistan','bookingo')),
			'VU'=>array(esc_html__('Vanuatu','bookingo')),
			'VE'=>array(esc_html__('Venezuela','bookingo')),
			'VN'=>array(esc_html__('Viet Nam','bookingo')),
			'VG'=>array(esc_html__('Virgin Islands, British','bookingo')),
			'VI'=>array(esc_html__('Virgin Islands, U.S.','bookingo')),
			'WF'=>array(esc_html__('Wallis And Futuna','bookingo')),
			'EH'=>array(esc_html__('Western Sahara','bookingo')),
			'YE'=>array(esc_html__('Yemen','bookingo')),
			'ZM'=>array(esc_html__('Zambia','bookingo')),
			'ZW'=>array(esc_html__('Zimbabwe','bookingo'))
		);	
		
		return($country);
	}
	
	/**************************************************************************/
	
	function getCountry($country=null)
	{
		if(is_null($country)) return($this->country);
		else return($this->country[$country]);
	}
	
	/**************************************************************************/
	
	function isCountry($index)
	{
		return(array_key_exists($index,$this->country));
	}
	
	/**************************************************************************/
	
	function getCountryName($index)
	{
		return($this->country[$index][0]);
	}
	
	/**************************************************************************/
	
	function getDefaultCountry()
	{
		return('US');
	}
	
	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/