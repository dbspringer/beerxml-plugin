<?php

/**
 * Class wrapper for admin options for BeerXML shortcode
 */
class BeerXML_Admin {

	/**
	 * Add options page to the admin menu and init the options
	 */
	function __construct() {
		add_filter( 'plugin_action_links_' . BEERXML_BASENAME, array( $this, 'settings_link' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_action( 'admin_init', array( $this, 'options_init' ) );
	}

	/**
	 * Add settings link on plugin page
	 */
	function settings_link( $links ) {
		$settings_link = '<a href="options-general.php?page=beerxml-shortcode">Settings</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}

	/**
	 * Add the options page
	 */
	function add_options_page() {
		add_options_page(
			'BeerXML Shortcode',
			'BeerXML Shortcode',
			'manage_options',
			'beerxml-shortcode',
			array( $this, 'options_page' )
		);
	}

	/**
	 * Output the options to screen
	 */
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

	/**
	 * Add the register the settings and setting sections
	 */
	function options_init() {
		register_setting( 'beerxml_shortcode_group', 'beerxml_shortcode_units', 'absint' );
		register_setting( 'beerxml_shortcode_group', 'beerxml_shortcode_cache', 'absint' );
		register_setting( 'beerxml_shortcode_group', 'beerxml_shortcode_download', 'absint' );
		register_setting( 'beerxml_shortcode_group', 'beerxml_shortcode_style', 'absint' );
		register_setting( 'beerxml_shortcode_group', 'beerxml_shortcode_mash', 'absint' );
		register_setting( 'beerxml_shortcode_group', 'beerxml_shortcode_fermentation', 'absint' );

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

		add_settings_field(
			'beerxml_shortcode_style',
			__( 'Include style details', 'beerxml-shortcode' ),
			array( $this, 'style_option' ),
			'beerxml-shortcode',
			'beerxml_shortcode_section'
		);

		add_settings_field(
			'beerxml_shortcode_mash',
			__( 'Include mash details', 'beerxml-shortcode' ),
			array( $this, 'mash_option' ),
			'beerxml-shortcode',
			'beerxml_shortcode_section'
		);

		add_settings_field(
			'beerxml_shortcode_fermentation',
			__( 'Include fermentation details', 'beerxml-shortcode' ),
			array( $this, 'fermentation_option' ),
			'beerxml-shortcode',
			'beerxml_shortcode_section'
		);
	}

	/**
	 * Notice for default options
	 */
	function print_section_info() {
		_e( 'Used by default unless overwritten via shortcode', 'beerxml-shortcode' );
	}

	/**
	 * Callback for units option
	 */
	function units_option() {
		$units = get_option( 'beerxml_shortcode_units', 1 );
		?>
		<select id="beerxml_shortcode_units" name="beerxml_shortcode_units">
			<option value="1" <?php selected( $units, 1 ); ?>>US</option>
			<option value="2" <?php selected( $units, 2 ); ?>>Metric</option>
		</select>
		<?php
	}

	/**
	 * Callback for cache option
	 */
	function cache_option() {
		?>
		<input type="text" id="beerxml_shortcode_cache" name="beerxml_shortcode_cache" value="<?php echo get_option( 'beerxml_shortcode_cache', 60*60*12 ); ?>" />
		<?php
	}

	/**
	 * Callback for download option
	 */
	function download_option() {
		?>
		<input type="checkbox" id="beerxml_shortcode_download" name="beerxml_shortcode_download" value="1" <?php checked( get_option( 'beerxml_shortcode_download', 1 ) ); ?> />
		<?php
	}

	/**
	 * Callback for style option
	 */
	function style_option() {
		?>
		<input type="checkbox" id="beerxml_shortcode_style" name="beerxml_shortcode_style" value="1" <?php checked( get_option( 'beerxml_shortcode_style', 1 ) ); ?> />
		<?php
	}

	/**
	 * Callback for mash option
	 */
	function mash_option() {
		?>
		<input type="checkbox" id="beerxml_shortcode_mash" name="beerxml_shortcode_mash" value="1" <?php checked( get_option( 'beerxml_shortcode_mash', 0 ) ); ?> />
		<?php
	}

	/**
	 * Callback for fermentation option
	 */
	function fermentation_option() {
		?>
		<input type="checkbox" id="beerxml_shortcode_fermentation" name="beerxml_shortcode_fermentation" value="1" <?php checked( get_option( 'beerxml_shortcode_fermentation', 0 ) ); ?> />
		<?php
	}
}

// init admin
new BeerXML_Admin();
