<?php
/*
 * This is the main menu interface to the plugin
 */

function onk2019_menupage () {

	global $wpdb;
	$startstop = $wpdb->get_results(
		'SELECT MIN(date) as mindate, MAX(date) as maxdate ' .
		'FROM `uLZvWUiRonk2019_counter`'
	)[0];
	$topevents = $wpdb->get_results(
		'SELECT event, organiser, name, count(*) as clicks ' .
		'FROM `uLZvWUiRonk2019_counter` ct ' .
                'LEFT JOIN `uLZvWUiRonk2019` ev ' .
		'ON ct.event = ev.id ' .
		'GROUP BY event ORDER BY clicks DESC LIMIT 20'
	);

	echo "<h2>Anleitung</h2>";

	echo "TODO: Hier ein bisschen Doku einfügen";

	echo "<h2>Top-Veranstaltungen</h2>";
	echo "Zeitraum: " . htmlspecialchars($startstop->mindate) .
		" bis " . htmlspecialchars($startstop->maxdate);

	echo "<table>";
	echo "<tr><th>id</th><th>organiser</th><th>name</th><th>clicks</th></tr>";
	foreach($topevents as $row) {
		echo "<tr><td align='right'>" .
			"<a href='https://www.offenes-neukoelln.de/programm/?id=" .
				$row->event . "'>$row->event</td>" .
			"<td>$row->organiser</td>" .
			"<td>$row->name</td><td>$row->clicks</td></tr>";
	}
	echo "</table>";

}

?>
