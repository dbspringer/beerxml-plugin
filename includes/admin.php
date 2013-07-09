<?php

class BeerXML_Admin {

	function __construct() {
		$this->init();
	}

	function init() {
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_action( 'admin_init', array( $this, 'options_init' ) );
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
		?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2>BeerXML Shortcode Settings</h2>
			<form method="post" action="options.php">
				<?php
				settings_fields( 'beerxml_shortcode_group' );
				do_settings_sections( 'beerxml-shortcode' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	function options_init() {
		register_setting( 'beerxml_shortcode_group', 'beerxml_shortcode_units', 'absint' );
		register_setting( 'beerxml_shortcode_group', 'beerxml_shortcode_cache', 'absint' );
		register_setting( 'beerxml_shortcode_group', 'beerxml_shortcode_download' );

		add_settings_section(
			'beerxml_shortcode_section',
			__( 'Shortcode default settings', 'beerxml-shortcode' ),
			array( $this, 'print_section_info' ),
			'beerxml-shortcode'
		);

		add_settings_field(
			'beerxml_shortcode_units',
			__( 'Units', 'beerxml-shortcode' ),
			array( $this, 'units_option' ),
			'beerxml-shortcode',
			'beerxml_shortcode_section'
		);

		add_settings_field(
			'beerxml_shortcode_cache',
			__( 'Cache duration (seconds)', 'beerxml-shortcode' ),
			array( $this, 'cache_option' ),
			'beerxml-shortcode',
			'beerxml_shortcode_section'
		);

		add_settings_field(
			'beerxml_shortcode_download',
			__( 'Include download link', 'beerxml-shortcode' ),
			array( $this, 'download_option' ),
			'beerxml-shortcode',
			'beerxml_shortcode_section'
		);
	}

	function print_section_info() {
		_e( 'Used by default unless overwritten via shortcode', 'beerxml-shortcode' );
	}

	function units_option() {
		$units = get_option( 'beerxml_shortcode_units', 1 );
		?>
		<select id="beerxml_shortcode_units" name="beerxml_shortcode_units">
			<option value="1" <?php selected( $units, 1 ); ?>>US</option>
			<option value="2" <?php selected( $units, 2 ); ?>>Metric</option>
		</select>
		<?php
	}

	function cache_option() {
		?>
		<input type="text" id="beerxml_shortcode_cache" name="beerxml_shortcode_cache" value="<?php echo get_option( 'beerxml_shortcode_cache', 60*60*12 ); ?>" />
		<?php
	}

	function download_option() {
		?>
		<input type="checkbox" id="beerxml_shortcode_download" name="beerxml_shortcode_download" value="1" <?php checked( get_option( 'beerxml_shortcode_download', 1 ) ); ?> />
		<?php
	}
}

// init admin
new BeerXML_Admin();
