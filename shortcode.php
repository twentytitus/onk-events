<?php
function onk2019_shortstring($str, $len = 58)
{
	if ( mb_strlen($str) > $len)
		return '<span title="' . $str . '">' . mb_substr ( $str, 0, $len-3, 'utf-8' ) . '...</span>';
	else
		return $str;
}

function onk2019_shortcode($atts = [])
{
	$atts = array_change_key_case((array)$atts, CASE_LOWER);
	if ( isset($_GET['id']) ) {
		$the_id = (integer) $_GET['id'];
	} else {
		$the_id = -1;
	}
	$atts = $wporg_atts = shortcode_atts([ 
		'id' => $the_id,
		'view' => preg_replace("/[^a-zA-Z]/", "", $_GET['view'])
	], $atts, $tag);
	if (empty($atts['view'])) { $atts['view'] = 'list'; }

	global $wpdb;
	$table_name = $wpdb->prefix . 'onk2019';
	$category_table_name = $wpdb->prefix . 'onk2019_categories';
	$count_table_name = $wpdb->prefix . 'onk2019_counter';
	$day_table_name = $wpdb->prefix . 'onk2019_days';
	
	preg_match('/([^?]*).*/', $_SERVER["REQUEST_URI"], $base_uri);
	$base_uri = $base_uri[1];
	$query_string = '';
	parse_str($_SERVER['QUERY_STRING'], $query_string);
	
	$o = '<link rel="stylesheet" href="' . plugin_dir_url(__FILE__) . 'stylesheet.css" type="text/css">';
		
	if ($atts['id'] == -1) {

		$sql_where = [];
		if (isset($_GET['dayselect']) && (integer) $_GET['dayselect'] != 0) {
			$the_day = (integer) $_GET['dayselect'];
			$sql_where[] = 'day = ' . $the_day;
		}
		if (isset($_GET['from']) & $_GET['from'] != "0") {
			// preg_match('/[0-2][0-9][\.:][0-5][0-9]/', $_GET['from'], $the_from);
			// $the_from = preg_replace('/\./', ':', $the_from[0]);
			$the_from_hour = (integer) $_GET['from'];
			$the_from = $the_from_hour . ':00';
			$sql_where[] = "(time_start >= '" . $the_from . "'" . 
				" OR time_end > '" . $the_from . "'" .  // still running
				" OR (time_end <= '08:00' AND time_start < '" . $the_from . "' AND time_end < time_start))"; // runs past midnight
		}
		if (isset($_GET['category']) & (integer) $_GET['category'] != 0) {
			$the_cat = (integer) $_GET['category'];
			$sql_where[] = "(categories = '" . $the_cat . "'"
					. " OR categories LIKE '" . $the_cat . ",%'"
					. " OR categories LIKE '%," . $the_cat . ",%'"
					. " OR categories LIKE '%," . $the_cat . "')";
		}
		if (isset($_GET['onlykids'])) {
			$the_onlykids = (integer) $_GET['onlykids'];
			$sql_where[] = "kids = 1";
		}
		if (isset($_GET['onlywheelchair'])) {
			$the_onlywheelchair = (integer) $_GET['onlywheelchair'];
			$sql_where[] = "wheelchair IN (1, 2)";
		}
		if (isset($_GET['search']) & $_GET['search'] != "") {
			$the_search_NOT_ESCAPED = $_GET['search'];
			$search_fields = Array('name', 'organiser', 'description', 'address');
			$sql_search = '';
			$first_search = 1;
			foreach ($search_fields as $f) {
				if ($first_search) {
					$sql_search .= "(" . $f . " LIKE '%" . esc_sql($the_search_NOT_ESCAPED) . "%') ";
					$first_search = 0;
				} else {
					$sql_search .= "OR (" . $f . " LIKE '%" . esc_sql($the_search_NOT_ESCAPED) . "%') ";
				}
			}
			$sql_where[] = $sql_search;
		}

		$o .= '<div class="onk_filter">';
		$o .= '<form action="' . $base_uri . '" method="get" name="onkEventFilter">';

		$o .= '<input type="hidden" name="view" value="' . $atts['view'] . '">';

		$days = $wpdb->get_results( "SELECT day, date, name FROM $day_table_name ORDER BY day" );

		$o .= '<select name="dayselect" class="dayselect">';
		$o .= '<option value="" ' . (!isset($the_day) ? "selected" : "") . '><b>Alle Tage</b></option>';
		foreach (array(1, 2, 3) as $day) {
			$o .= '<option value="' . $day . '" ' . ($day == $the_day ? "selected" : "") . '>'
	                        . strftime( '%a, %e. %b', strtotime( htmlspecialchars($days[$day]->date) ) )
				. '</option>';
		}
		$o .= '</select>';

		/* $o .= '<label>ab <input type="number" name="from" min="0", max="23" value="' .
			(isset($the_from) ? $the_from_hour : "0") . '" /> Uhr</label>'; */

		$o .= '<select name="from" class="timeselect">';
		$o .= '<option value="0" ' . (!isset($the_from) || $the_from == 0 ? "selected" : "") . '><b>Alle Zeiten</b></option>';
		foreach (array(10, 12, 14, 16, 18, 20) as $hour) {
			$o .= '<option value="' . $hour . '" ' . ($hour == $the_from_hour ? "selected" : "") . '>'
				. 'ab ' . $hour . ' Uhr' . '</option>';
		}
		$o .= '</select>';

		$cats = $wpdb->get_results( "SELECT id, name FROM $category_table_name" );
		$o .= '<select name="category" class="categoryselect">';
		$o .= '<option value="" ' . (!isset($the_cat) ? "selected" : "") . '><b>Alle Kategorien</b></option>';
		foreach ($cats as $cat) {
			$o .= '<option value="' . $cat->id . '" ' . ($cat->id == $the_cat ? "selected" : "") . '>'
				. $cat->name . '</option>';
		}
		$o .= '</select>';
		$o .= '<br />';
		$o .= '<span style="font-size: 70%">';
		$o .= '<label class="property">' .
			'<input type="checkbox" name="onlykids" value="1"' .
			($the_onlykids ? " checked" : "") . '/>&#127880; kinderfreundlich' .
			'</label>';
		$o .= '<label class="property">' .
			'<input type="checkbox" name="onlywheelchair" value="1"' .
			($the_onlywheelchair ? " checked" : "") . '/>&#9855; barrierefrei' .
			'</label>';
		$o .= '</span>';
		$o .= '<br />';
		$o .= '<input type="submit" value="Filtern">';
		$o .= '</form>';
		if (count($sql_where) > 0) {
			$o .= '<form action="' . $base_uri . '" method="get" name="onkEventFilterReset">';
			$o .= '<input type="hidden" name="view" value="' . $atts['view'] . '">';
			$o .= '<input type="submit" value="(x) alle anzeigen" class="onk_reset">';
			$o .= '</form>';
		}
		$o .= '</div>';

		$o .= '<div class="onk_chooseview">';
		$query_string['view'] = 'list';
		$o .= '<a href="' . $base_uri . '?' . http_build_query($query_string) . '">';
		$o .= '<div' . ($atts['view'] == 'list' ? ' class="onk_chosen"' : '') . '>';
		$o .= 'Liste';
		$o .= '</div>';
		$o .= '</a>';
		$query_string['view'] = 'map';
		$o .= '<a href="' . $base_uri . '?' . http_build_query($query_string) . '">';
		$o .= '<div' . ($atts['view'] == 'map' ? ' class="onk_chosen"' : '') . '>';
		$o .= 'Karte';
		$o .= '</div>';
		$o .= '</a>';
		$o .= '<div>';
		$o .= '</div>';
		$o .= '<div class="onk_search">';
		$o .= '<form action="' . $base_uri . '" method="get" name="onkEventSearch">';
		$o .= '<input type="search" name="search" placeholder="Suche" value="' . (isset($the_search_NOT_ESCAPED) ? stripslashes(htmlspecialchars($the_search_NOT_ESCAPED)) : "") . '">';
		$o .= '<input type="submit" value="&#x1F50D;">';
		$o .= '</form>';
		$o .= '</div>';
		$o .= '</div>';
		
		$first_sql = 1;
		$sql_where_str = '';
		foreach ($sql_where as $s_w) {
			if ($first_sql == 1)  {
				$sql_where_str .= ' WHERE (' . $s_w . ') ';
				$first_sql = 0;
			} else { 
				$sql_where_str .= 'AND (' . $s_w . ') ';
			}
		}
		$sql_query = "SELECT * FROM $table_name AS events " .
			$sql_where_str . " ORDER BY day, time_start, time_end, id ASC";
		// echo $sql_query; 

		$result = $wpdb->get_results( $sql_query ); 

		if ( empty( $result ) ) 
		{
			$o .= '<div><br><br>Keine Veranstaltungen mit diesen Filtereinstellungen gefunden.<br><br></div>';
		} else {
			if ($atts['view'] == 'list') {
				include("shortcode_eventlist.php");
			} else if($atts['view'] == 'map') {
				echo $o;
				$o = "";
				include("shortcode_map.php");
			} else {
				$o .= "Error: Invalid shortcode 'view' parameter: " . $atts['view']; 
			}
		}
	}
	else
	{
		include("shortcode_details.php");
	}
	return $o;
}
?>
