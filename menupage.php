<?php
/*
 * This is the main menu interface to the plugin
 */

function onk2019_menupage () {

	echo "<h2>Anleitung</h2>";

	echo "TODO: Add instructions";

	echo "<h2>Kategorien</h2>";

	global $wpdb;

	$category_table_name = $wpdb->prefix . 'onk2019_categories';
	
	if (isset($_POST['button_add'])) {
		$wpdb->query("TRUNCATE $category_table_name");
		$names = $_POST['name'];
		for ($i=0; $i<sizeof($names); $i++)
		{
			$cat = $names[$i];
			if ($cat != '') {
				$the_id = (integer) $_POST['id'][$i];
				$wpdb->insert($category_table_name, 
					array('id' => $the_id, 'name' => $cat),
					array('%d', '%s'));
			}
		}
	}

	$result = $wpdb->get_results( "SELECT * FROM $category_table_name" ); 

	echo '<form action="' . $_SERVER['PHP_SELF'] . '?page=onk2019-categories" method="post" name="theForm">';
	
	echo '<table>';
	echo '<tr><td>';
	$i = 0;
	foreach ($result as $row) {
		echo '<tr><td>';
		echo '<input type="number" size="2" name="id[' . $i . ']" value="' . $row->id . '">';
		echo '</td><td>';
		echo '<input type="text" name="name[' . $i . ']" value="' . $row->name . '">';
		echo '</td></tr>';
		$i++;
	}
	$nrow = $i;
	for (; $i<$nrow+3; $i++) {
		echo '<tr><td>';
		echo '<input type="number" size="2" name="id[' . $i . ']" value="">';
		echo '</td><td>';
		echo '<input type="text" name="name[' . $i . ']" placeholder="neu">';
		echo '</td></tr>';
	}

	echo '<tr>';
	echo '<td></td><td><i><small>Bitte Vorsicht beim Löschen von noch benutzten Kategorien!</small></i></td>';
	echo '</tr>';
	echo '</table>';

	echo '<br>';
	submit_button( 'Änderungen anwenden', 'primary', 'button_add', false );
	
	echo '</form>';


	echo "<h2>Veranstaltungstage</h2>";

/*

	global $wpdb;

	$day_table_name = $wpdb->prefix . 'onk2019_days';
	
	if (isset($_POST['button_add'])) {
		$wpdb->query("TRUNCATE $day_table_name");
		$days = $_POST['day'];
		for ($i=0; $i<sizeof($days); $i++)
		{
			$day = $days[$i];
			$date = $_POST['date'][$i];
			if ($day != '' && $date != '') {
				$wpdb->insert($day_table_name, 
					array('day' => $day, 'date' => date),
					array('%d', '%s'));
			}
		}
	}

	$result = $wpdb->get_results( "SELECT * FROM $day_table_name" ); 

	echo '<br />';
	echo '<form action="' . $_SERVER['PHP_SELF'] . '?page=onk2019-days" method="post" name="theForm">';
	
	echo '<table>';
	echo '<tr><td><b>Tage:</b></td></tr>';
	echo '<tr><td>';
	$i = 0;
	foreach ($result as $row) {
		echo '<tr><td>';
		echo '<input type="number" size="2" name="day[' . $i . ']" value="' . $row->day . '">';
		echo '</td><td>';
		echo '<input type="date" name="date[' . $i . ']" value="' . $row->date . '">';
		echo '</td></tr>';
		$i++;
	}
	$nrow = $i;
	for (; $i<$nrow+3; $i++) {
		echo '<tr><td>';
		echo '<input type="number" size="2" name="day[' . $i . ']" value="">';
		echo '</td><td>';
		echo '<input type="date" name="date[' . $i . ']" placeholder="neu">';
		echo '</td></tr>';
	}

	echo '<tr>';
	echo '</tr>';
	echo '</table>';

	echo '<br>';
	submit_button( 'Änderungen anwenden', 'primary', 'button_add', false );
	
	echo '</form>';

*/

}

?>
