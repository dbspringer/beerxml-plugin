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
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Drafts for Friends initialization routines
	 */
	function init() {
		// I18n
		load_plugin_textdomain(
			'beer-xml-shortcode',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		if ( ! defined( 'BEER_XML_URL' ) ) {
			define( 'BEER_XML_URL', plugin_dir_url( __FILE__ ) );
		}
			
		if ( ! defined( 'BEER_XML_PATH' ) ) {
			define( 'BEER_XML_PATH', plugin_dir_path( __FILE__ ) );
		}

		require_once( BEER_XML_PATH . '/includes/classes.php' );

		add_shortcode( 'beerxml', array( $this, 'beer_xml_shortcode' ) );
	}

	function beer_xml_shortcode( $atts ) {
		if ( ! is_array( $atts ) ) {
			return '<!-- BeerXML shortcode passed invalid attributes -->';
		}

		if ( ! isset( $atts[0] ) ) {
			return '<!-- BeerXML shortcode source not set -->';
		}

		$xml_loc = esc_url_raw( $atts[0] );
		$beer_xml = new BeerXML( $xml_loc );

		return "<h1>{$beer_xml->recipes[0]->name}</h1>";
	}
}

// The fun starts here!
new BeerXMLShortcode();

?>
