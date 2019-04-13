function add_map() {
	var mymap = L.map('mapid', {
		maxBounds: L.latLngBounds([52.390, 13.380], [52.508, 13.540])
	}).setView([52.448, 13.450], 12);
	
	// L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/light-v9/tiles/256/{z}/{x}/{y}?access_token=pk.eyJ1IjoiYnVlbmRuaXMtbmV1a29lbGxuIiwiYSI6ImNqNDE0anAwZDA3bWQycW9nb3Ywa214ZTUifQ.JSI_aPL9DT7DRzSGYM4S3w', {
		// attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',

	L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		attribution: 'Map &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
		minZoom: 12,
		maxZoom: 18
	}).addTo(mymap);

/*
	L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
		attribution: 'Map &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery &copy; <a href="http://openstreetmap.fr">openstreetmap.fr</a>',
		minZoom: 12,
		maxZoom: 18
	}).addTo(mymap);
*/
	return (mymap);
}

