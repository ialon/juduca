<?php

/******************************************************************************/
/******************************************************************************/

class BGCBSDemo
{
	/**************************************************************************/
	
	function __construct()
	{

	}
	
	/**************************************************************************/
	
	function import()
	{
		error_reporting(E_ALL);
		
		ob_start();
		ob_clean();
		
		/***/
		
		if(!defined('WP_LOAD_IMPORTERS')) define('WP_LOAD_IMPORTERS',true);

		BGCBSInclude::includeFile(ABSPATH.'wp-admin/includes/import.php');

		$includeClass=array
		(
			array
			(
				'class'=>'WP_Import',
				'path'=>PLUGIN_BGCBS_LIBRARY_PATH.'wordpress-importer.php'				
			)
		);

		foreach($includeClass as $value)
		{
			$r=BGCBSInclude::includeClass($value['path'],array($value['class']));
			if($r!==true) break;
		}

		if($r===false) return(false);

		/***/
		
		$Import=new WP_Import();
		$Import->fetch_attachments=true;
		$Import->import(PLUGIN_BGCBS_DEMO_PATH.'demo.xml.gz');
	
		/***/
		
		$BookingFormStyle=new BGCBSBookingFormStyle();
		$BookingFormStyle->createCSSFile();
		
		/***/
		
		$buffer=ob_get_clean();
		if(ob_get_contents()) ob_end_clean();
		
		return($buffer);
	}
	
	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/