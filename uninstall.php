<?php

if ( ! defined( 'ABSPATH' ) || ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

if ( ! is_multisite() ) {
	delete_option( 'beerxml_shortcode_cache' );
	delete_option( 'beerxml_shortcode_units' );
	delete_option( 'beerxml_shortcode_download' );
	delete_option( 'beerxml_shortcode_style' );
	delete_option( 'beerxml_shortcode_mash' );
	delete_option( 'beerxml_shortcode_fermentation' );
	delete_option( 'beerxml_shortcode_misc' );
	delete_option( 'beerxml_shortcode_mhop' );
	delete_option( 'beerxml_shortcode_actuals' );
} else {
	global $wpdb;
	$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
	$original_blog_id = get_current_blog_id();
	foreach ( $blog_ids as $blog_id ) {
		switch_to_blog( $blog_id );
		delete_option( 'beerxml_shortcode_cache' );
		delete_option( 'beerxml_shortcode_units' );
		delete_option( 'beerxml_shortcode_download' );
		delete_option( 'beerxml_shortcode_style' );
		delete_option( 'beerxml_shortcode_mash' );
		delete_option( 'beerxml_shortcode_fermentation' );
		delete_option( 'beerxml_shortcode_misc' );
		delete_option( 'beerxml_shortcode_mhop' );
		delete_option( 'beerxml_shortcode_actuals' );
	}

	switch_to_blog( $original_blog_id );
}

// drops mic
