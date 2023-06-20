<?php
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function ct_setup() {

// Add theme support for
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script'
		)
	);

	add_theme_support( 'title-tag' );
	add_theme_support( 'woocommerce' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'customize-selective-refresh-widgets' );


// Gutenberg presets

	add_theme_support( 'align-wide' );
	add_theme_support( 'custom-spacing' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'custom-line-height' );

	remove_theme_support( 'core-block-patterns' );

	// Add support for block styles.
	add_theme_support( 'wp-block-styles' );

	// Enqueue editor styles.
	add_editor_style( 'assets/dist/css/gutenberg.css' );

	// Font Sizes
	add_theme_support(
		'editor-font-sizes',
		array(
			array(
				'name'      => 'Extra Large',
				'shortName' => 'XL',
				'size'      => 20,
				'slug'      => 'extra-large'
			),
			array(
				'name'      => 'Large',
				'shortName' => 'L',
				'size'      => 18,
				'slug'      => 'large'
			),
		)
	);

	// Background Gradients
	add_theme_support(
		'editor-gradient-presets',
		array(
			array(
				'name'     => 'White to light gray',
				'gradient' => 'linear-gradient(0deg, rgba(242,242,242,1) 0%, rgba(255,255,255,1) 100%)',
				'slug'     => 'white-to-light-gray'
			),
			array(
				'name'     => 'Black to transparent',
				'gradient' => 'linear-gradient(0deg, rgba(0,0,0,1) 0%, rgba(255,255,255,0) 100%)',
				'slug'     => 'black-to-transparent',
			),
			array(
				'name'     => 'Low alpha black to transparent',
				'gradient' => 'linear-gradient(0deg, rgba(0,0,0,0.65) 0%, rgba(255,255,255,0) 100%)',
				'slug'     => 'low-alpha-black-to-transparent',
			),
		)
	);

	// Background Colors
	add_theme_support(
		'editor-color-palette', array(
			array(
				'name'	=> 'White',
				'slug'	=> 'white',
				'color'	=> '#FFFFFF',
			),
			array(
				'name'	=> 'Black',
				'slug'	=> 'black',
				'color'	=> '#000000',
			),
		)
	);


// Register custom image sizes
	add_image_size( 'ct-gallery', 330, 220, array( 'center', 'center' ) );
	add_image_size( 'ct-section-header', 1146, 380, array( 'center', 'center' ) );

	add_image_size( 'ct-thumb', 86, 86, false );
	add_image_size( 'ct-thumb-large',167, 167, false );
	add_image_size( 'ct-small', 280, 280, false );
	add_image_size( 'ct-medium', 370, 370, false );
	add_image_size( 'ct-xmedium', 960, 960, false );
	add_image_size( 'ct-large', 1280, 1280, false );

}
add_action( 'after_setup_theme', 'ct_setup' );
