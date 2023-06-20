<?php

// Shortcode is [ct-sitemap show_tax="true"]
if ( defined( 'YOAST_ENVIRONMENT' ) ) {

	/**
	 * Shortcode for HTML Sitemap
	 *
	 * @return string of HTML sitemap
	 */
	function ct_sitemap( $atts ) {

		$a = shortcode_atts( array(
			'show_tax' => true,
		), $atts );

		$a['show_tax'] = $a['show_tax'] === 'true' ? true : false;

		$output = '';
		$pt = get_post_types( [ 'public' => true, 'show_in_rest' => true ], 'names', 'and' );
		sort( $pt );

		foreach ( $pt as $type ) {
			if ( $type !== 'attachment' && WPSEO_Post_Type::is_post_type_indexable( $type ) ) {

				$query = new WP_Query(
					array(
						'posts_per_page'    => -1,
						'post_type'         => $type,
						'post_status'     	=> 'publish',
						'has_password'		=> false,
						'post__not_in'		=> array( get_the_ID() ),
						'orderby'			=> 'title',
						'order'				=> 'ASC',
						'meta_query'        => array(
							'relation'      => 'OR',
							array(
								'compare'   => 'NOT EXISTS',
								'key'       => '_yoast_wpseo_meta-robots-noindex',
							),
							array(
								'value'     => 1,
								'compare'   => '!=',
								'key'       => '_yoast_wpseo_meta-robots-noindex',
							),
						),
					)
				);
				if ( $query->have_posts() ) {
					$output .= '<h3>' . get_post_type_object( $type )->labels->name . '</h3>';

					$output .= '<ul>';
					if ( get_post_type_object( $type )->hierarchical ) {
						$output .= wp_list_pages(
							array(
								'echo'      	=> 0,
								'title_li'  	=> null,
								'post_type' 	=> $type,
								'has_password'	=> false,
								'orderby'		=> 'title',
								'order'			=> 'ASC',
								'include'		=> wp_list_pluck( $query->posts, 'ID' ),
								'post_status'   => 'publish',
							)
						);
					} else {
						while ( $query->have_posts() ) { $query->the_post();
							$output .= '<li><a href="' . get_the_permalink( get_the_ID() ) . '">' . get_the_title() . '</a></li>';
						}
					}
					$output .= '</ul>';
				}
				wp_reset_postdata();

			}
		}

		if ( $a['show_tax'] ) {
			$tax_class = new WPSEO_Taxonomy_Sitemap_Provider();
			// Load all public taxonomies also.
			foreach ( get_taxonomies( [ 'public' => true, 'show_in_rest' => true ], 'objects' ) as $tax => $n ) {
				if ( $tax_class->is_valid_taxonomy( $tax ) ) {
					$terms = get_terms( array(
						'taxonomy'		=> $tax,
						'hide_empty'	=> true,
					) );

					if ( !empty( $terms ) ) {
						$output .= '<h3>' . $n->label . '</h3>';
						$output .= '<ul>';
						foreach ( $terms as $t ) {
							if ( isset( get_option( 'wpseo_taxonomy_meta' )[$tax][$t->term_id]['wpseo_noindex'] ) ) {
								$index = get_option( 'wpseo_taxonomy_meta' )[$tax][$t->term_id]['wpseo_noindex'];
								if ( !empty( $index ) && $index === 'noindex' ) {
									continue;
								}
							}
							$output .= '<li><a href="' . get_term_link( $t, $tax ) . '">' . $t->name . '</a></li>';
						}
						$output .= '</ul>';
					}
				}
			}
		}

		return $output;
	}
	add_shortcode( 'ct-sitemap', 'ct_sitemap' );

}
