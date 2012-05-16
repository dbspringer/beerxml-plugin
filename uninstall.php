<?php

// If uninstall not called from WordPress exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

// Delete option from the options table
delete_option( 'beer-xml-shortcode-options' );

// Remove and additional options and custom tables
// TODO