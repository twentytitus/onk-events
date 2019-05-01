<?php
// parse_str($_SERVER['QUERY_STRING'], $query_string);

$old_day = 0;
$old_time = -1;
foreach ($result as $row)
{
	$query_string = [];
	$query_string['id'] = (integer) $row->id;
	$details_link = http_build_query($query_string);
	
	$hour =	date( 'G', strtotime( htmlspecialchars($row->time_start) ) );

	if ($hour != $old_time && $old_time != -1) {
		$o .= '</div>';
		$o .= '</div>';
	}

	if ($row->day != $old_day) {
		$o .= '<div class="onk_day_header">' 
			// FIXME: There is a better way to do this
	                . strftime( '%A, %e. %B', strtotime( htmlspecialchars($days[(integer) $row->day]->date) ) )
			. '</div>';
		$day_class = 'onk_general_day' . htmlspecialchars($row->day);
	}	

	if ($hour != $old_time) {
		$o .= '<div class="onk_outerbox">';
		$o .= '<div class="onk_timebox">';
		$o .= $hour . ' Uhr';
		$o .= '</div>';
		$o .= '<div class="onk_flex">';
	}

	$o .= '<a class="onk_eventlink" href="' . $base_uri . '?' . $details_link . '">';
	$o .= '<div class="onk_general ' . $day_class . '">';

	$o .= '<p class="onk_line">';

	$o .= "<span class='onk_properties'>";
	if ( $row->wheelchair == 1 ) {
		$o .= "<span title='barrierefrei'>&#9855;</span>";
	} else if ( $row->wheelchair == 2 ) {
		$o .= "<span title='eingeschränkt barrierefrei' class='limited_accessibility'>&#9855;</span>";
	}
	if ( $row->kids == 1 ) {
		$o .= "<span title='kinderfreundlich'>&#127880;</span>";
	}
	$o .= "</span>";

	$o .= '<span class="onk_eventname">';
	$o .= onk2019_shortstring(htmlspecialchars($row->name), 75);
	$o .= '</span>';
	$o .= '</p>';
	
	$o .= '<p class="onk_line">';
	$o .= '<span class="onk_organiser">';
	$o .= onk2019_shortstring(htmlspecialchars($row->organiser), 60);
	$o .= '&nbsp;</span>';
	$o .= '</p>';	
	
	$o .= '<div class="onk_number onk_largenumber">' . (integer) $row->id . '</div>';

	$o .= '<p class="onk_time">';
	if ( $row->day == 0 ) {
		$o .= 'Do, 23. Mai, ';
	}
	if ( $row->time_start != '00:00:00' ) {
		$o .= date( 'G:i', strtotime( htmlspecialchars($row->time_start) ) ) . ' Uhr';
		if ( $row->time_end != '00:00:00' ) {
			$o .= '<span class="onk_time_end">';
			$o .= ' – ' . date( 'G:i', strtotime( htmlspecialchars($row->time_end) ) ) . ' Uhr';
			$o .= '</span>';
		}
	} else {
		$o .= '<span class="onk_minitext">Tag & Nacht</span>';
	}
	$o .= '</p>';

	$o .= '</div>';

	$o .= '</a>';
	
	$old_day = $row->day;
	$old_time = $hour;
}
/* end of last hour boxes */
$o .= '</div>';
$o .= '</div>';
?>
