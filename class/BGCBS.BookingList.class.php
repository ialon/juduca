<?php

/******************************************************************************/
/******************************************************************************/

class BGCBSBookingList
{
	/**************************************************************************/
	
	function __construct($post)
	{
		$this->post = $post;

        $CourseFormElement=new BGCBSCourseFormElement();
        $this->customOptions = $CourseFormElement->customOptions;
        $this->eventsports = array(
            'Ajedrez',
            'Atletismo',
            'Taekwondo',
            'Karate Do',
            'Natación'
        );
	}
	
	/**************************************************************************/
	
	function generate()
	{
        $html =
        '<table>
            <thead>
                <tr>
                    <th>' . esc_html__( 'Name.', 'bookingo' ) . '</th>
                    <th>' . esc_html__( 'Last name.', 'bookingo' ) . '</th>
                    <th>' . esc_html__( 'ID.', 'bookingo' ) . '</th>
                    <th>' . esc_html__( 'Course group.', 'bookingo' ) . '</th>';

        if(in_array($this->post->post_title, $this->eventsports))
        {
            $html .= '<th>' . esc_html__( 'Events.', 'bookingo' ) . '</th>';
        }

        $html .=
                    '<th>' . esc_html__( 'Documents.', 'bookingo' ) . '</th>
                </tr>
            </thead>
        ';

        $html .=
        '
            <tbody>
        ';

        $bookings = $this->getBookings();
        foreach($bookings as $booking)
        {
            $html .=
            '
                <tr>
                    <th>' . $booking['firstname'] . '</th>
                    <th>' . $booking['lastname'] . '</th>
                    <th>' . $booking['idnumber'] . '</th>
                    <th>' . $booking['category'] . '</th>';

            if(in_array($this->post->post_title, $this->eventsports))
            {
                $html .= '<th>' . ($booking['events'] ?? '') . '</th>';
            }

            $html .=
            '
                    <th>' . $booking['documents'] . '</th>
                </tr>
            ';
        }

        $html .=
        '
            </tbody>
        </table>';

        return $html;
	}
	
	/**************************************************************************/
	
	function getBookings()
	{
        global $wpdb, $user_identity;

        $bookings = [];

        $university = null;
        if(!current_user_can('administrator'))
        {
            $university = ($wpdb->get_row($wpdb->prepare("SELECT company_name FROM {$wpdb->prefix}swpm_members_tbl WHERE user_name = %d", $user_identity)))->company_name;
        }

        $allbookings = $wpdb->get_results($wpdb->prepare("SELECT post_id FROM {$wpdb->prefix}postmeta WHERE meta_key = 'bgcbs_course_id' AND meta_value = %d", $this->post->ID));

        foreach($allbookings as $allbooking)
        {
            $metadata = BGCBSPostMeta::getPostMeta($allbooking->post_id);

            if (empty($metadata) || !isset($metadata['applicant_first_name'])) {
                continue;
            }

            $booking = [];
            $documents = [];

            $booking['firstname'] = $metadata['applicant_first_name'];
            $booking['lastname'] = $metadata['applicant_second_name'];
            $booking['category'] = $metadata['course_group_name'];

            foreach ($metadata['form_element_field'] as $elementfield) {
                if ($elementfield['label'] == 'Universidad')
                    $booking['university'] = $elementfield['value'];
                if ($elementfield['label'] == 'Documento de viaje') {
                    $documenttype = '<strong>' . $elementfield['value'] . ':</strong><br>';
                    $booking['idnumber'] = $documenttype . ($booking['idnumber'] ?? '');
                }
                if ($elementfield['label'] == 'No. de Documento de viaje')
                    $booking['idnumber'] = ($booking['idnumber'] ?? '') . $elementfield['value'];

                if ((int)$elementfield['field_type']===6)
                    $documents[$elementfield['value']] = $elementfield['label'];
            }

            if ($university && $booking['university'] != $university) {
                continue;
            }

            $booking['events'] = $this->getEventList($metadata);

            $booking['documents'] = '<ul>';
            foreach($documents as $id => $document)
                $booking['documents'] .= '<li><a href="' . wp_get_attachment_url($id) . '" target="_blank">' . esc_html__($document) . '</a></li>';
            $booking['documents'] .= '</ul>';

            $bookings[] = $booking;
        }

        return $bookings;
	}

    /**************************************************************************/

    function getEventList($metadata) {
        if(in_array($this->post->post_title, $this->eventsports))
        {
            $eventhtml = '';

            switch($this->post->post_title)
            {
                case 'Ajedrez':
                    $equipo = [];
                    if(!empty($metadata['equipo_de_ajedrez']))
                        $equipo = explode(';', $metadata['equipo_de_ajedrez']);
                    if (count($equipo) > 0) $eventhtml .= '<strong>Equipo:</strong>';
                    foreach ($equipo as $item) {
                        if ($item) $eventhtml .= '<li>' . $item . '</li>';
                    }

                    $individual = [];
                    if(!empty($metadata['torneo_individual']))
                        $individual = explode(';', $metadata['torneo_individual']);
                    if (count($individual) > 0) $eventhtml .= '<strong>Torneo individual:</strong>';
                    foreach ($individual as $item) {
                        if ($item) $eventhtml .= '<li>' . $item . '</li>';
                    }

                    break;

                case 'Karate Do':
                    $kumite = [];
                    if(!empty($metadata['kumite']))
                        $kumite = explode(';', $metadata['kumite']);
                    if (count($kumite) > 0) $eventhtml .= '<strong>Kumite:</strong>';
                    foreach ($kumite as $item) {
                        if ($item) $eventhtml .= '<li>' . $item . '</li>';
                    }

                    $equipo = [];
                    if(!empty($metadata['kata_individual']))
                        $equipo = explode(';', $metadata['kata_individual']);
                    if (count($equipo) > 0) $eventhtml .= '<strong>Kata individual:</strong>';
                    foreach ($equipo as $item) {
                        if ($item) $eventhtml .= '<li>' . $item . '</li>';
                    }

                    $individual = [];
                    if(!empty($metadata['kata_equipo']))
                        $individual = explode(';', $metadata['kata_equipo']);
                    if (count($individual) > 0) $eventhtml .= '<strong>Kata por equipo:</strong>';
                    foreach ($individual as $item) {
                        if ($item) $eventhtml .= '<li>' . $item . '</li>';
                    }

                    break;
            }

            $html = '';
            if ($eventhtml)
                $html = '<ul>' . $eventhtml . '</ul>';

            return $html;
        }
    }

	/**************************************************************************/
	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/