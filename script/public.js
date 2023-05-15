"use strict";
/******************************************************************************/
/******************************************************************************/

jQuery(document).ready(function($) 
{	
    /**************************************************************************/
	
	$('.bgcbs-course-group-list-carousel').slick(
	{
		variableWidth:false,
		prevArrow:'<a href="#" class="slick-prev"></a>',
		nextArrow:'<a href="#" class="slick-next"></a>',
		slidesToShow:3,
		slidesToScroll:1,
		centerMode:false,
		responsive: 
		[
			{
				breakpoint:960,
				settings: 
				{
					slidesToShow:2
				}
			},
			{
				breakpoint:768,
				settings: 
				{
					slidesToShow:1
				}
			}
		]
	});		
	
    /**************************************************************************/
});

/******************************************************************************/
/******************************************************************************/