<?php

/******************************************************************************/
/******************************************************************************/

class BGCBSTemplate
{
	/**************************************************************************/
	
	function __construct($data,$path)
	{
		$this->data=$data;
		$this->path=$path;
	}

	/**************************************************************************/

	public function output()
	{
		ob_start();
 		include($this->path);
		$value=ob_get_clean();
		return($value);
	}
    
    /**************************************************************************/
    
 	static function outputS($data,$path,$format=false)
	{
		$Template=new BGCBSTemplate($data,$path);
		return($Template->output($format));
	}
	
	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/