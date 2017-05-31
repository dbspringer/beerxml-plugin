=== BeerXML Shortcode ===
Contributors: derekspringer, zarathos
Donate link: http://wordpressfoundation.org/donate/
Tags: shortcode, beer, beerxml, homebrew, recipe
Requires at least: 3.4
Tested up to: 4.8
Stable tag: 0.7.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Automatically insert and display beer recipes by linking to a BeerXML document.

== Description ==

A shortcode for displaying beer recipes. Now with [Shortcake (Shortcode UI)](https://wordpress.org/plugins/shortcode-ui/) integration!

* Link to a BeerXML document to display recipe details, style details, fermentables, hops, miscs, yeast, mash steps, fermentation schedule, and notes.
* Allows you to easily switch between U.S. & Metric measurements.
* Control if & how long recipe is cached.
* Allow readers to download the recipe directly.

It follows the basic format of:

[beerxml
	recipe={URL}
	metric=true|false
	download=true|false
	style=true|false
	mash=true|false
	fermentation=true|false
	mhop=true|false
	misc=true|false
	actuals=true|false
	cache=-1|{seconds to cache}]

Please note all options (minus recipe) are optional and have the following defaults:

* metric = false
* cache = 12 hours (60 x 60 x 12 seconds), -1 kills the cache and sets value to 0
* download = true
* style = true
* mash = true
* misc = true
* actuals = true
* fermentation = false
* mhop = false

== Installation ==

1. Upload the files to the `/wp-content/plugins/beerxml-plugin/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Go to Admin Dashboard -> Settings -> BeerXML Shortcode and configure defaults.

== Screenshots ==

1. BeerXML recipe displayed in the twenty eleven theme.
2. Inserting the shortcode into a post.
3. Insert Post Element option.
4. Insert Post Element details.

== Changelog ==

= 0.7.1 =

* Updated XML mime type to avoid blocked XML uploads

= 0.7 =

[Brülosopher's](http://brulosophy.com/) Baby

* Added 'mhop' flag to display hops in metric after a request by Brülosopher :)
* Made Miscs section optional using misc=true|false option (defaults to on).
* Added Actuals row to Details table using actuals=true|false (defaults to on).
* Extended length of Document URL field in Shortcake UI.

= 0.6.1 =

* Fix for weights improperly swapping at exactly 1 lb/kg.

= 0.6 =

* Weight will display oz if < 1 lb or g if < 1 kg.
* Set mash schedule to default on.
* Updated 'Tested up to' to 4.4.

= 0.5 =

* [Shortcake (Shortcode UI)](https://wordpress.org/plugins/shortcode-ui/) integration. When Shortcake is installed you will now be able to insert recipes via the Add Media->Insert Post Element option. Additionally, the BeerXML shortcode will now render in the visual editor.
* Moved wp_set_object_terms for post to outside build_style function. Beer Style should now be set for the post even if you choose not to display beer style details.

= 0.4 =

Tom Sawyer edition: thanks to [ksolomon](https://github.com/ksolomon) and [jksnetwork](https://github.com/jksnetwork) for their pull requests.

* Custom taxonomy for the beer style. Creates an archive of all beers added for each style with link to the list of beers for each style. @[ksolomon](https://github.com/dbspringer/beerxml-plugin/pull/5)
* Added mash and fermentation details, defaulted to off. To include add mash=true or fermentation=true to shortcode or update the settings in the admin menu. @[jksnetwork](https://github.com/dbspringer/beerxml-plugin/pull/6)

= 0.3.2 =

* Added default for miscs that don't have display_value.

= 0.3.1 =

* Updated uninstall.php to remove options (including multisite) upon uninstall.
* Updated XML retrieval to use wp_remote_get instead of file_get_contents, which caused some folks issues.
* Added settings link to plugins page.

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
