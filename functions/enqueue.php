<?php


/**
 * Enqueue scripts and styles.
 */
function ct_enqueue_assets() {
	// For all pages.

	/**
	 * Custom enqueuing for scripts
	 *
	 *  Handle => array (
	 *      path (/ct-theme/assets/js/ is the path used),
	 *      version (if empty will use filemtime),
	 *      require all prior scripts (true, false (only require jQuery), define script handles that are required),
	 *      load in footer
	 *  )
	 */
	$scripts = array(
		'a11y-menu' => array( 'vendor/a11y-menu.js', '', false ),
		'global'    => array( 'global.js', '', false ),
	);

	/**
	 * Custom enqueuing for styles
	 *
	 *  Handle => array (
	 *      path (used from theme assets),
	 *      version (if empty will use filemtime),
	 *      require all prior styles (true, false (only require jQuery), define required),
	 *      media type for the stylesheet
	 *  )
	 */
	$styles = array(
		'ct-main' => array( 'core.css', '', false ),
	);

	// Assets for front page.
	if ( is_front_page() ) {
		$styles['ct-home'] = array( 'templates/home.css', '', false );
	}

	// Assets for default page template.
	if ( basename( get_page_template() ) === 'page.php' && ! is_front_page() ) {
		$styles['ct-page'] = array( 'templates/page.css', '', false );
	}

	// Assets for single post templates.
	if ( is_single() ) {
		$styles['ct-single'] = array( 'templates/single.css', '', false );
	}

	// Assets for archive templates.
	if ( is_post_type_archive() || is_archive() || is_home() ) {
		$styles['ct-archive'] = array( 'templates/archive.css', '', false );
	}

	// Assets for any WooCommerce page template
	if ( is_realy_woocommerce_page() ) {

	}

	ct_enqueue_styles( $styles );
	ct_enqueue_scripts( $scripts );

}
add_action( 'wp_enqueue_scripts', 'ct_enqueue_assets' );


/**
 * Enqueue scripts and styles for Gutenberg Editor.
 */
function ct_gutenberg_enqueue_assets() {
	/**
	 * Custom enqueuing for scripts
	 *
	 *  Handle => array (
	 *      path (/ct-theme/assets/js/ is the path used),
	 *      version (if empty will use filemtime),
	 *      require all prior scripts (true, false (only require jQuery), define script handles that are required)
	 *  )
	 */
	$scripts = array(
		'global' => array( 'global.js', '', false ),
	);

	ct_enqueue_scripts( $scripts );

}
add_action( 'enqueue_block_editor_assets', 'ct_gutenberg_enqueue_assets' );