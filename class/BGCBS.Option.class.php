<?php

/******************************************************************************/
/******************************************************************************/

class BGCBSOption
{
	/**************************************************************************/
	
	static function createOption($refresh=false)
	{
		return(BGCBSGlobalData::setGlobalData(PLUGIN_BGCBS_CONTEXT,array('BGCBSOption','createOptionObject'),$refresh));				
	}
		
	/**************************************************************************/
	
	static function createOptionObject()
	{	
		return((array)get_option(PLUGIN_BGCBS_OPTION_PREFIX.'_option'));
	}
	
	/**************************************************************************/
	
	static function refreshOption()
	{
		return(self::createOption(true));
	}
	
	/**************************************************************************/
	
	static function getOption($name)
	{
		global $bgcbsGlobalData;

		self::createOption();

		if(!array_key_exists($name,$bgcbsGlobalData[PLUGIN_BGCBS_CONTEXT])) return(null);
		return($bgcbsGlobalData[PLUGIN_BGCBS_CONTEXT][$name]);		
	}

	/**************************************************************************/
	
	static function getOptionObject()
	{
		global $bgcbsGlobalData;
		return($bgcbsGlobalData[PLUGIN_BGCBS_CONTEXT]);
	}
	
	/**************************************************************************/
	
	static function updateOption($option)
	{
		$nOption=array();
		foreach($option as $index=>$value) $nOption[$index]=$value;
		
		$oOption=self::refreshOption();

		update_option(PLUGIN_BGCBS_OPTION_PREFIX.'_option',array_merge($oOption,$nOption));
		
		self::refreshOption();
	}
	
	/**************************************************************************/
	
	static function resetOption()
	{
		update_option(PLUGIN_BGCBS_OPTION_PREFIX.'_option',array());
		self::refreshOption();		
	}
	
	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/