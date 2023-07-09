<?php

/******************************************************************************/
/******************************************************************************/

class BGCBSBookingForm
{
	/**************************************************************************/
	
 	function __construct()
 	{

 	}
 	 	
 	/**************************************************************************/
 	
 	public function init()
 	{
 	 	$this->registerCPT();
 	}
 	
	/**************************************************************************/

 	public static function getCPTName()
 	{
 	 	return(PLUGIN_BGCBS_CONTEXT.'_booking_form');
 	}
 	
 	/**************************************************************************/
 	
 	private function registerCPT()
 	{
		register_post_type
		(
			self::getCPTName(),
			array
			(
				'labels'=>array
				(
					'name'=>esc_html__('Booking Forms','bookingo'),
					'singular_name'=>esc_html__('Booking Form','bookingo'),
					'add_new'=>esc_html__('Add New','bookingo'),
					'add_new_item'=>esc_html__('Add New Booking Form','bookingo'),
					'edit_item'=>esc_html__('Edit Booking Form','bookingo'),
					'new_item'=>esc_html__('New Booking Form','bookingo'),
					'all_items'=>esc_html__('Booking Forms','bookingo'),
					'view_item'=>esc_html__('View Booking Form','bookingo'),
					'search_items'=>esc_html__('Search Booking Forms','bookingo'),
					'not_found'=>esc_html__('No Booking Forms Found','bookingo'),
					'not_found_in_trash'=>esc_html__('No Booking Forms Found in Trash','bookingo'), 
					'parent_item_colon'=>'',
					'menu_name'=>esc_html__('Booking Forms','bookingo')
				),	
				'public'=>false,  
				'show_ui'=>true, 
				'show_in_menu'=>'edit.php?post_type='.BGCBSBooking::getCPTName(),
				'capability_type'=>'post',
				'menu_position'=>2,
				'hierarchical'=>false,  
				'rewrite'=>false,
                'capabilities' => [
                    'publish_posts' => 'manage_options',
                    'edit_posts' => 'manage_options',
                    'edit_others_posts' => 'manage_options',
                    'delete_posts' => 'manage_options',
                    'delete_others_posts' => 'manage_options',
                    'read_private_posts' => 'manage_options',
                    'edit_post' => 'manage_options',
                    'delete_post' => 'manage_options',
                    'read_post' => 'manage_options',
                ],
				'supports'=>array('title')  
			)
		);
 	 	
 	 	add_action('save_post',array($this,'savePost'));
 	 	add_action('add_meta_boxes_'.self::getCPTName(),array($this,'addMetaBox'));
 	 	add_filter('postbox_classes_'.self::getCPTName().'_bgcbs_meta_box_booking_form',array($this,'adminCreateMetaBoxClass'));
 	 	
 	 	add_shortcode(PLUGIN_BGCBS_CONTEXT.'_booking_form',array($this,'createBookingForm'));
        add_shortcode(PLUGIN_BGCBS_CONTEXT.'_booking_carnet',array($this,'createBookingCarnet'));
        add_shortcode(PLUGIN_BGCBS_CONTEXT.'_booking_carnet_cta',array($this,'createBookingCarnetCta'));
        add_shortcode(PLUGIN_BGCBS_CONTEXT.'_booking_carnet_print',array($this,'createBookingCarnetPrint'));

		add_filter('manage_edit-'.self::getCPTName().'_columns',array($this,'manageEditColumns')); 
		add_action('manage_'.self::getCPTName().'_posts_custom_column',array($this,'managePostsCustomColumn'));
		add_filter('manage_edit-'.self::getCPTName().'_sortable_columns',array($this,'manageEditSortableColumns'));
 	}
 	
 	/**************************************************************************/
 	
 	static function getShortcodeName()
 	{
 	 	return(PLUGIN_BGCBS_CONTEXT.'_booking_form');
 	}
 	
 	/**************************************************************************/
 	
 	function addMetaBox()
 	{
 	 	add_meta_box(PLUGIN_BGCBS_CONTEXT.'_meta_box_booking_form',esc_html__('Main','bookingo'),array($this,'addMetaBoxMain'),self::getCPTName(),'normal','low');		
 	}
 	
 	/**************************************************************************/
 	
 	function addMetaBoxMain()
 	{
 	 	global $post;
 	 	
		$Course=new BGCBSCourse();
 	 	$Country=new BGCBSCountry();
 	 	$Currency=new BGCBSCurrency();
 	 	$BookingStatus=new BGCBSBookingStatus();
 	 	$BookingFormStyle=new BGCBSBookingFormStyle();
 	 	
		$data=array();
 	 	
 	 	$data['meta']=BGCBSPostMeta::getPostMeta($post);
 	 	
		$data['nonce']=BGCBSHelper::createNonceField(PLUGIN_BGCBS_CONTEXT.'_meta_box_booking_form');
 	 	
 	 	$data['dictionary']['color']=$BookingFormStyle->getColor();
 	 	
 	 	$data['dictionary']['course']=$Course->getDictionary();
 	 	
 	 	$data['dictionary']['country']=$Country->getCountry();
		
		$data['dictionary']['currency']=$Currency->getCurrency();
		
 	 	$data['dictionary']['booking_status']=$BookingStatus->getBookingStatus();
		
		echo BGCBSTemplate::outputS($data,PLUGIN_BGCBS_TEMPLATE_PATH.'admin/meta_box_booking_form.php');
 	}
 	
 	/**************************************************************************/
 	
 	function adminCreateMetaBoxClass($class) 
 	{
 	 	array_push($class,'to-postbox-1');
 	 	return($class);
 	}
 	
 	/**************************************************************************/
 	
 	function savePost($postId)
 	{ 	  
 	 	if(!$_POST) return(false);
 	 	
 	 	if(BGCBSHelper::checkSavePost($postId,PLUGIN_BGCBS_CONTEXT.'_meta_box_booking_form_noncename','savePost')===false) return(false);
 	 	
 	 	$Date=new BGCBSDate();
		$Currency=new BGCBSCurrency();
 	 	$Validation=new BGCBSValidation();
 	 	$BookingStatus=new BGCBSBookingStatus();
		$BookingFormStyle=new BGCBSBookingFormStyle();
 	 	
 	 	/***/
 	 	/***/
 	 	
 	 	$option=BGCBSHelper::getPostOption();

		/***/
		
		if(!$BookingStatus->isBookingStatus($option['booking_status_id_default']))
			$option['booking_status_id_default']=$BookingStatus->getDefaultBookingStatus();
		
		/***/

		if(!$Validation->isBool($option['woocommerce_enable']))
 	 	 	$option['woocommerce_enable']=0; 
		
		/***/
		
 	 	$option['currency']=(array)$option['currency'];
 	 	if(in_array(-1,$option['currency']))
 	 	{
 	 	 	$option['currency']=array(-1);
 	 	}
 	 	else
 	 	{
 	 	 	foreach($Currency->getCurrency() as $index=>$value)
 	 	 	{
 	 	 	 	if(!$Currency->isCurrency($index))
 	 	 	 	 	unset($option['currency'][$index]);
 	 	 	}
 	 	}
		
 	 	if(!count($option['currency']))
 	 	 	$option['currency']=array(-1); 
 	 	
		/***/
		
 	 	if(!$Validation->isBool($option['coupon_enable']))
 	 	 	$option['coupon_enable']=0; 
		
		/***/
		
		if(!$Validation->isBool($option['form_preloader_enable']))
			$option['form_preloader_enable']=0;
			
		if(!in_array($option['tab_active'],array(0,1)))
			$option['tab_active']=1;
		
		if(!$Validation->isBool($option['course_time_enable']))
			$option['course_time_enable']=0;		

		/***/
		
 	 	foreach($option['style_color'] as $index=>$value)
 	 	{
 	 	 	if(!$BookingFormStyle->isColor($index))
 	 	 	{
 	 	 	 	unset($option['style_color'][$index]);
 	 	 	 	continue;
 	 	 	}
 	 	 	
 	 	 	if(!$Validation->isColor($value,true))
 	 	 	 	$option['style_color'][$index]='';
 	 	}
	
 	 	/***/

 	 	$key=array
 	 	(
			'course_id',
			'booking_status_id_default',
			'woocommerce_enable',
			'currency',
			'coupon_enable',
 	 	 	'form_preloader_enable',
			'tab_active',
			'course_time_enable',
 	 	 	'style_color'
 	 	);

		foreach($key as $value)
			BGCBSPostMeta::updatePostMeta($postId,$value,$option[$value]);
 	 	
 	 	$BookingFormStyle->createCSSFile();
 	}
 	
	/**************************************************************************/
	
	function setPostMetaDefault(&$meta)
	{
 	 	BGCBSHelper::setDefault($meta,'course_id',-1);
		
		BGCBSHelper::setDefault($meta,'booking_status_id_default',1);
		
		BGCBSHelper::setDefault($meta,'woocommerce_enable',0);
		
		BGCBSHelper::setDefault($meta,'currency',array(-1));
		
		BGCBSHelper::setDefault($meta,'coupon_enable',0);
		
		BGCBSHelper::setDefault($meta,'address_layout',0);
		
 	 	BGCBSHelper::setDefault($meta,'form_preloader_enable',1); 
		BGCBSHelper::setDefault($meta,'tab_active',1); 
		BGCBSHelper::setDefault($meta,'course_time_enable',1); 
	}
 	
 	/**************************************************************************/
 	
 	function getDictionary($attr=array())
 	{
		global $post;
		
		$dictionary=array();
		
		$default=array
		(
			'booking_form_id'=>0,
			'suppress_filters'=>false
		);
		
		$attribute=shortcode_atts($default,$attr);
		
		BGCBSHelper::preservePost($post,$bPost);
		
		$argument=array
		(
			'post_type'=>self::getCPTName(),
			'post_status'=>'publish',
			'posts_per_page'=>-1,
			'orderby'=>array('menu_order'=>'asc','title'=>'asc')
		);
		
		if(array_key_exists('booking_form_id',$attr))
 	 	{
			$argument['p']=$attribute['booking_form_id'];
 	 	 	if((int)$argument['p']<=0) return($dictionary);
 	 	}
		if(array_key_exists('suppress_filters',$attr))
 	 	{
			$argument['suppress_filters']=$attribute['suppress_filters'];
 	 	}	
		
		$query=new WP_Query($argument);
		if($query===false) return($dictionary);
		
		while($query->have_posts())
		{
			$query->the_post();
			$dictionary[$post->ID]['post']=$post;
			$dictionary[$post->ID]['meta']=BGCBSPostMeta::getPostMeta($post);
			$dictionary[$post->ID]['post']->post_title=BGCBSHelper::filterPostTitle($post->post_title,$post->ID);
		}

		BGCBSHelper::preservePost($post,$bPost,0);	
		
		return($dictionary); 	 	
 	}
 	
 	/**************************************************************************/
 	
 	function manageEditColumns($column)
 	{
 	 	$column=array
 	 	(
 	 	 	'cb'=>$column['cb'],
 	 	 	'title'=>esc_html__('Title','bookingo'),
 	 	 	'course'=>esc_html__('Course','bookingo')
 	 	);
   
		return($column); 	 	 
 	}
 	
 	/**************************************************************************/
 	
 	function managePostsCustomColumn($column)
 	{
		global $post;
		
		$Course=new BGCBSCourse();
		
		$meta=BGCBSPostMeta::getPostMeta($post);
		
		switch($column)
		{
			case 'course':
				
				$dictionary=$Course->getDictionary();
				echo BGCBSHelper::displayDictionary($dictionary,$meta['course_id']);
				
			break;
		}
 	}
 	
 	/**************************************************************************/
 	
 	function manageEditSortableColumns($column)
 	{
		return($column); 	   
 	}

    /**************************************************************************/

    function createBookingCarnetCta()
    {
        $bookingid=BGCBSHelper::getGetValue('id',false);
        $meta = BGCBSPostMeta::getPostMeta($bookingid);

        if (empty($meta)) {
            return "";
        } else {
            return '<div class="wp-block-button has-custom-font-size has-medium-font-size" style="text-align:center;">
                        <a class="wp-block-button__link has-white-color has-text-color has-background has-text-align-center wp-element-button" href="https://juduca2023.ues.edu.sv/carnet/?id=' . $bookingid . '" style="border-radius:15px;background-color:#f32c46">Carnet Digital</a>
                    </div>';
        }
    }

    /**************************************************************************/

    function createBookingCarnetPrint()
    {
        if ( ! defined( 'DONOTCACHEPAGE' ) ) {
            define( 'DONOTCACHEPAGE', true );
        }

        global $wpdb;

        $output = '<link href="https://fonts.googleapis.com/css?family=Poppins:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic" rel="stylesheet" type="text/css">';
        $wheresql = "";

        if ($uni = BGCBSHelper::getGetValue('uni',false)) {
            $wheresql .= " AND uni.code = '{$uni}' ";
        }

        if ($color = BGCBSHelper::getGetValue('color',false)) {
            $wheresql .= " AND cat.color = '{$color}' ";
        }

        $query = "
            SELECT
                p.ID,
                cat.name,
                pm3.meta_value,
                cat.color,
                uni.code
            FROM {$wpdb->prefix}posts p
            LEFT JOIN
                (
                    SELECT
                        pm.post_id,
                        pm.meta_value AS name,
                        CASE
                                WHEN
                                (pm.meta_value = 'Cuerpo médico') THEN 'orange'
                            WHEN
                                (pm.meta_value = 'Prensa') THEN 'purple'
                            WHEN
                                (pm.meta_value = 'Funcionarios' OR
                                 pm.meta_value = 'Jefe de delegación' OR
                                 pm.meta_value = 'Jefe de misión') THEN 'gold'
                            WHEN
                                (pm.meta_value = 'Baloncesto' OR
                                 pm.meta_value = 'Fútbol' OR
                                 pm.meta_value = 'Fútbol Sala' OR
                                 pm.meta_value = 'Vóleibol' OR
                                 pm.meta_value = 'Tenis de Mesa' OR
                                 pm.meta_value = 'Ajedrez' OR
                                 pm.meta_value = 'Atletismo' OR
                                 pm.meta_value = 'Natación' OR
                                 pm.meta_value = 'Taekwondo' OR
                                 pm.meta_value = 'Karate Do' OR
                                 pm.meta_value = 'Entrenadores' OR
                                 pm.meta_value = 'Cuerpo técnico' OR
                                 pm.meta_value = 'Delegados') THEN 'gray'
                            ELSE 'gray'
                        END AS color
                    FROM {$wpdb->prefix}postmeta pm
                    WHERE pm.meta_key = 'bgcbs_course_name'
                ) cat ON cat.post_id = p.ID
            LEFT JOIN
                (
                    SELECT
                        pm2.post_id,
                        pm2.meta_key AS name,
                        CASE
                            WHEN pm2.meta_value LIKE '%Universidad Autónoma de Santo Domingo%' THEN 'uasd'
                            WHEN pm2.meta_value LIKE '%Universidad de Belize%' THEN 'ub'
                            WHEN pm2.meta_value LIKE '%Universidad de San Carlos de Guatemala%' THEN 'usac'
                            WHEN pm2.meta_value LIKE '%Universidad Nacional Autónoma de Honduras%' THEN 'unah'
                            WHEN pm2.meta_value LIKE '%Universidad Nacional de Ciencias Forestales%' THEN 'unacifor'
                            WHEN pm2.meta_value LIKE '%Universidad Pedagógica Nacional Francisco Morazán%' THEN 'unpfm'
                            WHEN pm2.meta_value LIKE '%Universidad Nacional de Agricultura%' THEN 'unag'
                            WHEN pm2.meta_value LIKE '%Bluefields Indian and Caribbean University%' THEN 'bicu'
                            WHEN pm2.meta_value LIKE '%Universidad de las Regiones Autónomas de la Costa Caribe Nicaragüense%' THEN 'uraccan'
                            WHEN pm2.meta_value LIKE '%Universidad Nacional Autónoma de Nicaragua Managua%' THEN 'unan-managua'
                            WHEN pm2.meta_value LIKE '%Universidad Nacional Autónoma de Nicaragua León%' THEN 'unan-leon'
                            WHEN pm2.meta_value LIKE '%Universidad Nacional Agraria de Nicaragua%' THEN 'ni_una'
                            WHEN pm2.meta_value LIKE '%Universidad Nacional de Ingeniería%' THEN 'uni'
                            WHEN pm2.meta_value LIKE '%Universidad Técnica Nacional de Costa Rica%' THEN 'utn'
                            WHEN pm2.meta_value LIKE '%Universidad Estatal a Distancia de Costa Rica%' THEN 'uned'
                            WHEN pm2.meta_value LIKE '%Tecnológico de Costa Rica%' THEN 'tec'
                            WHEN pm2.meta_value LIKE '%Universidad de Costa Rica%' THEN 'ucr'
                            WHEN pm2.meta_value LIKE '%Universidad Nacional de Costa Rica%' THEN 'cr_una'
                            WHEN pm2.meta_value LIKE '%Universidad Marítima Internacional de Panamá%' THEN 'umip'
                            WHEN pm2.meta_value LIKE '%Universidad Especializada de las Américas%' THEN 'udelas'
                            WHEN pm2.meta_value LIKE '%Universidad Autónoma de Chiriquí%' THEN 'unachi'
                            WHEN pm2.meta_value LIKE '%Universidad de Panamá%' THEN 'up'
                            WHEN pm2.meta_value LIKE '%Universidad Tecnológica de Panamá%' THEN 'utp'
                            WHEN pm2.meta_value LIKE '%Universidad de El Salvador%' THEN 'ues'
                            ELSE 'ues'
                        END AS code
                    FROM {$wpdb->prefix}postmeta pm2
                    WHERE pm2.meta_key = 'bgcbs_form_element_field'
                ) uni ON uni.post_id = p.ID
            LEFT JOIN {$wpdb->prefix}postmeta pm3 ON pm3.post_id = p.ID AND pm3.meta_key = 'bgcbs_course_group_name'
            WHERE p.post_type = 'bgcbs_booking' {$wheresql}
            ORDER BY uni.code, cat.color, pm3.meta_value, p.ID
        ";

        $allbookings = $wpdb->get_results($wpdb->prepare($query));

        foreach ($allbookings as $booking) {
            $output .= $this->createBookingCarnet($booking->ID, true);
        }

        // $output .= "<br>UES 2023";

        return $output;
    }

    /**************************************************************************/

    function createBookingCarnet($bid = null, $includeback = false)
    {
        if ( ! defined( 'DONOTCACHEPAGE' ) ) {
            define( 'DONOTCACHEPAGE', true );
        }

        $Booking=new BGCBSBooking();

        $output = '';
        if (!$bid) {
            $output = '<link href="https://fonts.googleapis.com/css?family=Poppins:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic" rel="stylesheet" type="text/css">';
        }

        $bookingid = BGCBSHelper::getGetValue('id',false);
        if (!$bookingid) {
            $bookingid = $bid;
        }

        $meta = BGCBSPostMeta::getPostMeta($bookingid);

        if (empty($meta))
        {
            return "ID inválido o no encontrado";
        }

        $university = $Booking->getUniversityFromBooking($bookingid);

        $data = [];

        $data['bookingid'] = $bookingid;
        $data['universidad'] = $university;
        $data['disciplina'] = $meta['course_name'];
        $data['categoria'] = $meta['course_group_name'];
        $data['nombres'] = ucwords(strtolower($meta['participant_first_name']));
        $data['apellidos'] = ucwords(strtolower($meta['participant_second_name']));

        foreach($meta['form_element_field'] as $elementfield)
        {
            if (($elementfield['label'] == 'Foto de carnet')) {
                $data['carnet-photo'] = "<div class=\"carnet-photo\" style=\"background-image: url('" . wp_get_attachment_url($elementfield['value']) . "') !important;\"></div>";
            } else if (($elementfield['label'] == 'Segundo nombre')) {
                $data['nombres'] .= ' ' . ucwords(strtolower($elementfield['value']));
            } else if (($elementfield['label'] == 'Segundo apellido')) {
                $data['apellidos'] .= ' ' . ucwords(strtolower($elementfield['value']));
            } else if (($elementfield['label'] == 'Disciplina')) {
                $data['disciplina'] = $elementfield['value'];
            } else if (($elementfield['label'] == 'Cargo que desempeña')) {
                $data['funcionario'] = $elementfield['value'];
            }
        }

        // Fix names
        $altnames = ucwords(mb_strtolower($data['nombres']));
        $altlastnames = ucwords(mb_strtolower($data['apellidos']));

        $data['caps-fixed'] = '';
        if ($data['nombres'] !== $altnames || $data['apellidos'] !== $altlastnames) {
            $data['caps-fixed'] = 'caps-fixed';
        }
        $data['nombres'] = $altnames;
        $data['apellidos'] = $altlastnames;

        // Background
        switch ($meta['course_name']) {
            case 'Funcionarios':
            case 'Jefe de delegación':
            case 'Jefe de misión':
                $data['color'] = 'gold';
                $qrbgcolor = 'C99C27';
                break;
            case 'Cuerpo médico':
                $data['color'] = 'orange';
                $qrbgcolor = 'EE7627';
                break;
            case 'Prensa':
                $data['color'] = 'purple';
                $qrbgcolor = 'D42262';
                break;
            case 'Baloncesto':
            case 'Fútbol':
            case 'Fútbol Sala':
            case 'Vóleibol':
            case 'Tenis de Mesa':
            case 'Ajedrez':
            case 'Atletismo':
            case 'Natación':
            case 'Taekwondo':
            case 'Karate Do':
            case 'Entrenadores':
            case 'Cuerpo técnico':
            case 'Delegados':
            default:
                $data['color'] = 'gray';
                $qrbgcolor = '585857';
                break;
        }

        // Country
        switch ($university) {
            case 'Universidad Autónoma de Santo Domingo':
                $data['country'] = 'República Dominicana';
                $data['countrycode'] = 'do';
                break;
            case 'Universidad de Belize':
                $data['country'] = 'Belize';
                $data['countrycode'] = 'be';
                break;
            case 'Universidad de San Carlos de Guatemala':
                $data['country'] = 'Guatemala';
                $data['countrycode'] = 'gt';
                break;
            case 'Universidad Nacional Autónoma de Honduras':
            case 'Universidad Nacional de Ciencias Forestales':
            case 'Universidad Pedagógica Nacional Francisco Morazán':
            case 'Universidad Nacional de Agricultura':
                $data['country'] = 'Honduras';
                $data['countrycode'] = 'hn';
                break;
            case 'Bluefields Indian and Caribbean University':
            case 'Universidad de las Regiones Autónomas de la Costa Caribe Nicaragüense':
            case 'Universidad Nacional Autónoma de Nicaragua Managua':
            case 'Universidad Nacional Autónoma de Nicaragua León':
            case 'Universidad Nacional Agraria de Nicaragua':
            case 'Universidad Nacional de Ingeniería':
                $data['country'] = 'Nicaragua';
                $data['countrycode'] = 'ni';
                break;
            case 'Universidad Técnica Nacional de Costa Rica':
            case 'Universidad Estatal a Distancia de Costa Rica':
            case 'Tecnológico de Costa Rica':
            case 'Universidad de Costa Rica':
            case 'Universidad Nacional de Costa Rica':
                $data['country'] = 'Costa Rica';
                $data['countrycode'] = 'cr';
                break;
            case 'Universidad Marítima Internacional de Panamá':
            case 'Universidad Especializada de las Américas':
            case 'Universidad Autónoma de Chiriquí':
            case 'Universidad de Panamá':
            case 'Universidad Tecnológica de Panamá':
                $data['country'] = 'Panamá';
                $data['countrycode'] = 'pa';
                break;
            case 'Universidad de El Salvador':
            default:
                $data['country'] = 'El Salvador';
                $data['countrycode'] = 'sv';
                break;
        }

        // Type
        switch ($meta['course_name']) {
            case 'Baloncesto':
            case 'Fútbol':
            case 'Fútbol Sala':
            case 'Vóleibol':
            case 'Tenis de Mesa':
            case 'Ajedrez':
            case 'Atletismo':
            case 'Natación':
            case 'Taekwondo':
            case 'Karate Do':
                $data['type'] = 'Atleta';
                break;
            case 'Funcionarios':
                $data['type'] = $data['funcionario'];
                break;
            case 'Entrenadores':
                $data['type'] = 'Entrenador';
                break;
            case 'Delegados':
                $data['type'] = 'Delegado';
                break;
            case 'Jefe de delegación':
            case 'Jefe de misión':
            case 'Cuerpo médico':
            case 'Prensa':
            case 'Cuerpo técnico':
            default:
                $data['type'] = $meta['course_name'];
                break;
        }

        // Sport logo
        switch ($meta['course_name']) {
            case 'Baloncesto':
            case 'Fútbol':
            case 'Fútbol Sala':
            case 'Vóleibol':
            case 'Tenis de Mesa':
            case 'Ajedrez':
            case 'Atletismo':
            case 'Natación':
            case 'Taekwondo':
            case 'Karate Do':
                $sport = str_replace(' ', '', strtolower($meta['course_name']));
                $sport = str_replace('ó', 'o', $sport);
                $sport = str_replace('ú', 'u', $sport);
                $data['sportlogo'] = '<img class="carnet-sport" alt="' . $meta['course_name'] . '" src="/wp-content/uploads/2023/06/sport-' . $sport . '.png"/>';
                break;
            case 'Entrenadores':
            case 'Delegados':
            case 'Cuerpo técnico':
                $sport = str_replace(' ', '', strtolower($data['disciplina']));
                $sport = str_replace('ó', 'o', $sport);
                $sport = str_replace('ú', 'u', $sport);
                $data['sportlogo'] = '<img class="carnet-sport" alt="' . $meta['course_name'] . '" src="/wp-content/uploads/2023/06/sport-' . $sport . '.png"/>';
                break;
            case 'Funcionarios':
            case 'Jefe de delegación':
            case 'Jefe de misión':
            case 'Cuerpo médico':
            case 'Prensa':
            default:
                $data['sportlogo'] = '';
                break;
        }

        // QR Code
        $data['qrcode'] = '<img src="https://api.qrserver.com/v1/create-qr-code/?color=FFFFFF&margin=0&bgcolor=' . $qrbgcolor . '&size=120x120&data=' . urlencode('https://juduca2023.ues.edu.sv/qr/?id=' . $bookingid) . '">';


        // Uni logo
        switch ($university) {
            case 'Universidad Autónoma de Santo Domingo':
                $shortname = 'uasd';
                break;
            case 'Universidad de Belize':
                $shortname = 'ub';
                break;
            case 'Universidad de San Carlos de Guatemala':
                $shortname = 'usac';
                break;
            case 'Universidad Nacional Autónoma de Honduras':
                $shortname = 'unah';
                break;
            case 'Universidad Nacional de Ciencias Forestales':
                $shortname = 'unacifor';
                break;
            case 'Universidad Pedagógica Nacional Francisco Morazán':
                $shortname = 'unpfm';
                break;
            case 'Universidad Nacional de Agricultura':
                $shortname = 'unag';
                break;
            case 'Bluefields Indian and Caribbean University':
                $shortname = 'bicu';
                break;
            case 'Universidad de las Regiones Autónomas de la Costa Caribe Nicaragüense':
                $shortname = 'uraccan';
                break;
            case 'Universidad Nacional Autónoma de Nicaragua Managua':
                $shortname = 'unan-managua';
                break;
            case 'Universidad Nacional Autónoma de Nicaragua León':
                $shortname = 'unan-leon';
                break;
            case 'Universidad Nacional Agraria de Nicaragua':
                $shortname = 'ni_una';
                break;
            case 'Universidad Nacional de Ingeniería':
                $shortname = 'uni';
                break;
            case 'Universidad Técnica Nacional de Costa Rica':
                $shortname = 'utn';
                break;
            case 'Universidad Estatal a Distancia de Costa Rica':
                $shortname = 'uned';
                break;
            case 'Tecnológico de Costa Rica':
                $shortname = 'tec';
                break;
            case 'Universidad de Costa Rica':
                $shortname = 'ucr';
                break;
            case 'Universidad Nacional de Costa Rica':
                $shortname = 'cr_una';
                break;
            case 'Universidad Marítima Internacional de Panamá':
                $shortname = 'umip';
                break;
            case 'Universidad Especializada de las Américas':
                $shortname = 'udelas';
                break;
            case 'Universidad Autónoma de Chiriquí':
                $shortname = 'unachi';
                break;
            case 'Universidad de Panamá':
                $shortname = 'up';
                break;
            case 'Universidad Tecnológica de Panamá':
                $shortname = 'utp';
                break;
            case 'Universidad de El Salvador':
                $shortname = 'ues';
                break;
        }

        $args = array(
            'post_type' => 'attachment',
            'name' => sanitize_title($shortname),
            'posts_per_page' => 1,
            'post_status' => 'inherit',
        );
        $_header = get_posts( $args );
        $header = $_header ? array_pop($_header) : null;
        $data['universidadlogo'] = $header ? wp_get_attachment_image_url($header->ID, 'medium') : '';
        $data['unishortname'] = $shortname;

        $data['includeback'] = $includeback;

        /***/

        $Template=new BGCBSTemplate($data,PLUGIN_BGCBS_TEMPLATE_PATH.'public/carnet.php');
        $output .= $Template->output();
        unset($Template);
        return $output;
    }
 	
 	/**************************************************************************/
 	
	function createBookingForm($attr)
	{
		$Booking=new BGCBSBooking();
		$PaymentPaypal=new BGCBSPaymentPaypal();
		$CourseAgreement=new BGCBSCourseAgreement();

		$action=BGCBSHelper::getGetValue('action',false);
		if($action==='ipn')
		{
			$PaymentPaypal->handleIPN();
			return(null);
		}

		$default=array
		(
			'currency'=>'',
			'booking_form_id'=>0
		);

		$attribute=shortcode_atts($default,$attr); 	 	 	   

		$data=$checkBookingForm=$this->checkBookingForm($attribute['booking_form_id'],$attribute['currency'],$bookingFormError);

		$data['ajax_url']=admin_url('admin-ajax.php');

		$data['booking_form_post_id']=$attribute['booking_form_id'];
		$data['booking_form_html_id']=BGCBSHelper::createId('bgcbs_booking_form');

		if(!is_array($checkBookingForm))
		{
			$data['notice_3']=$bookingFormError['message'];
			$data['booking_form_active']=0;
		}
		else
		{
			$data['course_promo_section']=$this->createPromoSection($data);

			$data['course_group_form']=$this->createCourseGroupForm($data);
			$data['course_participant_form']=$this->createCourseParticipantForm($data);
			$data['course_applicant_form']=$this->createCourseApplicantForm($data);
			$data['course_custom_form']=$this->createCourseCustomForm($data);

			$data['agreement_form']=$CourseAgreement->createAgreement($data);

			$data['schedule_section']=$this->createBookingFormSchedule($data);

			$data['info_1_section']=$this->createBookingFormInfo($data,1);
			$data['info_2_section']=$this->createBookingFormInfo($data,2);

			$data['coupon_form']=$this->createBookingFormCoupon($data);

			/***/

			$data['step_active']=1;

			/***/

			if($action==='booking_pay')
			{
				$bookingId=BGCBSHelper::getGetValue('booking_id',false);

				if($Booking->validatePayBooking($bookingId,true,$error)!==false)
				{
					$data['step_active']=2;
					$data['booking_id']=$bookingId;
				}
				else
				{
					$data['notice_1']=$error['message'];
				}
			}
		}

		/***/

		$Template=new BGCBSTemplate($data,PLUGIN_BGCBS_TEMPLATE_PATH.'public/public.php');
		return($Template->output());
	}

	/**************************************************************************/

	function checkBookingForm($bookingFormId,$currency=null,&$error=array())
	{
		$bookingForm=array(); 

		/****/ 

		$data=BGCBSHelper::getPostOption();
		
		if(!array_key_exists('course_group_id',$data))
			$data['course_group_id']=BGCBSHelper::getGetValue('course_group_id',false);
		
		/****/ 

		$Course=new BGCBSCourse();
		$TaxRate=new BGCBSTaxRate();
		$Currency=new BGCBSCurrency();
		$Location=new BGCBSLocation();
		$PriceRule=new BGCBSPriceRule();
		$CourseGroup=new BGCBSCourseGroup();

		/****/ 

		$bookingForm['booking_form']=$this->getDictionary(array('booking_form_id'=>$bookingFormId));
		if(!count($bookingForm['booking_form'])) 
		{
			$error=array('id'=>1,'message'=>esc_html__('Cannot find booking form with provided ID.','bookingo'));
			return(false);
		}

		$bookingForm['booking_form_id']=$bookingFormId;

		/****/ 

		$bookingForm['course']=$Course->getDictionary(array('course_id'=>$bookingForm['booking_form'][$bookingFormId]['meta']['course_id']));
		if(!count($bookingForm['course'])) 
		{
			$error=array('id'=>2,'message'=>esc_html__('Cannot find course assigned to this booking form.','bookingo'));
			return(false);
		}	

		$bookingForm['course_id']=$bookingForm['booking_form'][$bookingFormId]['meta']['course_id'];

		/****/  

		$bookingForm['course_group']=$CourseGroup->getDictionary(array('course_group_id'=>$bookingForm['course'][$bookingForm['course_id']]['meta']['course_group_id']));

		if(count($bookingForm['course_group']))
		{
			foreach($bookingForm['course_group'] as $index=>$value)
			{
				$active=$this->checkCourseGroupActive($bookingForm,$index);
				if(!$active) $bookingForm['course_group_inactive'][$index] = $value;
                // if(!$active) unset($bookingForm['course_group'][$index]);
			}            
		}

		if(!count($bookingForm['course_group'])) 
		{
			$error=array('id'=>3,'message'=>esc_html__('Cannot find group assigned to this course.','bookingo'));
			return(false);
		}	

		if(array_key_exists('course_group_id',$data) && array_key_exists($data['course_group_id'],$bookingForm['course_group']))
			$bookingForm['course_group_id']=$data['course_group_id'];
		else 
		{
			reset($bookingForm['course_group']);
			$bookingForm['course_group_id']=key($bookingForm['course_group']);
		}
		
		/****/ 

		$bookingForm['dictionary']['tax_rate']=$TaxRate->getDictionary();
		$bookingForm['dictionary']['location']=$Location->getDictionary();
		$bookingForm['dictionary']['price_rule']=$PriceRule->getDictionary();
		$bookingForm['dictionary']['payment']=$this->getPayment($bookingForm);

		/***/

		$bookingForm['booking_form_active']=$this->checkBookingFormActive($bookingForm);

		/***/

		if(!$Currency->isCurrency($currency))
			$currency=$Currency->getFormCurrency();

		if(!in_array($currency,$bookingForm['booking_form'][$bookingForm['booking_form_id']]['meta']['currency']))
			$currency=$Currency->getBaseCurrency();

		$bookingForm['currency']=$currency;

		/***/

		return($bookingForm);
	}
    
    /**************************************************************************/
    
	function checkCourseGroupActive($bookingForm,$courseGroupId)
	{
		$Booking=new BGCBSBooking();

		if(!array_key_exists($courseGroupId,$bookingForm['course_group'])) return(false);

		$participant=$Booking->getNumberParticipant($courseGroupId);

		if($participant['registered']>=$bookingForm['course_group'][$courseGroupId]['meta']['participant_number']) return(false);

		return(true);
	}
	
	/**************************************************************************/
	
	function checkBookingFormActive($bookingForm)
	{
		$Date=new BGCBSDate();
		$Validation=new BGCBSValidation();
		
		$dateStart=$bookingForm['course'][$bookingForm['course_id']]['meta']['registration_period_start_date'];
		$timeStart=$bookingForm['course'][$bookingForm['course_id']]['meta']['registration_period_start_time'];
		
		$dateEnd=$bookingForm['course'][$bookingForm['course_id']]['meta']['registration_period_end_date'];
		$timeEnd=$bookingForm['course'][$bookingForm['course_id']]['meta']['registration_period_end_time'];

		if(($Validation->isNotEmpty($dateStart)) || ($Validation->isNotEmpty($dateEnd)))
		{
			$dateTimeCurrent=date('d-m-Y H:i');
			
			if(($Validation->isNotEmpty($dateStart)))
				$dateStart=$Date->formatDateToStandard($dateStart);
			if(($Validation->isNotEmpty($dateEnd)))
				$dateEnd=$Date->formatDateToStandard($dateEnd);			
			
			$timeStart=$Date->formatTimeToStandard($timeStart);
			$timeEnd=$Date->formatTimeToStandard($timeEnd);
			
			if(($Validation->isNotEmpty($dateStart)) && ($Validation->isNotEmpty($dateEnd)))
			{
				if(!$Date->dateInRange($dateTimeCurrent,$dateStart.' '.$timeStart,$dateEnd.' '.$timeEnd))
				{
					return(-1);
				}
			}
			else if(($Validation->isNotEmpty($dateStart)))
			{
				if($Date->compareDate($dateTimeCurrent,$dateStart.' '.$timeStart)==2)
				{
					return(-2);
				}
			}
			else if(($Validation->isNotEmpty($dateEnd)))
			{
				if($Date->compareDate($dateTimeCurrent,$dateEnd.' '.$timeEnd)==1)
				{
					return(-3);
				}
			}
		}	
		
		return(1);
	}
	
	/**************************************************************************/
	
	function createPromoSection($bookingForm)
	{
		$html=null;

		$Validation=new BGCBSValidation();

		$course=$bookingForm['course'][$bookingForm['course_id']];

		if($Validation->isEmpty($course['meta']['promo_image'])) return($html);

		if($Validation->isNotEmpty($course['meta']['promo_video_embed_code']))
		{
			$html=
			'
				<div class="bgcbs-course-promo-section bgcbs-course-promo-section-video">
					<div>
						<img src="'.esc_attr($course['meta']['promo_image']).'" alt=""/>
						<span class="bgcbs-icon-meta-24-video"></span>
					</div>
					<div class="bgcbs-hidden">'.$course['meta']['promo_video_embed_code'].'</div>
				</div>
			';
		}
		else
		{
			 $html=
			'
				<div class="bgcbs-course-promo-section">
					<div>
						<img src="'.esc_attr($course['meta']['promo_image']).'" alt=""/>
					</div>
				</div>
			';           
		}

		return($html);
	}
	
	/**************************************************************************/
	
	function createCourseGroupForm($bookingForm)
	{
		$html=null;
		
		if(count($bookingForm['course_group'])<=1) return($html);
		
		foreach($bookingForm['course_group'] as $index=>$value)
		{
            $inactive = (isset($bookingForm['course_group_inactive']) && in_array($index, array_keys($bookingForm['course_group_inactive'])));
			$html.='<option value="'.esc_attr($index).'"'.BGCBSHelper::selectedIf($index,$bookingForm['course_group_id'],false).'>'.esc_html($value['post']->post_title).($inactive?esc_html__(' (full)','bookingo'):'').'</option>';
		}
		
		$html=
		'
			<div class="bgcbs-course-group-form">

				<h2>'.esc_html__('Course Group','bookingo').'</h2>
					
				<div>
					<div class="bgcbs-form-field">
						<label>'.esc_html__('Course group','bookingo').'</label>
						<select name="'.BGCBSHelper::getFormName('course_group_id',false).'">
							'.wp_kses($html,array('option'=>array('value'=>array(),'selected'=>array()))).'
						</select>
					</div>
				</div>
			
			</div>
		';
		
		return($html);
	}
	
	/**************************************************************************/
 	
	function createCourseParticipantForm($bookingForm)
	{
		$CourseFormElement=new BGCBSCourseFormElement();
				
		$course=$bookingForm['course'][$bookingForm['course_id']];
		
		$html=
		'
			<div class="bgcbs-course-participant-form">
				
				<h2>'.esc_html__('Course Participant Data','bookingo').'</h2>

				<div>
				
					<div class="bgcbs-layout-50x50 bgcbs-clear-fix">

						<div class="bgcbs-layout-column-left bgcbs-form-field">
							<label>'.esc_html__('First Name *','bookingo').'</label>
							<input type="text" name="'.BGCBSHelper::getFormName('participant_first_name',false).'"  value=""/>
						</div>

						<div class="bgcbs-layout-column-right bgcbs-form-field">
							<label>'.esc_html__('Last Name *','bookingo').'</label>
							<input type="text" name="'.BGCBSHelper::getFormName('participant_second_name',false).'"  value=""/>
						</div>

					</div>
					
					'.$CourseFormElement->createField(1,$course['meta']).'
					
				</div>
				
			</div>
		';
		
		return($html);
	}
	
 	/**************************************************************************/
 	
	function createCourseApplicantForm($bookingForm)
	{
		$CourseFormElement=new BGCBSCourseFormElement();
		
		$course=$bookingForm['course'][$bookingForm['course_id']];
		
		if((int)$course['meta']['applicant_data_enable']!==1) return('');
		
		$html=
		'
			<div class="bgcbs-course-applicant-form">
				
				<h2>'.esc_html__('Applicant Data','bookingo').'</h2>

				<div>
				
					<div class="bgcbs-layout-50x50 bgcbs-clear-fix">
					
						<div class="bgcbs-layout-column-left bgcbs-form-field">
							<label>'.esc_html__('First Name *','bookingo').'</label>
							<input type="text" name="'.BGCBSHelper::getFormName('applicant_first_name',false).'"  value=""/>
						</div>

						<div class="bgcbs-layout-column-right bgcbs-form-field">
							<label>'.esc_html__('Last Name *','bookingo').'</label>
							<input type="text" name="'.BGCBSHelper::getFormName('applicant_second_name',false).'"  value=""/>
						</div>
						
					</div>
					
					<div class="bgcbs-layout-50x50 bgcbs-clear-fix">
					
						<div class="bgcbs-layout-column-left bgcbs-form-field">
							<label>'.esc_html__('E-mail Address *','bookingo').'</label>
							<input type="text" name="'.BGCBSHelper::getFormName('applicant_email_address',false).'"  value=""/>
						</div>

						<div class="bgcbs-layout-column-right bgcbs-form-field">
							<label>'.esc_html__('Phone Number','bookingo').'</label>
							<input type="text" name="'.BGCBSHelper::getFormName('applicant_phone_number',false).'"  value=""/>
						</div>

					</div>
					
					'.$CourseFormElement->createField(2,$course['meta']).'
					
				</div>

			</div>
		';
		
		return($html);
	}	
	
	/**************************************************************************/
	
	function createCourseCustomForm($bookingForm)
	{
		$html=null;
		
		$CourseFormElement=new BGCBSCourseFormElement();
		
		$course=$bookingForm['course'][$bookingForm['course_id']];
	
		$panel=$CourseFormElement->getPanel($course['meta']);
 	 	foreach($panel as $value)
 	 	{
 	 	 	if(in_array($value['id'],array(1,2))) continue;
			
			$html.=
			'
				<div class="bgcbs-course-custom-form">

					<h2>'.esc_html($value['label']).'</h2>

					<div>		
						'.$CourseFormElement->createField($value['id'],$course['meta']).'
					</div>
				
				</div>
			';
 	 	}
		
		return($html);		
	}
	
	/**************************************************************************/
	
	function createBookingFormSchedule($bookingForm)
	{
		$html=null;

		$Date=new BGCBSDate();

		$courseGroupId=$bookingForm['course_group_id'];

		$b=array_fill(0,2,false);

		$b[0]=(is_array($bookingForm['course_group'][$courseGroupId]['meta']['schedule_week_day'])) && (count($bookingForm['course_group'][$courseGroupId]['meta']['schedule_week_day']));
		$b[1]=(is_array($bookingForm['course_group'][$courseGroupId]['meta']['schedule_date'])) && (count($bookingForm['course_group'][$courseGroupId]['meta']['schedule_date']));

		if((!$b[0]) && (!$b[1])) return;

		if($b[0])
		{
			$day=$bookingForm['course_group'][$courseGroupId]['meta']['schedule_week_day'];

			foreach($day as $value)
			{
				$html.=
				'
					<li>
						<div></div>
						<div>
							<h5>
								'.sprintf(esc_html__('Every %s','bookingo'),$Date->getDayName($value['day'])).'<br/>
								'.sprintf(esc_html__('%s - %s','bookingo'),$Date->formatTimeToDisplay($value['time_start']),$Date->formatTimeToDisplay($value['time_stop'])).'<br/>	
							</h5>
							<span class="bgcbs-icon-meta-24-repetition"></span>
						</div>
					</li>
				';
			}
		}

		if($b[1])
		{
			$date=$bookingForm['course_group'][$courseGroupId]['meta']['schedule_date'];

			foreach($date as $value)
			{
				$html.=
				'
					<li>
						<div></div>
						<div>
							<h5>
								'.esc_html($Date->formatDateToDisplay($value['date'])).'<br/>
								'.sprintf(esc_html__('%s - %s','bookingo'),$Date->formatTimeToDisplay($value['time_start']),$Date->formatTimeToDisplay($value['time_stop'])).'<br/>	
							</h5>
							<span class="bgcbs-icon-meta-24-date"></span>
						</div>
					</li>
				';
			}
		}

		$html=
		'
			<div class="bgcbs-group-schedule-section">
				<h3>'.esc_html__('Classes Schedule','bookingo').'</h3>
				<div>
					<ul class="bgcbs-list-reset">
						'.$html.'
					</ul>
				</div>
			</div>
		';

		return($html);
	}
	
	/**************************************************************************/
	
 	function createBookingFormInfo($bookingForm,$type)
 	{
		$Date=new BGCBSDate();
		$Booking=new BGCBSBooking();
		$Validation=new BGCBSValidation();
		$CourseGroup=new BGCBSCourseGroup();
		
		$course=$bookingForm['course'][$bookingForm['course_id']];
		$courseGroup=$bookingForm['course_group'][$bookingForm['course_group_id']];
		
		if($type===1)
		{
			if((int)$bookingForm['booking_form'][$bookingForm['booking_form_id']]['meta']['course_time_enable']===1)
			{
				$dateTimeStart=$Date->formatDateToDisplay($courseGroup['meta']['course_group_start_date']).' '.$Date->formatTimeToDisplay($courseGroup['meta']['course_group_start_time']);
				$dateTimeEnd=$Date->formatDateToDisplay($courseGroup['meta']['course_group_end_date']).' '.$Date->formatTimeToDisplay($courseGroup['meta']['course_group_end_time']);
			}
			else
			{
				$dateTimeStart=$Date->formatDateToDisplay($courseGroup['meta']['course_group_start_date']);
				$dateTimeEnd=$Date->formatDateToDisplay($courseGroup['meta']['course_group_end_date']);
			}
			
			$html=
			'	
				<div class="bgcbs-info-1-section">
					<ul class="bgcbs-list-reset">
						<li>
							<div>
								<span class="bgcbs-icon-meta-24-date"></span>
							</div>
							<div>
								<span>'.esc_html__('Start date','bookingo').'</span>
								<h5>'.esc_html(trim($dateTimeStart)).'</h5>
							</div>
						</li>
						<li>
							<div>
								<span class="bgcbs-icon-meta-24-date"></span>
							</div>
							<div>
								<span>'.esc_html__('End date','bookingo').'</span>
								<h5>'.esc_html(trim($dateTimeEnd)).'</h5>
							</div>
						</li>
						<li>
							<div>
								<span class="bgcbs-icon-meta-24-documents"></span>
							</div>
							<div>
								<span>'.esc_html__('Course length','bookingo').'</span>
								<h5>'.sprintf(esc_html__('%sx Lesson','bookingo'),$courseGroup['meta']['lesson_number']).'</h5>
							</div>
						</li>
						<li>
							<div>
								<span class="bgcbs-icon-meta-24-clock"></span>
							</div>
							<div>
								<span>'.esc_html__('Class duration','bookingo').'</span>
								<h5>'.sprintf(esc_html__('%s Minutes','bookingo'),$courseGroup['meta']['lesson_length']).'</h5><h5></h5>
							</div>
						</li>
					</ul>
				</div>
			';
		}
		else
		{
			$price=$CourseGroup->calculatePrice($bookingForm);
			
			$participant=$Booking->getNumberParticipant($bookingForm['course_group_id']);
			
			/***/
			
			$htmlInfo=null;
			$htmlContact=null;
			$htmlStudent=null;
			$htmlLocation=null;	
			
			/***/
			
			$htmlCourseLabel=null;
			$htmlCourseValue=null;
			
			if(count($bookingForm['course_group'])===1)
			{
            	$htmlCourseLabel=esc_html__('Course name','bookingo');
            	$htmlCourseValue=$course['post']->post_title;
            }
            else
            {
				$htmlCourseLabel=esc_html__('Course / group name','bookingo');
				$htmlCourseValue=$course['post']->post_title.' / '.$courseGroup['post']->post_title;
            }
			
			$htmlCourse=
			'
				<li>
					<div>
						<span class="bgcbs-icon-meta-24-canvas"></span>
					</div>
					<div>
						<span>'.esc_html($htmlCourseLabel).'</span>
						<h5>'.esc_html($htmlCourseValue).'</h5>
					</div>
				</li>				
			';
			
			if($Validation->isNotEmpty($courseGroup['meta']['short_info']))
			{
				$htmlInfo=
				'
					<li>
						<div>
							<span class="bgcbs-icon-meta-24-info"></span>
						</div>
						<div>
							<span>'.esc_html__('Info','bookingo').'</span>
							<h5>'.wp_kses(nl2br($courseGroup['meta']['short_info']),array('br'=>array())).'</h5>
						</div>
					</li>
				';				
			}
			
			$locationId=$courseGroup['meta']['location_id'];
			if(array_key_exists($locationId,$bookingForm['dictionary']['location']))
			{
				$location=$bookingForm['dictionary']['location'][$locationId];
				
				$data=array
				(
					'name'=>$location['post']->post_title,
					'street'=>$location['meta']['address_street'],
					'street_number'=>$location['meta']['address_street_number'],
					'postcode'=>$location['meta']['address_postcode'],
					'city'=>$location['meta']['address_city'],
					'state'=>$location['meta']['address_state'],
					'country'=>$location['meta']['address_country']
				);

				$htmlLocation=
				'
					<li>
						<div>
							<span class="bgcbs-icon-meta-24-address"></span>
						</div>
						<div>
							<span>'.esc_html__('Location','bookingo').'</span>
							<h5>'.BGCBSHelper::displayAddress($data,'<br/>').'</h5>
						</div>
					</li>
				';
			}
						
			if($Validation->isNotEmpty($courseGroup['meta']['contact_info']))
			{
				$htmlContact=
				'
					<li>
						<div>
							<span class="bgcbs-icon-meta-24-mobile"></span>
						</div>
						<div>
							<span>'.esc_html__('Contact','bookingo').'</span>
							<h5>'.wp_kses(nl2br($courseGroup['meta']['contact_info']),array('br'=>array())).'</h5>
						</div>
					</li>
				';				
			}
			
			/***/
			
			$html =
			'
				<div class="bgcbs-info-2-section">
					<!-- <div class="bgcbs-info-2-section-price">				
			';
			
			if($Validation->isNotEmpty($courseGroup['meta']['price_label_instead_price']))
			{
				$html.=
				'
						<span class="bgcbs-info-2-section-price-item-2">'.esc_html($courseGroup['meta']['price_label_instead_price']).'</span>					
				';				
			}
			else
			{
				$html.=
				'
						<span class="bgcbs-info-2-section-price-item-1">'.sprintf(esc_html__('Price (%s)','bookingo'),$price['calculate']['currency']).'</span>					
				';
				
				if((int)$courseGroup['meta']['price_net_display_enable']===1)
				{
					$html.=
					'
						<span class="bgcbs-info-2-section-price-item-2">'.esc_html($price['calculate']['net_format']).'</span>
					';					
				}
				else
				{
					$html.=
					'
						<span class="bgcbs-info-2-section-price-item-2">'.esc_html($price['calculate']['gross_format']).'</span>
					';						
				}
			}
			
			if($Validation->isNotEmpty($courseGroup['meta']['price_label_under_price']))
			{
				$html.=
				'
						<span class="bgcbs-info-2-section-price-item-3">'.esc_html($courseGroup['meta']['price_label_under_price']).'</span> -->					
				';				
			}	
			
			if((int)$courseGroup['meta']['participant_number_enable']===1)
			{
                $limitlesssports = array(
                    'Atletismo',
                    'Natación',
                    'Taekwondo',
                    'Karate Do'
                );

				$htmlStudent=
				'
					<li>
						<div>
							<span class="bgcbs-icon-meta-24-students"></span>
						</div>
						<div>
							<span>'.esc_html__('Students','bookingo').'</span>
				';

                if(!in_array($course['post']->post_title, $limitlesssports))
                {
                    $htmlStudent.='<h5>'.sprintf(esc_html__('%s enrolled of %s','bookingo'),$participant['registered'],$courseGroup['meta']['participant_number']).'</h5>';
                }
                else
                {
                    $htmlStudent.='<h5>'.sprintf(esc_html__('%s enrolled','bookingo'),$participant['registered']).'</h5>';
                }

                $groupsports = array(
                    'Baloncesto' => 9,
                    'Fútbol' => 15,
                    'Fútbol Sala' => 9,
                    'Vóleibol' => 9,
                    'Tenis de Mesa' => 1,
                    'Ajedrez' => 1
                );

                if(in_array($course['post']->post_title, array_keys($groupsports)))
                {
                    $htmlStudent.= '<h5>'.sprintf(esc_html__('Minimum required: %s','bookingo'), $groupsports[$course['post']->post_title]).'</h5>';
                }

                if(!in_array($course['post']->post_title, $limitlesssports))
                {
                    $htmlStudent.=
                    '
                        </div>
                            <div class="bgcbs-participant-number-circle" data-value="'.esc_attr((($participant['registered']/$courseGroup['meta']['participant_number']))).'">
                                <div></div>
                                <b></b>
                                <b></b>
                            </div>
                        </li>					
                    ';
                }
			}
			
			$html.=
			'
					<!-- </div> -->
					<!-- <div class="bgcbs-button bgcbs-button-style-2">
						<a href="#">'.esc_html__('Book a class','bookingo').'</a>
					</div> -->
					<ul class="bgcbs-list-reset">			
						'.$htmlStudent.'
						'.$htmlCourse.'
						'.$htmlLocation.'
						'.$htmlInfo.'
						'.$htmlContact.'
					</ul>
				</div>
			';
		}
		
		return($html);
	}
	
	/**************************************************************************/
	
 	function createBookingFormCoupon($bookingForm)
 	{
		$html=null;
		
		if((int)$bookingForm['booking_form'][$bookingForm['booking_form_id']]['meta']['coupon_enable']!==1) return($html);
		
		$html=
		'
			<div class="bgcbs-coupon-form">

				<h2>'.esc_html__('Coupon','bookingo').'</h2>

				<div>		
					<div class="bgcbs-form-field">
						<label>'.esc_html__('Coupon code','bookingo').'</label>
						<input type="text" name="'.BGCBSHelper::getFormName('coupon_code',false).'"  value=""/>					
					</div>
					<div class="bgcbs-button bgcbs-button-style-2 bgcbs-button-apply-coupon">
						<a href="#">'.esc_html__('Apply coupon','bookingo').'</a>
					</div>
				</div>

			</div>
		';		
		
		return($html);
	}
		
	/**************************************************************************/
	
	function changeGroupAjax()
	{
		$response=array();
		
		$data=BGCBSHelper::getPostOption();
		
		$bookingFormError=array();
		
		if(!is_array($bookingForm=$this->checkBookingForm($data['booking_form_id'],$data['currency'],$bookingFormError)))	
		{
			BGCBSHelper::createJSONResponse($response);
		}
		
		$response['info_1_section']=$this->createBookingFormInfo($bookingForm,1);		
		$response['info_2_section']=$this->createBookingFormInfo($bookingForm,2);
		$response['schedule_section']=$this->createBookingFormSchedule($bookingForm);
		
		BGCBSHelper::createJSONResponse($response);
	}
	
	/**************************************************************************/
	
	function applyCoupon()
	{
		$response=array();
		
		$Coupon=new BGCBSCoupon();
		
		$data=BGCBSHelper::getPostOption();
		
		$bookingFormError=array();
		
		if(!is_array($bookingForm=$this->checkBookingForm($data['booking_form_id'],$data['currency'],$bookingFormError)))	
		{
			BGCBSHelper::createJSONResponse($response);
		}
		
		if((int)$bookingForm['booking_form'][$bookingForm['booking_form_id']]['meta']['coupon_enable']!==1)
		{
			BGCBSHelper::createJSONResponse($response);
		}
		
		if(!$Coupon->checkCode($bookingForm))
		{
			$response=array('error'=>1,'message'=>esc_html__('Coupon is not valid.','bookingo'));
			BGCBSHelper::createJSONResponse($response);
		}
		
		$response['error']=0;
		$response['message']=esc_html__('Coupon is valid. Discount has been granted.','bookingo');
		
		$response['info_2_section']=$this->createBookingFormInfo($bookingForm,2);
		
		BGCBSHelper::createJSONResponse($response);
	}
	
	/**************************************************************************/
	
	function validate($bookingForm,&$data)
	{
 	 	$response=array();
		
		$Validation=new BGCBSValidation();
 	 	$CourseAgreement=new BGCBSCourseAgreement();
		$CourseFormElement=new BGCBSCourseFormElement();
        $Booking=new BGCBSBooking();
		
		/***/
		
		if($Validation->isEmpty($data['participant_first_name']))
			$this->setErrorLocal($response,BGCBSHelper::getFormName('participant_first_name',false),esc_html__('Enter participant first name.','bookingo'));
		if($Validation->isEmpty($data['participant_second_name']))
			$this->setErrorLocal($response,BGCBSHelper::getFormName('participant_second_name',false),esc_html__('Enter participant last name.','bookingo')); 	 	 	 	  
		
		/***/
		
		$course=$bookingForm['course'][$bookingForm['course_id']];
		if((int)$course['meta']['applicant_data_enable']===1)
		{
			if($Validation->isEmpty($data['applicant_first_name']))
				$this->setErrorLocal($response,BGCBSHelper::getFormName('applicant_first_name',false),esc_html__('Enter applicant first name.','bookingo'));
			if($Validation->isEmpty($data['applicant_second_name']))
				$this->setErrorLocal($response,BGCBSHelper::getFormName('applicant_second_name',false),esc_html__('Enter applicant last name.','bookingo')); 	  
			if(!$Validation->isEmailAddress($data['applicant_email_address']))
				$this->setErrorLocal($response,BGCBSHelper::getFormName('applicant_email_address',false),esc_html__('Enter valid applicant e-mail address.','bookingo')); 	 
		}
		
		/***/
		
 	 	$error=$CourseFormElement->validate($bookingForm,$data);
 	 	foreach($error as $errorValue)
			$this->setErrorLocal($response,$errorValue['name'],$errorValue['message_error']); 
		
		/***/
		
        $participant = $Booking->getNumberParticipant($bookingForm['course_group_id']);
        $participant_number = (int)$bookingForm['course_group'][$bookingForm['course_group_id']]['meta']['participant_number'];
        if ($participant['registered'] >= $participant_number)
            $this->setErrorGlobal($response,esc_html__('This course group is full.','bookingo'));

		$error=$CourseAgreement->validate($bookingForm,$data);
		if($error)
			$this->setErrorGlobal($response,esc_html__('Approve all agreements.','bookingo'));  
		
		/***/
		
		if(isset($response['error']))
			BGCBSHelper::createJSONResponse($response);
		
		/***/
		
		return(true);
	}
	
	/**************************************************************************/
	
 	function setErrorLocal(&$response,$field,$message)
 	{
 	 	if(!isset($response['error']))
 	 	{
 	 	 	$response['error']['local']=array();
 	 	 	$response['error']['global']=array();
 	 	}
 	 	
 	 	array_push($response['error']['local'],array('field'=>$field,'message'=>$message));
 	}
 	
 	/**************************************************************************/
 	
 	function setErrorGlobal(&$response,$message)
 	{
 	 	if(!isset($response['error']))
 	 	{
 	 	 	$response['error']['local']=array();
 	 	 	$response['error']['global']=array();
 	 	}
 	 	
 	 	array_push($response['error']['global'],array('message'=>$message));
 	}
	
	/**************************************************************************/
	
	function getPayment($bookingForm)
	{
		$Payment=new BGCBSPayment();
		$Validation=new BGCBSValidation();

		$payment=array();
		
		$dictionary=$Payment->getDictionary();
		
		$course=$bookingForm['course'][$bookingForm['course_id']];
		
		if(!is_array($course['meta']['payment_id'])) return($payment);
		
		if(in_array(-1,$course['meta']['payment_id'])) return($payment);
		
		foreach($dictionary as $index=>$value)
		{
			switch($value['meta']['payment_type'])
			{
				case 2:
					
					if(($Validation->isEmpty($value['meta']['payment_stripe_api_key_secret'])) || ($Validation->isEmpty($value['meta']['payment_stripe_api_key_publishable'])))
					{
						unset($dictionary[$index]);
					}
					
				break;
			
				case 3:
					
					if($Validation->isEmpty($value['meta']['payment_paypal_email_address']))
					{
						unset($dictionary[$index]);
					}
					
				break;
			}
		}
		
		foreach($course['meta']['payment_id'] as $index=>$value)
		{
			if(array_key_exists($value,$dictionary))
			{
				$payment[$value]=$dictionary[$value];
			}
		}
		
		return($payment);
	}
	
 	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/