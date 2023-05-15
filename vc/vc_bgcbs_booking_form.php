<?php

/******************************************************************************/
/******************************************************************************/

$Currency=new BGCBSCurrency();
$BookingForm=new BGCBSBookingForm();
$VisualComposer=new BGCBSVisualComposer();

vc_map
( 
	array
	(
		'base'                                                                  =>  BGCBSBookingForm::getShortcodeName(),
		'name'                                                                  =>  esc_html__('Bookingo Booking Form','bookingo'),
		'description'                                                           =>  esc_html__('Displays booking from.','bookingo'), 
		'category'                                                              =>  esc_html__('Content','templatica'),  
		'params'																=>  array
		(   
			array
			(
				'type'                                                          =>  'dropdown',
				'param_name'													=>  'booking_form_id',
				'heading'                                                       =>  esc_html__('Booking form','bookingo'),
				'description'                                                   =>  esc_html__('Select booking form which has to be displayed.','bookingo'),
				'value'                                                         =>  $VisualComposer->createParamDictionary($BookingForm->getDictionary()),
				'admin_label'                                                   =>  true
			),
			array
			(
				'type'                                                          =>  'dropdown',
				'param_name'													=>  'currency',
				'heading'                                                       =>  esc_html__('Currency','bookingo'),
				'description'                                                   =>  esc_html__('Select currency of booking form.','bookingo'),
				'value'                                                         =>  $VisualComposer->createParamDictionary($Currency->getCurrency()),
				'admin_label'                                                   =>  true
			)  
		)
	)
);  