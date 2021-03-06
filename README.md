# Wordpress Plugin for the Offenes Neukölln Programme

"[Offenes Neukölln](https:///www.offenes-neukoelln.de)" is a local anti-racist
festival in Berlin's district Neukölln. It promotes a diverse, open, and
solidary neighbourhood and is entirely coordinated by a group volunteers of
[Bündnis Neukölln](https://www.buendnis-neukoelln.de).

The more than 100 events of the festival are presented on the festival website
through a [Wordpress](https://www.wordpress.com/) plugin. The code is rather
specific to the particular event. However, it might still be useful to someone.

## Features

- List view
- Map view
- Filtering of events (by time, categories, fulltext search)
- Very rudimentary click statistics (in admin interface)

## Status

It works. And it looks nice. Code quality could be improved.

## Installation

- Copy the files to `wp-content/plugins/onk2019`
- Add a subfolder `leaflet` with the files of a
  [Leaflet](https://leafletjs.com/) installation
- Activate the plugin in your Wordpress admin interface
- Go to your database and fill the tables with events

## See it in action

https://www.offenes-neukoelln.de/programm/programm-2019

## Contributions and Feedback

... are always welcome: titus [at] buendnis-neukoelln.de or
https://github.com/twentytitus/

