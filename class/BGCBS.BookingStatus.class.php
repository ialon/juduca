<?php

/******************************************************************************/
/******************************************************************************/

class BGCBSBookingStatus
{
	/**************************************************************************/
	
	function __construct()
	{
		$this->bookingStatus=array
		(
			1=>array(esc_html__('Pending (new)','bookingo')),
			2=>array(esc_html__('Processing (accepted)','bookingo')),
			3=>array(esc_html__('Cancelled (rejected)','bookingo')),
			4=>array(esc_html__('Completed (confirmed)','bookingo')),
			5=>array(esc_html__('On hold','bookingo')),
			6=>array(esc_html__('Refunded','bookingo')),	
			7=>array(esc_html__('Failed','bookingo'))
		);		
		
		$this->bookingStatusMap=array
		(
			1=>'pending',
			2=>'processing',
			3=>'cancelled',
			4=>'completed',
			5=>'on-hold',
			6=>'refunded',	
			7=>'failed'
		);	
		
		$this->bookingStatusSynchronization=array
		(
			1=>array(esc_html__('No synchronization','bookingo')),
			2=>array(esc_html__('One way: from wooCommerce to plugin','bookingo')),
			3=>array(esc_html__('One way: from plugin to wooCommerce','bookingo'))
		);
	}
	
	/**************************************************************************/
	
	function getDefaultBookingStatus()
	{
		return(key($this->bookingStatus));
	}
	
	/**************************************************************************/
	
	function getBookingStatus($bookingStatus=null)
	{
		if(is_null($bookingStatus)) return($this->bookingStatus);
		else return($this->bookingStatus[$bookingStatus]);
	}
    
    /**************************************************************************/
 
    function getBookingStatusName($bookingStatus=null)
	{
        if(is_array($this->bookingStatus[$bookingStatus])) return($this->bookingStatus[$bookingStatus][0]);
        return(null);
	}
	
	/**************************************************************************/
	
	function isBookingStatus($bookingStatus)
	{
		return(array_key_exists($bookingStatus,$this->getBookingStatus()));
	}
	
	/**************************************************************************/
	
	function getBookingStatusSynchronization($bookingStatusSynchronization=null)
	{
		if(is_null($bookingStatusSynchronization)) return($this->bookingStatusSynchronization);
		else return($this->bookingStatusSynchronization[$bookingStatusSynchronization]);
	}
	
	/**************************************************************************/
	
	function isBookingStatusSynchronization($bookingStatusSynchronization)
	{
		return(array_key_exists($bookingStatusSynchronization,$this->getBookingStatusSynchronization()));
	}
	
	/**************************************************************************/
	
	function mapBookingStatus($bookingStatusId)
	{
		if($this->isBookingStatus($bookingStatusId))
		{
			return($this->bookingStatusMap[$bookingStatusId]);
		}
		else
		{
			return(array_search($bookingStatusId,$this->bookingStatusMap));
		}
	}
	
	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/