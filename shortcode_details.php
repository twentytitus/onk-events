<?php	
$row = $wpdb->get_results( "SELECT * FROM $table_name WHERE id = " . $atts['id'] );
$row = $row[0];
$days = $wpdb->get_results( "SELECT day, date, name FROM $day_table_name ORDER BY day" );
$categories = $wpdb->get_results( "SELECT id, name FROM $category_table_name WHERE id IN (" .
	htmlspecialchars($row->categories) . ") ORDER BY name" );

# count the visit to this page
if (!is_user_logged_in()) {
	$wpdb->insert($count_table_name, array(
		'event' => $row->id,
		'date' => current_time('Y-m-d')
	), array('%d', '%s'));
}

if ( empty( $row ) ) {
	$o .= '<div>Veranstaltung nicht gefunden.<br>&nbsp;<br>'
		. 'Bleib neugierig und schau im <a href="' . $base_uri . '">Programm</a> '
		. 'nach wirklich existierenden Veranstaltungen!</div>';
}
else {
	$o .= '<div class="onk_details">';

	$o .= '<p class="onk_details_number">#' . (integer) $row->id . '</p>';	

	$o .= '<p class="onk_details_link_back" style="text-align: left;">';
	$o .= '<a href="' . $base_uri . '">&lt; Alle Veranstaltungen</a>';
	$o .= '</p>';

	$o .= '<h3 class="onk_details_eventname">';
	$o .= htmlspecialchars($row->name);
	$o .= '</h3>';
	
	if (sizeof($categories) > 0) {
		$o .= '<p class="onk_details_categories">';
		$category_fun = function($entry) {
			// return '<img src="' . plugin_dir_url(__FILE__) . '/icons/ONK_Programm_Icons_' . (integer) $entry->id . '.png"> '
			// 	. htmlspecialchars($entry->name);
			return $entry->name;
		};
		$o .= implode(' &nbsp; ', array_map($category_fun, $categories));
		$o .= '</p>';
	}

	$o .= '<p class="onk_details_properties">&nbsp;';
        if ($row->kids == 1) {
		$o .= '&#127880; kinderfreundlich';
        }
        if ($row->wheelchair == 1) {
		$o .= ' &#9855; barrierefrei';
        }
        if ($row->wheelchair == 2) {
		$o .= ' <span class="limited_accessibility">&#9855;</span> eingeschränkt barrierefrei';
        }
	$o .= '</p>';

	$o .= '<p class="onk_details_organiser">';
	$o .= 'Veranstalter*in: ';
	if (!empty ($row->link_organiser))
		$o .= '<a href="' . htmlspecialchars($row->link_organiser) . '" target="blank">'
			. htmlspecialchars($row->organiser) . '</a>';
	else
		$o .= htmlspecialchars($row->organiser);
	$o .= '</p>';

	$o .= '<p class="onk_details_when">';
	// FIXME: There is a better way to get the day's index
	$o .= strftime( '%A, %e. %B', strtotime( htmlspecialchars($days[(integer) $row->day]->date) ) );
	if ( $row->time_start != '00:00:00' ) {
		$o .= ' ' . date( 'G:i', strtotime( htmlspecialchars($row->time_start) ) );
		if ( $row->time_end != '00:00:00' ) {
			$o .= ' - ' . date( 'G:i', strtotime( htmlspecialchars($row->time_end) ) );
		}
		$o .= ' Uhr';
	}
	$o .= '</p>';
	
	$o .= '<p class="onk_details_where">';
	$o .= htmlspecialchars($row->place);
	if ($row->address != "") {
		$o .= ', ' . htmlspecialchars($row->address);
	}
	if ($row->coord1 != "0.0000" & $row->coord2 != 0.0000) {
		$maplink = 'https://www.openstreetmap.org/?mlat='
			. htmlspecialchars($row->coord1) . '&mlon=' . htmlspecialchars($row->coord2) . '#map=16/'
			. htmlspecialchars($row->coord1) . '/' . htmlspecialchars($row->coord2);
		$o .= '<noscript><br><a href="' . $maplink . '" target="blank">Karte</a></noscript>';
	}
	$o .= '</p>';

	if (!empty ($row->description)) {
		$o .= '<p class="onk_details_description">';
		$o .= preg_replace(					# no escaping!
			'/(\S+)@(\S+)\.(\S+)/',
			'\1 [at] \2 [punkt] \3',
			preg_replace('/\n/', '<br>', $row->description)
		);
		$o .= '</p>';
	}

	if (!empty ($row->additional)) {
		$o .= '<p class="onk_details_additional">';
		$o .= preg_replace(					# no escaping!
			'/(\S+)@(\S+)\.(\S+)/',
			'\1 [at] \2 [punkt] \3',
			preg_replace('/\n/', '<br>', $row->additional)
		);
		$o .= '</p>';
	}

	if (!empty ($row->link_event) || !empty ($row->link_social)) {
		$o .= '<p class="onk_details_link_box">';
		if (!empty ($row->link_event)) {
			$o .= '<span class="onk_details_link_event">';
			$o .= '<a href="' . htmlspecialchars($row->link_event) . '" target="blank">Mehr Informationen</a></td>';
			$o .= '</span>';
		} else {
			$o .= '&nbsp;';
		}
		if (!empty ($row->link_social)) {
			$o .= '<span class="onk_details_link_social">';
			$o .= '<a href="' . htmlspecialchars($row->link_social) . '" target="blank">Facebook</a></td>';
			$o .= '</span>';
		}
		$o .= '</p>';
	}
	
	echo $o;
	if ($row->coord1 != "0.0000" & $row->coord2 != 0.0000) {
		
		if      ($row->day == 1) { $icon_class = 'onk_map_icon_day1'; }
		else if ($row->day == 2) { $icon_class = 'onk_map_icon_day2'; }
		else if ($row->day == 3) { $icon_class = 'onk_map_icon_day3'; }
		else {                     $icon_class = 'onk_map_icon';      }
?>

		<link rel="stylesheet" href="<?php echo plugins_url($plugin = "onk2019"); ?>/leaflet/leaflet.css" />
		<script type="text/javascript" src="<?php echo plugins_url($plugin = "onk2019"); ?>/leaflet/leaflet.js"></script>
		<script type="text/javascript" src="<?php echo plugins_url($plugin = "onk2019"); ?>/map.js"></script>
	
		<script type="text/javascript">
		<!--
		document.write('<div id="mapid"></div>');
		coords = [ <?php echo $row->coord1; ?> , <?php echo $row->coord2; ?> ];
		day = <?php echo $row->day; ?>;
		mymap = add_map().setView(coords, 17);
                var myIcon = L.divIcon({html: '<?php echo "<div class=" . $icon_class .
			"><p class=onk_map_icon_text>" . $row->id . "</p></div>"; ?>', className: 'onk_map_icon_wrapper'});
		var marker = L.marker(coords, {icon: myIcon}).addTo(mymap);
		document.write('<p class="onk_details_where">');
		document.write('<?php echo htmlspecialchars($row->address); ?>');
		document.write(' <a href="<?php echo $maplink; ?>" target="blank">(große Karte)</a>');
		document.write('</p>');
		//-->
		</script>
		
<?php
	}
	$o = "";
	
	$o .= '</div>';

	$o .= '<p class="onk_details_link_back">';
	$o .= '<br />&nbsp;<br /><a href="' . $base_uri . '">&lt; Alle Veranstaltungen</a>';
	$o .= '</p>';
}	
?>
