<?php
/*
Plugin Name: BeerXML Shortcode
Plugin URI: http://automattic.com/
Description: Automatically insert/display beer recipes by linking to a BeerXML document.
Author: Derek Springer
Version: 0.1
Author URI: http://flavors.me/derekspringer
*/

class BeerXMLShortcode {

	/**
	 * A simple call to init when constructed
	 */
	function __construct() {
		register_activation_hook( __FILE__, array( $this, 'install' ) );
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Do all the plugin activation setup
	 */
	function install() {
		if( ! get_option( 'beerxml_shortcode_recipes' ) ) {
			add_option( 'beerxml_shortcode_recipes', array(), '', 'no' );
		}
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
	 * [beer_xml_shortcode description]
	 * @param  array $atts shortcode attributes
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
			'recipe'  => null
		), $atts ) );

		if ( ! isset( $recipe ) ) {
			$recipe = $atts[0];
		}

		$recipe = esc_url_raw( $recipe );
		$recipe_filename = pathinfo( $recipe, PATHINFO_FILENAME );
		$recipe_id = "{$post->ID}_$recipe_filename";
		$beer_xml = get_option( "beerxml_shortcode_recipe-$recipe_id" );
		if ( ! $beer_xml ) {
			$beer_xml = new BeerXML( $recipe );
			if ( $beer_xml->recipes ) {
				$recipes = get_option( 'beerxml_shortcode_recipes', array() );
				$recipes[] = $recipe_id;
				update_option( 'beerxml_shortcode_recipes', $recipes );
				add_option( "beerxml_shortcode_recipe-$recipe_id", $beer_xml );
			}
		}

		if ( ! $beer_xml->recipes ) {
			return '<!-- Error parsing BeerXML document -->';
		}

		return "<h1>{$beer_xml->recipes[0]->name}</h1>";
	}
}

// The fun starts here!
new BeerXMLShortcode();
