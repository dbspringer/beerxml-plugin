<?php

// If uninstall not called from WordPress exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

$recipes = get_option( 'beerxml-shortcode-recipes' );
if ( $recipes ) {
	foreach ( $recipes as $recipe )  {
		delete_option( "beerxml-shortcode-$recipe" );
	}
}

// Delete option from the options table
delete_option( 'beerxml-shortcode-recipes' );

// Remove and additional options and custom tables
// TODO
