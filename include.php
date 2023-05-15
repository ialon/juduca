<?php

/******************************************************************************/
/******************************************************************************/

require_once('define.php');

/******************************************************************************/

require_once(PLUGIN_BGCBS_CLASS_PATH.'BGCBS.File.class.php');
require_once(PLUGIN_BGCBS_CLASS_PATH.'BGCBS.Include.class.php');

BGCBSInclude::includeClass(PLUGIN_BGCBS_LIBRARY_PATH.'/stripe/init.php',array('Stripe\Stripe'));
BGCBSInclude::includeFileFromDir(PLUGIN_BGCBS_CLASS_PATH);

/******************************************************************************/
/******************************************************************************/