<?php
/*
Plugin Name: BeerXML Shortcode
Plugin URI: http://automattic.com/
Description: Automatically insert/display beer recipes by linking to a BeerXML document.
Author: Derek Springer
Version: 0.1
Author URI: http://12inchpianist.com
License: GPL2
*/

/**
 * Class wrapper for BeerXML shortcode
 */
class BeerXMLShortcode {

	/**
	 * A simple call to init when constructed
	 */
	function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * BeerXML initialization routines
	 */
	function init() {
		// I18n
		load_plugin_textdomain(
			'beerxml-shortcode',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		if ( ! defined( 'BEERXML_URL' ) ) {
			define( 'BEERXML_URL', plugin_dir_url( __FILE__ ) );
		}

		if ( ! defined( 'BEERXML_PATH' ) ) {
			define( 'BEERXML_PATH', plugin_dir_path( __FILE__ ) );
		}

		require_once( BEERXML_PATH . '/includes/classes.php' );

		add_shortcode( 'beerxml', array( $this, 'beerxml_shortcode' ) );
	}

	/**
	 * Shortcode for BeerXML
	 * [beerxml recipe=http://example.com/wp-content/uploads/2012/08/bowie-brown.xml cache=10800 metric=true]
	 *
	 * @param  array $atts shortcode attributes
	 *                     recipe - URL to BeerXML document
	 *                     cache - number of seconds to cache recipe
	 *                     metric - true  -> use metric values
	 *                              false -> use U.S. values
	 * @return string HTML to be inserted in shortcode's place
	 */
	function beerxml_shortcode( $atts ) {
		global $post;

		if ( ! is_array( $atts ) ) {
			return '<!-- BeerXML shortcode passed invalid attributes -->';
		}

		if ( ! isset( $atts['recipe'] ) && ! isset( $atts[0] ) ) {
			return '<!-- BeerXML shortcode source not set -->';
		}

		extract( shortcode_atts( array(
			'recipe' => null,
			'cache'  => 60*60*12, // cache for 12 hours
			'metric' => false // U.S. measurements
		), $atts ) );

		if ( ! isset( $recipe ) ) {
			$recipe = $atts[0];
		}

		$recipe = esc_url_raw( $recipe );
		$recipe_filename = pathinfo( $recipe, PATHINFO_FILENAME );
		$recipe_id = "beerxml_shortcode_recipe-{$post->ID}_$recipe_filename";

		$cache  = intval( esc_attr( $cache ) );
		if ( -1 == $cache ) { // clear cache if set to -1
			delete_transient( $recipe_id );
			$cache = 0;
		}

		$metric = (boolean) esc_attr( $metric );

		if ( ! $cache || false === ( $beer_xml = get_transient( $recipe_id ) ) ) {
			$beer_xml = new BeerXML( $recipe );
		} else {
			// result was in cache, just use that
			return $beer_xml;
		}

		if ( ! $beer_xml->recipes ) { // empty recipe
			return '<!-- Error parsing BeerXML document -->';
		}

		/***************
		 * Recipe Details
		 **************/
		if ( $metric ) {
			$beer_xml->recipes[0]->batch_size = round( $beer_xml->recipes[0]->batch_size, 1 );
			$t_vol = __( 'L', 'beerxml-shortcode' );
		} else {
			$beer_xml->recipes[0]->batch_size = round( $beer_xml->recipes[0]->batch_size * 0.264172, 1 );
			$t_vol = __( 'gal', 'beerxml-shortcode' );
		}

		$btime = round( $beer_xml->recipes[0]->boil_time );
		$t_details = __( 'Recipe Details', 'beerxml-shortcode' );
		$t_size    = __( 'Batch Size', 'beerxml-shortcode' );
		$t_boil    = __( 'Boil Time', 'beerxml-shortcode' );
		$t_time    = __( 'min', 'beerxml-shortcode' );
		$t_ibu     = __( 'IBU', 'beerxml-shortcode' );
		$t_srm     = __( 'SRM', 'beerxml-shortcode' );
		$t_og      = __( 'Est. OG', 'beerxml-shortcode' );
		$t_fg      = __( 'Est. FG', 'beerxml-shortcode' );
		$t_abv     = __( 'ABV', 'beerxml-shortcode' );
		$details = <<<DETAILS
		<div class='beerxml-details'>
			<h3>$t_details</h3>
			<table>
				<thead>
					<tr>
						<th>$t_size</th>
						<th>$t_boil</th>
						<th>$t_ibu</th>
						<th>$t_srm</th>
						<th>$t_og</th>
						<th>$t_fg</th>
						<th>$t_abv</th>
					</tr>
					<tr>
						<td>{$beer_xml->recipes[0]->batch_size} $t_vol</td>
						<td>$btime $t_time</td>
						<td>{$beer_xml->recipes[0]->ibu}</td>
						<td>{$beer_xml->recipes[0]->est_color}</td>
						<td>{$beer_xml->recipes[0]->est_og}</td>
						<td>{$beer_xml->recipes[0]->est_fg}</td>
						<td>{$beer_xml->recipes[0]->est_abv}</td>
					</tr>
				</thead>
			</table>
		</div>
DETAILS;

		/***************
		 * Fermentables Details
		 **************/
		$fermentables = '';
		foreach ( $beer_xml->recipes[0]->fermentables as $fermentable ) {
			$fermentables .= $this->build_fermentable( $fermentable, $metric );
		}

		$t_fermentables = __( 'Fermentables', 'beerxml-shortcode' );
		$t_name = __( 'Name', 'beerxml-shortcode' );
		$t_amount = __( 'Amount', 'beerxml-shortcode' );
		$fermentables = <<<FERMENTABLES
		<div class='beerxml-fermentables'>
			<h3>$t_fermentables</h3>
			<table>
				<thead>
					<tr>
						<th>$t_name</th>
						<th>$t_amount</th>
					</tr>
					$fermentables
				</thead>
			</table>
		</div>
FERMENTABLES;

		/***************
		 * Hops Details
		 **************/
		$hops = '';
		foreach ( $beer_xml->recipes[0]->hops as $hop ) {
			$hops .= $this->build_hop( $hop, $metric );
		}

		$t_hops  = __( 'Hops', 'beerxml-shortcode' );
		$t_time  = __( 'Time', 'beerxml-shortcode' );
		$t_use   = __( 'Use', 'beerxml-shortcode' );
		$t_form  = __( 'Form', 'beerxml-shortcode' );
		$t_alpha = __( 'Alpha %', 'beerxml-shortcode' );
		$hops = <<<HOPS
		<div class='beerxml-hops'>
			<h3>$t_hops</h3>
			<table>
				<thead>
					<tr>
						<th>$t_name</th>
						<th>$t_amount</th>
						<th>$t_time</th>
						<th>$t_use</th>
						<th>$t_form</th>
						<th>$t_alpha</th>
					</tr>
					$hops
				</thead>
			</table>
		</div>
HOPS;

		/***************
		 * Yeast Details
		 **************/
		$yeasts = '';
		foreach ( $beer_xml->recipes[0]->yeasts as $yeast ) {
			$yeasts .= $this->build_yeast( $yeast, $metric );
		}

		$t_yeast       = __( 'Yeast', 'beerxml-shortcode' );
		$t_lab         = __( 'Lab', 'beerxml-shortcode' );
		$t_attenuation = __( 'Attenuation', 'beerxml-shortcode' );
		$t_temperature = __( 'Temperature', 'beerxml-shortcode' );
		$yeasts = <<<YEASTS
		<div class='beerxml-yeasts'>
			<h3>$t_yeast</h3>
			<table>
				<thead>
					<tr>
						<th>$t_name</th>
						<th>$t_lab</th>
						<th>$t_attenuation</th>
						<th>$t_temperature</th>
					</tr>
					$yeasts
				</thead>
			</table>
		</div>
YEASTS;

		// stick 'em all together
		$html = <<<HTML
		<div class='beerxml-recipe'>
			$details
			$fermentables
			$hops
			$yeasts
		</div>
HTML;

		if ( $cache && $beer_xml->recipes ) {
			set_transient( $recipe_id, $html, $cache );
		}

		return $html;
	}

	/**
	 * Build fermentable row
	 * @param  BeerXML_Fermentable  $fermentable fermentable to display
	 * @param  boolean $metric      true to display values in metric
	 * @return string               table row containing fermentable details
	 */
	static function build_fermentable( $fermentable, $metric = false ) {
		if ( $metric ) {
			$fermentable->amount = round( $fermentable->amount, 3 );
			$t_weight = __( 'kg', 'beerxml-shortcode' );
		} else {
			$fermentable->amount = round( $fermentable->amount * 2.20462, 2 );
			$t_weight = __( 'lbs', 'beerxml-shortcode' );
		}

		return <<<FERMENTABLE
		<tr>
			<td>$fermentable->name</td>
			<td>$fermentable->amount $t_weight</td>
		</tr>
FERMENTABLE;
	}

	/**
	 * Build hop row
	 * @param  BeerXML_Hop          $hop hop to display
	 * @param  boolean $metric      true to display values in metric
	 * @return string               table row containing hop details
	 */
	static function build_hop( $hop, $metric = false ) {
		if ( $metric ) {
			$hop->amount = round( $hop->amount * 1000, 1 );
			$t_weight = __( 'g', 'beerxml-shortcode' );
		} else {
			$hop->amount = round( $hop->amount * 35.274, 2 );
			$t_weight = __( 'oz', 'beerxml-shortcode' );
		}

		if ( $hop->time >= 1440 ) {
			$hop->time = round( $hop->time / 1440, 1);
			$t_time = _n( 'day', 'days', $hop->time, 'beerxml-shortcode' );
		} else {
			$hop->time = round( $hop->time );
			$t_time = __( 'min', 'beerxml-shortcode' );
		}

		$hop->alpha = round( $hop->alpha, 1 );

		return <<<FERMENTABLE
		<tr>
			<td>$hop->name</td>
			<td>$hop->amount $t_weight</td>
			<td>$hop->time $t_time</td>
			<td>$hop->use</td>
			<td>$hop->form</td>
			<td>$hop->alpha</td>
		</tr>
FERMENTABLE;
	}

	/**
	 * Build yeast row
	 * @param  BeerXML_Yeast        $yeast yeast to display
	 * @param  boolean $metric      true to display values in metric
	 * @return string               table row containing yeast details
	 */
	static function build_yeast( $yeast, $metric = false ) {
		if ( $metric ) {
			$yeast->min_temperature = round( $yeast->min_temperature, 2 );
			$yeast->max_temperature = round( $yeast->max_temperature, 2 );
			$t_temp = __( 'C', 'beerxml-shortcode' );
		} else {
			$yeast->min_temperature = round( ( $yeast->min_temperature * (9/5) ) + 32, 1 );
			$yeast->max_temperature = round( ( $yeast->max_temperature * (9/5) ) + 32, 1 );
			$t_temp = __( 'F', 'beerxml-shortcode' );
		}

		$yeast->attenuation = round( $yeast->attenuation );

		return <<<YEAST
		<tr>
			<td>$yeast->name ({$yeast->product_id})</td>
			<td>$yeast->laboratory</td>
			<td>{$yeast->attenuation}%</td>
			<td>{$yeast->min_temperature}°$t_temp - {$yeast->max_temperature}°$t_temp</td>
		</tr>
YEAST;
	}
}

// The fun starts here!
new BeerXMLShortcode();
