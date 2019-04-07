<link rel="stylesheet" href="/wp-content/plugins/onk2019/leaflet/leaflet.css" />
<script type="text/javascript" src="/wp-content/plugins/onk2019/leaflet/leaflet.js"></script>
<script type="text/javascript" src="/wp-content/plugins/onk2019/map.js"></script>

<noscript><br>Um die Veranstaltungen auf einer interaktiven Karte zu sehen, musst du leider Javascript in deinem Browser aktivieren.</noscript>

<script type="text/javascript">
<!--
document.write('<div id="mapid"></div>');
mymap = add_map();
// var zoom = mymap.getZoom();
//-->
</script>

<?php
$markers = "";
$i = 1;
foreach ($result as $row)
{
	if ($row->coord1 != "0.0000" && $row->coord2 != 0.0000) {
		if      ($row->day == 1) { $icon_class = 'onk_map_icon_day1'; $div_class = 'day1'; }
		else if ($row->day == 2) { $icon_class = 'onk_map_icon_day2'; $div_class = 'day2'; }
		else if ($row->day == 3) { $icon_class = 'onk_map_icon_day3'; $div_class = 'day3'; }
		else {                     $icon_class = 'onk_map_icon';      $div_class = '';     }
                $markers .= "var myIcon = L.divIcon({" .
				"html: '<div class=" . $icon_class . "><p class=onk_map_icon_text>" . $row->id . "</p></div>', " .
				"className: 'onk_map_icon_wrapper' }); \n";
		$markers .= "var marker" . $i . " = L.marker([" . $row->coord1 . ", " . $row->coord2 . "], {icon: myIcon}).addTo(mymap); \n";
		
		$s = "<div class='onk_map " . $div_class . "'>";
		
		$query_string = [];
		$query_string['id'] = $row->id;
		$details_link = http_build_query($query_string);
		$s .= "<a class='onk_eventlink' href='" . $base_uri . "?" . $details_link . "'>";
	
		$s .= "<div class='onk_eventname'>";
		$s .= htmlspecialchars($row->name);
		$s .= "</div>";
		
		$s .= "<div class='onk_organiser'>";
		$s .= htmlspecialchars($row->organiser);
		$s .= "&nbsp;</div>";
	
		$s .= "<div class='onk_organiser'>";
		$s .= htmlspecialchars($row->address);
		$s .= "&nbsp;</div>";
	
		$s .= "<div class='onk_when'>";
		$s .= strftime( '%A, %e. %B', strtotime( htmlspecialchars($days[(integer) $row->day-1]->date) ) );
			if ( $row->time_start != '00:00:00' ) {
				$s .= ' ' . date( 'G:i', strtotime( htmlspecialchars($row->time_start) ) );
				if ( $row->time_end != '00:00:00' ) {
					$s .= ' - ' . date( 'G:i', strtotime( htmlspecialchars($row->time_end) ) );
				}
			$s .= ' Uhr';
		}
		$s .= "</div>";
		
		$s .= '</a>';

		$s .= "</div>";

		$markers .= 'marker' . $i . '.bindPopup("' . $s . '");' . "\n";
		/* $markers .= 'marker' . $i . '.bindTooltip("' . $row->id . '", {opacity: 0});' . "\n"; */
		$i++;
	}
}
$markers .= "var myIcon = L.divIcon({" .
		"html: '<div class=onk_map_icon_info><p class=onk_map_icon_text>&#x2139;</p></div>', " .
		"className: 'onk_map_icon_wrapper' }); \n";
$markers .= "var marker_info = L.marker([52.47918, 13.43766], {icon: myIcon}).addTo(mymap); \n";
$markers .= 'marker_info.bindPopup("<div class=\"onk_map info\"><div class=onk_eventname>Offenes-Neukölln-Infostand</div><div class=onk_organiser>Das ganze Wochenende stehen wir vor dem Rathaus Neukölln und freuen uns, mit euch ins Gespräch zu kommen.</div><div class=onk_organiser>Es gibt Musik, Getränke und jede Menge Infomaterial rund um das Festival und die beteiligten Akteur*innen.</div><div class=onk_when>Fr, 24. Mai 15-20 Uhr<br>Sa, 25. Mai 10-20 Uhr<br>So, 26. Mai 11-18 Uhr</div></div>");' . "\n";

echo '<script type="text/javascript">';
echo "<!--\n";
echo $markers;
echo "//-->\n";
echo '</script>';
?>
