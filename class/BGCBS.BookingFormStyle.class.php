<?php

/******************************************************************************/
/******************************************************************************/

class BGCBSBookingFormStyle
{
	/**************************************************************************/
	
 	function __construct()
 	{
 	 	$this->color=array
 	 	(
			1=>array
			(
				'default'=>'333333'
			),
			2=>array
			(
				'default'=>'202124'
			),
			5=>array
			(
				'default'=>'FFFFFF'
			),			
			6=>array
			(
				'default'=>'4F2EC9'
			),
			7=>array
			(
				'default'=>'E7E9ED'
			),
			10=>array
			(
				'default'=>'828F99'
			),
			11=>array
			(
				'default'=>'transparent'
			),			
			12=>array
			(
				'default'=>'52E2BB'
			),	
			17=>array
			(
				'default'=>'DCD5F4'
			)			
 	 	);
 	}
 	
 	/**************************************************************************/
 	
 	function isColor($color)
 	{
 	 	return(array_key_exists($color,$this->getColor()));
 	}
 	
 	/**************************************************************************/
 	
 	function getColor()
 	{
 	 	return($this->color);
 	}
 	
 	/**************************************************************************/
 	
 	function createCSSFile()
 	{
		$path=array
		(
			BGCBSFile::getMultisiteBlog()
		);
		
		foreach($path as $pathData)
		{
			if(!BGCBSFile::dirExist($pathData)) @mkdir($pathData);			
			if(!BGCBSFile::dirExist($pathData)) return(false);
		}
 	 			
		/***/
 	 	
 	 	$content=null;
		
 	 	$Validation=new BGCBSValidation();
 	 	$BookingForm=new BGCBSBookingForm();
 	 	
 	 	$dictionary=$BookingForm->getDictionary(array('suppress_filters'=>true));
 	 	
 	 	foreach($dictionary as $dictionaryIndex=>$dictionaryValue)
 	 	{
 	 	 	$meta=$dictionaryValue['meta'];

 	 	 	foreach($this->getColor() as $colorIndex=>$colorValue)
 	 	 	{
 	 	 	 	if((!isset($meta['style_color'][$colorIndex])) || (!$Validation->isColor($meta['style_color'][$colorIndex]))) 
 	 	 	 	 	$meta['style_color'][$colorIndex]=$colorValue['default'];
 	 	 	}
 	 	 	
 	 	 	$data=array();
		
 	 	 	$data['color']=$meta['style_color'];
 	 	 	$data['main_css_class']='.bgcbs-booking-form-id-'.$dictionaryIndex;

 	 	 	$Template=new BGCBSTemplate($data,PLUGIN_BGCBS_TEMPLATE_PATH.'public/style.php');
		
 	 	 	$content.=$Template->output();
 	 	}
 	 	
		if($Validation->isNotEmpty($content))
			file_put_contents(BGCBSFile::getMultisiteBlogCSS(),$content); 
 	}
 	
 	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/