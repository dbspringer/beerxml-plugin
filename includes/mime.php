<?php

/**
 * Add text/xml to acceptable mime types so users can upload BeerXML files
 */
class BeerXML_Mime {

	/**
	 * Add a filter to upload_mimes
	 */
	function __construct() {
		add_filter( 'upload_mimes', array( $this, 'beerxml_mimes' ) );
	}

	/**
	 * Add mimes required for the BeerXML documents (currently just application/xml)
	 * @param  array $mimes mime types to filter
	 * @return array new array of acceptable mimes
	 */
	function beerxml_mimes( $mimes ) {
		if ( ! isset( $mimes['xml'] ) )
			return array_merge( $mimes, array( 'xml' => 'application/xml' ) );

		return $mimes;
	}
}

new BeerXML_Mime();
