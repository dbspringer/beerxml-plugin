<?php

class BeerXML_Admin {

	function __construct() {
		$this->init();
	}

	function init() {
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
	}

	function add_options_page() {
		add_options_page(
			'BeerXML Shortcode',
			'BeerXML Shortcode',
			'manage_options',
			'beerxml-shortcode',
			array( $this, 'options_page' )
		);
	}

	function options_page() {
		echo <<<HTML
		<div class="wrap">
			<div id="icon-options-general" class="icon32"><br></div>
			<h2>BeerXML Shortcode Settings</h2>
		</div>
HTML;
	}
}

// init admin
new BeerXML_Admin();
