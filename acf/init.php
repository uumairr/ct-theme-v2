<?php

// If using ACF that supports block types
if ( function_exists( 'acf_register_block_type' ) ) :

	// Reuseable global variable of color.
	define( 'CT_COLOR', '#FFFFFF' );
	define( 'CT_BG_COLOR', '#076aab' );

	/**
	 * Function to register ACF block type
	 *
	 * @link https://www.advancedcustomfields.com/resources/acf_register_block_type/
	 */
	function ct_register_acf_block_types() {

		$cache = filemtime( assets_directory() . '/css/blocks/demo.css' );
		acf_register_block_type( array (
			'name'				=> 'demo',
			'title'				=> 'Demo',
			'description'		=> 'This is a demo block item',
			'render_template'	=> 'acf/templates/demo.php',
			'category'			=> 'coalition',
			'supports'			=> array(
				'anchor'			=> true,
			),
			'icon'				=> array(
				'src'				=> 'book-alt',
				'foreground'		=> CT_COLOR,
				'background'		=> CT_BG_COLOR,
			),
			'keywords'			=> array( 'test', 'demo' ),
			'enqueue_style'		=> assets_uri() . '/css/blocks/demo.css?ct-cache=' . $cache,
		) );

	}
	add_action( 'acf/init', 'ct_register_acf_block_types' );

	/**
	 * Register gutenberg block and re-order it to make custom category first.
	 * 
	 * @param  array $categories contains all registered categories
	 * @return array             sorted categories
	 */
	function ct_block_category( $categories ) {

		$custom_block = array(
			'slug'	=> 'coalition',
			'icon'	=> 'wordpress',
			'title'	=> 'CT Custom Blocks',
		);

		$categories_sorted = array();
		$categories_sorted[0] = $custom_block;

		foreach ($categories as $category) {
			$categories_sorted[] = $category;
		}

		return $categories_sorted;

	}
	add_filter( 'block_categories', 'ct_block_category', 10, 1);

endif;

/**
	  ________                                     __  __  _
	 /_  __/ /_  ___  ____ ___  ___     ________  / /_/ /_(_)___  ____ ______
	  / / / __ \/ _ \/ __ `__ \/ _ \   / ___/ _ \/ __/ __/ / __ \/ __ `/ ___/
	 / / / / / /  __/ / / / / /  __/  (__  )  __/ /_/ /_/ / / / / /_/ (__  )
	/_/ /_/ /_/\___/_/ /_/ /_/\___/  /____/\___/\__/\__/_/_/ /_/\__, /____/
															   /____/
 */

if ( function_exists( 'acf_add_local_field_group' ) ) :

	/**
	 * Add quick link to site settings to the admin nav bar
	 *
	 * @param  object $wp_admin_bar contains the admin bar object.
	 */
	function ct_add_site_settings( $wp_admin_bar ) {
		$args = array(
			'id'    => 'ct-settings',
			'title' => '<i class="dashicons-before dashicons-sos" style="line-height: 20px;display: inline-block;"></i> CT Settings',
			'href'  => admin_url( 'admin.php?page=ct-settings' ),
			'meta'  => array(
				'html'     => '<style>#wp-admin-bar-ct-settings a{color:#FFFFFF!important;background:#006AAC!important}</style>',
			),
		);

		$wp_admin_bar->add_node( $args );
		$wp_admin_bar->remove_node( 'wp-logo' );
		$wp_admin_bar->remove_node( 'comments' );
		$wp_admin_bar->remove_node( 'customize' );
	}
	add_action( 'admin_bar_menu', 'ct_add_site_settings', 99 );

	/**
	 * Registering ACF options page
	 */
	function ct_settings() {

		// Check function exists.
		if ( function_exists( 'acf_add_options_page' ) ) {

			acf_add_options_page(
				array(
					'position'      => 1,
					'page_title'    => 'CT Settings',
					'menu_title'    => 'CT Settings',
					'menu_slug'     => 'ct-settings',
					'icon_url'      => 'dashicons-sos',
				)
			);
		}
	}
	add_action( 'acf/init', 'ct_settings' );


	/**
	 * Fixing admin area with CSS
	 */
	function ct_fix_admin_stuff() {
		echo '
		<style>
			.wp-block {
				max-width: 1170px;
			}
			.editor-post-title textarea {
				text-align: center;
			}
		</style>
		';
	}
	add_action( 'admin_head', 'ct_fix_admin_stuff' );

endif;
