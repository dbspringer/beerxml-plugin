=== BeerXML Shortcode ===
Contributors: derekspringer
Donate link: http://wordpressfoundation.org/donate/
Tags: shortcode, beer, beerxml, homebrew, recipe
Requires at least: 3.4
Tested up to: 3.6
Stable tag: 0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Automatically insert/display beer recipes by linking to a BeerXML document.

== Description ==

A shortcode for linking to beer recipes.

* Link to a BeerXML document to display recipe details, style details, fermentables, hops, miscs, yeast, and notes.
* Allows you to easily switch between U.S. & Metric measurements.
* Control if & how long recipe is cached.
* Allow readers to download the recipe directly.

It follows the basic format of:

[beerxml recipe={URL} metric=true|false download=true|false style=true|false cache=-1|{seconds to cache}]

Please note: metric, download, style, and cache are optional values and have the following defaults:

* metric = false
* cache = 12 hours (60 x 60 x 12 seconds), -1 kills the cache and sets value to 0
* download = true
* style = true

== Installation ==

1. Upload the files to the `/wp-content/plugins/beerxml-plugin/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Go to Admin Dashboard -> Settings -> BeerXML Shortcode and configure defaults.

== Screenshots ==

1. BeerXML recipe displayed in the twenty eleven theme.
2. Inserting the shortcode into a post.

== Changelog ==

= 0.3 =

* Added Beer Style details section.
* Added % to fermentables.
* Added XML parsing security update I picked up at WCSF.

= 0.2 =

* Escaped XML parsing.
* Added admin menu to set default values for shortcode.
* Tweaked markup and added Miscs, Notes, and Download section.
* Added new 'download' parameter to shortcode to allow readers to directly download BeerXML file.

= 0.1.1 =

* Added text/xml as acceptable mime type for BeerXML document self-hosting.

= 0.1 =

* First cut, allows basic display of details, fermentables, hops, and yeast information in U.S. or metric units.

== TODO ==

Here's some stuff that would be nice to add in the near future:

* Custom CSS definition.
* Auto unit select.
* Quick unit select.
