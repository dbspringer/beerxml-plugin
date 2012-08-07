<?php

// If uninstall not called from WordPress exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

$recipes = get_option( 'beerxml_shortcode_recipes' );
if ( $recipes ) {
	foreach ( $recipes as $recipe )  {
		delete_option( "beerxml_shortcode_recipe-$recipe" );
	}
}

// Delete option from the options table
delete_option( 'beerxml_shortcode_recipes' );

// Remove and additional options and custom tables
// TODO
