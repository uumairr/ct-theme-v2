<?php

/**
 * Function to define taxonomies for WooCommerce
 */
function custom_ct_woo_taxs() {

	$labels = array(
		'name'                       => 'Brands',
		'singular_name'              => 'Brand',
		'menu_name'                  => 'Brand',
		'all_items'                  => 'All Brands',
		'parent_item'                => 'Parent Brand',
		'parent_item_colon'          => 'Parent Brand:',
		'new_item_name'              => 'New Brand Name',
		'add_new_item'               => 'Add New Brand',
		'edit_item'                  => 'Edit Brand',
		'update_item'                => 'Update Brand',
		'separate_items_with_commas' => 'Separate Brand with commas',
		'search_items'               => 'Search Brands',
		'add_or_remove_items'        => 'Add or remove Brands',
		'choose_from_most_used'      => 'Choose from the most used Brands',
	);

	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);

	register_taxonomy( 'brand', 'product', $args );
	register_taxonomy_for_object_type( 'brand', 'product' );

}
add_action( 'init', 'custom_ct_woo_taxs' );


/**
 * This function replaces the default Gravatar fallback image with
 * a locally hosted Company logo.
 *
 * @param  string $url contains the gravatar url to manipulate
 * @return string $url of avatar
 */
function ct_custom_avatar( $url ) {

	$hash = html_entity_decode( $url );
	$hash = preg_replace( '/(http|https):\/\/[0|1|2]\.gravatar\.com\/avatar\//', '', $hash );
	$hash = preg_replace( '/\?s=(.*)&d=(.*)&r=(.*)/', '', $hash );

	if ( $hash ) {
		$gravatar_server = hexdec( $hash[0] ) % 3;
	} else {
		$gravatar_server = rand( 0, 2 );
	}

	if ( is_ssl() ) {
		$uri = 'https://secure.gravatar.com/avatar/' . $hash;
	} else {
		$uri = sprintf( 'http://%d.gravatar.com/avatar/%s', $gravatar_server, $hash . '?s=64&d=404&r=g' );
	}

	$headers = @get_headers( $uri );
	if ( ! preg_match( '|200|', $headers[0] ) ) {
		$avatar = get_bloginfo( 'template_directory' ) . '/assets/img/default-avatar.png';
	} else {
		$avatar = $url;
	}

	return $avatar;

}
add_filter( 'get_avatar_url', 'ct_custom_avatar', 10 );
