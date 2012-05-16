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

		add_shortcode( 'beerxml', array( $this, 'beer_xml_shortcode' ) );
	}

	function beer_xml_shortcode( $atts ) {
		return '<h1>HELLO WORLD!</h1>';
	}
}

// The fun starts here!
new BeerXMLShortcode();

?>
