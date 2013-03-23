=== BeerXML Shortcode ===
Contributors: derekspringer
Donate link: http://wordpressfoundation.org/donate/
Tags: shortcode, beer, beerxml
Requires at least: 3.0
Tested up to: 3.4.1
Stable tag: 0.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Automatically insert/display beer recipes by linking to a BeerXML document.

== Description ==

A shortcode for linking to beer recipes.

* Link to a BeerXML document to display recipe details, fermentables, hops, and yeast information.
* Allows you to easily switch between U.S. & Metric measurements.
* Control if & how long recipe is cached.

It follows the basic format of [beerxml recipe={URL} metric=true|false cache=-1|{seconds to cache}]

Please note: metric and cache are optional values and have the following defaults:

* metric = false
* cache = 12 hours (60 x 60 x 12 seconds), -1 kills the cache

== Installation ==

1. Upload the files to the `/wp-content/plugins/beerxml-plugin/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.

== Screenshots ==

1. BeerXML recipe displayed in the twenty eleven theme.
2. Inserting the shortcode into a post.

== Changelog ==

= 0.1.2b2 =

* Testing beta release procedure.

= 0.1.1 =

* Added text/xml as acceptable mime type for BeerXML document self-hosting.

= 0.1 =

* First cut, allows basic display of details, fermentables, hops, and yeast information in U.S. or metric units.

== TODO ==

Here's some stuff that would be nice to add in the near future:

* Custom CSS definition.
* Auto unit select.
* Quick unit select.
