<?php

/******************************************************************************/
/******************************************************************************/

class BGCBSBookingList
{
	/**************************************************************************/
	
	function __construct($post)
	{
		$this->post = $post;
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

        $eventsports = array(
            'Atletismo',
            'Taekwondo',
            'Karate Do',
            'Natación'
        );

        if(in_array($this->post->post_title, $eventsports))
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

            if(in_array($this->post->post_title, $eventsports))
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
            $metadata = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT pm.*
                    FROM {$wpdb->prefix}postmeta pm
                    JOIN {$wpdb->prefix}posts p ON p.ID = pm.post_id AND p.post_type = 'bgcbs_booking'
                    WHERE post_id = %d",
                    $allbooking->post_id
                )
            );

            if (empty($metadata)) {
                continue;
            }

            $booking = [];
            $documents = [];

            foreach($metadata as $data)
            {
                switch($data->meta_key) {
                    case 'bgcbs_applicant_first_name':
                        $booking['firstname'] = $data->meta_value;
                        break;
                    case 'bgcbs_applicant_second_name':
                        $booking['lastname'] = $data->meta_value;
                        break;
                    case 'bgcbs_course_group_name':
                        $booking['category'] = $data->meta_value;
                        break;
                    case 'bgcbs_form_element_field':
                        $elementfields = unserialize($data->meta_value);
                        foreach($elementfields as $elementfield)
                        {
                            if ($elementfield['label'] == 'Universidad')
                                $booking['university'] = $elementfield['value'];
                            if ($elementfield['label'] == 'No. de Documento de viaje')
                                $booking['idnumber'] = $elementfield['value'];
                            if ((int)$elementfield['field_type']===6)
                                $documents[$elementfield['value']] = $elementfield['label'];
                        }
                        break;
                }
            }

            if ($university && $booking['university'] != $university) {
                continue;
            }

            $booking['events'] = '100m, 200m, 300m';
            $booking['documents'] = '<ul>';
            foreach($documents as $id => $document)
            {
                $booking['documents'] .= '<li><a href="' . wp_get_attachment_url($id) . '" target="_blank">' . esc_html__($document) . '</a></li>';
            }
            $booking['documents'] .= '</ul>';

            $bookings[] = $booking;
        }

        return $bookings;
	}
	
	/**************************************************************************/
	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/