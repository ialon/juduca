<?php

/******************************************************************************/
/******************************************************************************/

$CourseGroup=new BGCBSCourseGroup();
$VisualComposer=new BGCBSVisualComposer();

vc_map
( 
    array
    (
        'base'                                                                  =>  'bgcbs_course_group_list',
        'name'                                                                  =>  __('Bookingo Course Group List','bookingo'),
        'description'                                                           =>  __('Creates list (or carousel) of course groups.','bookingo'), 
        'category'                                                              =>  __('Content','bookingo'),
        'params'                                                                =>  array
        (     
            array
            (
                'type'                                                          =>  'checkbox',
                'param_name'                                                    =>  'course_group_id',
                'heading'                                                       =>  __('Course group','swimacademy-core'),
                'description'                                                   =>  __('Select at least one course group.','bookingo'),
                'value'                                                         =>  $VisualComposer->createParamDictionary($CourseGroup->getDictionary()),
				'admin_label'                                                   =>  true
            ), 
            array
            (
                'type'                                                          =>  'dropdown',
                'param_name'                                                    =>  'carousel_enable',
                'heading'                                                       =>  __('Carousel','swimacademy-core'),
                'description'                                                   =>  __('Enable or disable carousel.','swimacademy-core'),
                'value'                                                         =>  array
                (
                    __('Enable','swimacademy-core')								=>  '1',
                    __('Disable','swimacademy-core')							=>  '0',
                ),
                'std'                                                           =>  '0'
            ), 
            array
            (
                'type'                                                          =>  'dropdown',
                'param_name'                                                    =>  'style',
                'heading'                                                       =>  __('Style','swimacademy-core'),
                'description'                                                   =>  __('Select a list style.','swimacademy-core'),
                'value'                                                         =>  array
                (
                    __('Style 1','swimacademy-core')							=>  '1',
                    __('Style 2','swimacademy-core')							=>  '2',
                ),
                'std'                                                           =>  '1'
            ),
            array
            (
                'type'                                                          =>  'textfield',
                'param_name'                                                    =>  'css_class',
                'heading'                                                       =>  __('CSS class','bookingo'),
                'description'                                                   =>  __('Additional CSS classes which are applied to top level markup of this shortcode.','bookingo')
            )
        )
    )
);

/******************************************************************************/

add_shortcode('bgcbs_course_group_list',array('WPBakeryShortCode_BGCBS_Course_Group_List','vcHTML'));

/******************************************************************************/

class WPBakeryShortCode_BGCBS_Course_Group_List 
{
    /**************************************************************************/
     
    public static function vcHTML($attr,$content) 
    {
        $default=array
        (
			'course_group_id'													=>	'',
			'carousel_enable'													=>	'0',
			'style'																=>	'1',
            'css_class'                                                         =>  ''
        );
        
        $attribute=shortcode_atts($default,$attr);
        
		$CourseGroup=new BGCBSCourseGroup();
		$Validation=new BGCBSValidation();
				
		if(!$Validation->isBool($attribute['carousel_enable']))
			$attribute['carousel_enable']=0;
		if(!in_array($attribute['style'],array(1,2)))
			$attribute['style']=1;	
		
        $html=null;
        
		$dictionary=$CourseGroup->getDictionary();
		
		$courseGroup=array_map('intval',preg_split('/,/',$attribute['course_group_id']));
		
		/***/
		
		$i=0;
		foreach($courseGroup as $courseGroupId)
		{
			if(!array_key_exists($courseGroupId,$dictionary)) continue;
			
			$image=null;
			
			if(((int)$attribute['style']===2) && (in_array($i,array(0,1))))
			{
				if(class_exists('SwimAcademy_ThemeSVGImage'))
					$image=SwimAcademy_ThemeSVGImage::getCode(9+$i);
			}
			
			$html.=$CourseGroup->createCourseGroupItem($courseGroupId,$dictionary[$courseGroupId],$attribute['style'],$image);
			
			$i++;
		}
		
		/***/
		
		$class=array('bgcbs-course-group-list','bgcbs-course-group-list-style-'.(int)$attribute['style']);
		
		if((int)$attribute['carousel_enable']===1)
			array_push($class,'bgcbs-course-group-list-carousel');
			
		array_push($class,$attribute['css_class']);
		
		/***/
		
        $html= 
        '
			<div class="bgcbs-main">
				<div'.SwimAcademy_ThemeHelper::createClassAttribute($class).'>
					'.$html.'
				</div>
			</div>
        ';
		        
        return($html);        
    } 
    
    /**************************************************************************/
} 
 
/******************************************************************************/
/******************************************************************************/