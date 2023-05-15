<?php

/******************************************************************************/
/******************************************************************************/

class BGCBSGlobalData
{
	/**************************************************************************/
	
	static function setGlobalData($name,$value,$refresh=false)
	{
		global $bgcbsGlobalData;
		
		if(isset($bgcbsGlobalData[$name]) && (!$refresh)) return($bgcbsGlobalData[$name]);
		
		if(is_callable($value))
			$bgcbsGlobalData[$name]=call_user_func($value);
		else $bgcbsGlobalData[$name]=$value;
		
		return($bgcbsGlobalData[$name]);
	}

	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/