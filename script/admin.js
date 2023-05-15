/******************************************************************************/
/******************************************************************************/

;(function($,doc,win) 
{
	"use strict";
    
    /**************************************************************************/
    
    if(parseInt(bgcbsData.jqueryui_buttonset_enable,10)!==1)
    {
        $('.to .to-radio-button,.to .to-checkbox-button').addClass('to-jqueryui-buttonset-disable');
    }
    
    /**************************************************************************/
    
    var $GET=[];
    
    document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g,function() 
    {
        function decode(s) 
        {
            return(decodeURIComponent(s.split('+').join(' ')));
        }
        $GET[decode(arguments[1])]=decode(arguments[2]);
    });
    
    /**************************************************************************/
	
	$('.to-link-target-blank').attr('target','_blank');
	
	/**************************************************************************/
    
})(jQuery,document,window);

/******************************************************************************/

function toCreateCustomDateTimePicker(dateFormat,timeFormat)
{
    jQuery('.to').on('focusin','.to-timepicker-custom',function()
    {
        var width=jQuery(this).outerWidth();

        jQuery(this).timepicker(
        { 
            timeFormat:timeFormat,
            appendTo:jQuery(this).parent()
        });

        jQuery(this).on('showTimepicker',function()
        {
            jQuery(this).next('.ui-timepicker-wrapper').width(width);
        });
    }); 

    jQuery('.to').on('focusin','.to-datepicker-custom',function()
    {
        jQuery(this).datepicker(
        { 
            inline:true,
            dateFormat:dateFormat,
            prevText:'<',
            nextText:'>'
        });
		
		jQuery('.ui-datepicker').addClass('to-ui-datepicker');
    });        
};

/******************************************************************************/

function toCreateEditPostLink()
{
	jQuery('.to-edit-post').on('click',function(e)
	{
		var parent=jQuery(this).parents('li:first');
		
		var object=parent.find('select');
		
		if(parseInt(object.length,10)!==1)
			object=parent.find('input[type="radio"]:checked');
		
		var value=parseInt(object.val(),10);

		if(value>0)
		{
			jQuery(this).attr('href',jQuery(this).attr('data-href')+value);
		}
		else
		{
			e.preventDefault();
		}
	});
}

/******************************************************************************/
/******************************************************************************/