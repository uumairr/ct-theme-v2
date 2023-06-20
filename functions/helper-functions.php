<?php

/**
 * Include partial file.
 * @param  [string]  $sFilePath       The component to include
 * @param  [array]   $aScopeVariables An array of variables to make available in the component.
 * @param  [boolean] $bReturn         Return the markup instead off outputting it.
 */
function ct_get_partial( $sFilePath, $aScopeVariables = [], $bReturn = false ) {

	ob_start();

	extract( $aScopeVariables, EXTR_OVERWRITE );

	$sTemplateSlug = sprintf( '/templates/partials/%s.php', $sFilePath );
	$sTemplateLocation = locate_template( $sTemplateSlug );

	if ( ! is_readable( $sTemplateLocation ) ) {
		throw new \Exception( sprintf( 'Missing partial: %s', $sFilePath ) );
	}

	include $sTemplateLocation;

	$sComponent = ob_get_clean();

	if ( $bReturn === true ) return $sComponent;

	echo $sComponent;
}

/**
 * Get dimensions for an SVG element using URL.
 * @param  [string] $url URL of the SVG element.
 * @return [array]       Image information as an array.
 *                           [string] Image source URL.
 *                           [int] Image width in pixels as as a int or null if not found.
 *                           [int] Image height in pixels as as a int or null if not found.
 */
function ct_svg_dimensions( $url ) {
	$image = array(
		$url,
		null,
		null
	);

	if ( is_array( $image ) && preg_match( '/\.svg$/i', $image[0] ) && $image[1] <= 1 ) {
		if ( ( $xml = simplexml_load_file( $image[0] ) ) !== false ) {
			$attr = $xml->attributes();
			$viewbox = explode( ' ', $attr->viewBox );
			$image[1] = isset( $attr->width ) && preg_match( '/\d+/', $attr->width, $value ) ? ( int ) $value[0] : ( count( $viewbox ) == 4 ? ( int ) $viewbox[2] : null );
			$image[2] = isset( $attr->height ) && preg_match( '/\d+/', $attr->height, $value ) ? ( int ) $value[0] : ( count( $viewbox ) == 4 ? ( int ) $viewbox[3] : null );
		} else {
			$image[1] = $image[2] = null;
		}
	}
	return $image;
}

/**
 * Returns the SVG HTML of the social media icon so it can easily be reused.
 *
 * @param  string $icon pick from one of the social media
 * @return string       HTML of the social media icon
 */
function ct_get_social_icon( string $icon ) {

	$icon = trim( strtolower( $icon ) );

	$social = array(
		'facebook' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M24 12.073C24 5.405 18.627 0 12 0S0 5.405 0 12.073C0 18.1 4.388 23.094 10.125 24v-8.437H7.078v-3.49h3.047v-2.66c0-3.025 1.792-4.697 4.533-4.697 1.312 0 2.686.236 2.686.236v2.971H15.83c-1.491 0-1.956.93-1.956 1.886v2.264h3.328l-.532 3.49h-2.796V24C19.612 23.094 24 18.1 24 12.073"></path></svg>',
		'instagram' => '<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 22 22"><path d="M16 0a6 6 0 016 6v10a6 6 0 01-6 6H6a6 6 0 01-6-6V6a6 6 0 016-6zm0 2H6a4 4 0 00-4 4v10a4 4 0 004 4h10a4 4 0 004-4V6a4 4 0 00-4-4zM6.577 8.709a5 5 0 118.932 4.496 5 5 0 01-8.932-4.496zm4.715-.742a3 3 0 10.191.022zM16.51 4.5a1 1 0 01.117 1.993L16.5 6.5a1 1 0 01-.117-1.993l.127-.007z"></path></svg>',
		'pinterest' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M11.995 0C5.361 0 0 5.37 0 11.995c0 5.084 3.16 9.428 7.622 11.176-.109-.948-.198-2.41.039-3.446.217-.938 1.402-5.963 1.402-5.963s-.355-.72-.355-1.777c0-1.668.967-2.912 2.171-2.912 1.027 0 1.52.77 1.52 1.688 0 1.027-.65 2.567-.996 3.998-.287 1.195.602 2.172 1.777 2.172 2.132 0 3.771-2.25 3.771-5.489 0-2.873-2.063-4.877-5.015-4.877-3.416 0-5.42 2.557-5.42 5.203 0 1.027.395 2.132.888 2.735a.357.357 0 01.08.345c-.09.375-.297 1.195-.336 1.363-.05.217-.178.266-.405.158-1.481-.711-2.409-2.903-2.409-4.66 0-3.781 2.745-7.257 7.928-7.257 4.156 0 7.394 2.962 7.394 6.931 0 4.137-2.606 7.464-6.22 7.464-1.214 0-2.36-.632-2.744-1.383l-.75 2.854c-.267 1.046-.998 2.35-1.491 3.149 1.125.345 2.31.533 3.554.533C18.629 24 24 18.63 24 12.005 23.99 5.37 18.62 0 11.995 0z"></path></svg>',
		'youtube' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 18"><path d="M9.545 12.799V5.2L15.818 9l-6.273 3.798zM23.498 2.81C23.222 1.704 22.41.833 21.377.537 19.505 0 12 0 12 0S4.495 0 2.623.537C1.591.833.778 1.704.502 2.811 0 4.816 0 9 0 9s0 4.184.502 6.19c.276 1.106 1.089 1.977 2.121 2.273C4.495 18 12 18 12 18s7.505 0 9.377-.537c1.032-.296 1.845-1.167 2.121-2.274C24 13.184 24 9 24 9s0-4.184-.502-6.19z"></path></svg>',
	);

	if ( array_key_exists( $icon, $social ) ) {
		$html = $social[ $icon ];
	}

	return $html;

}

/**
 * Get post ID from URL
 *
 * @param  string $url url of post
 * @return int      returns the post ID
 */
function ct_url_to_postid( string $url ) {
	// Try the core function
	$post_id = url_to_postid( $url );
	if ( $post_id == 0 ) {
		// Try custom post types
		$cpts = get_post_types( array(
			'public'   => true,
			'_builtin' => false
		), 'objects', 'and' );
		// Get path from URL
		$url_parts = explode( '/', trim( $url, '/' ) );
		$url_parts = array_splice( $url_parts, 3 );
		$path = implode( '/', $url_parts );
		// Test against each CPT's rewrite slug
		foreach ( $cpts as $cpt_name => $cpt ) {
			$cpt_slug = $cpt->rewrite['slug'];
			if ( strlen( $path ) > strlen( $cpt_slug ) && substr( $path, 0, strlen( $cpt_slug ) ) == $cpt_slug ) {
				$slug = substr( $path, strlen( $cpt_slug ) );
				$query = new WP_Query( array(
					'post_type'         => $cpt_name,
					'name'              => $slug,
					'posts_per_page'    => 1
				));
				if ( is_object( $query->post ) )
					$post_id = $query->post->ID;
			}
		}
	}
	return $post_id;
}

/**
 * Get all the defined Gutenberg colors for the color palette to be used in ACF too
 * @return string of all the colors added
 */
function ct_colors() {

	$color_palette = current( (array) get_theme_support( 'editor-color-palette' ) );

	// bail if there aren't any colors found
	if ( !$color_palette )
		return;

	// output begins
	ob_start();

	// output the names in a string
	echo '[';
		foreach ( $color_palette as $color ) {
			echo "'" . $color['color'] . "', ";
		}
	echo ']';

	return ob_get_clean();
}

/**
 * Used to trim our excerpt
 *
 * @param  string $text   string that's going to get trimmed
 * @param  int    $length      number of minimum length before trim
 * @param  string $length_type can be either 'sentence' or 'words'
 * @param  string $finish      can be either 'sentence' or 'word' or 'exact_w_spaces'
 * @return string              the trimmed string
 */
function neat_trim( string $text, int $length, string $length_type, string $finish ) {
	$tokens = array();
	$out = '';
	$w = 0;

	// Divide the string into tokens; HTML tags, or words, followed by any whitespace
	// (<[^>]+>|[^<>\s]+\s*)
	preg_match_all( '/(<[^>]+>|[^<>\s]+)\s*/u', $text, $tokens );
	foreach ( $tokens[0] as $t ) { // Parse each token
		if ( $w >= $length && 'sentence' != $finish ) { // Limit reached
			break;
		}
		if ( $t[0] != '<' ) { // Token is not a tag
			$t_trimmed = trim( $t );
			if ( $w >= $length && 'sentence' == $finish && preg_match( '/[\?\.\!](?!\d).*$/uS', $t_trimmed ) == 1 ) { // Limit reached, continue until ? . or ! occur at the end
				$out .= trim( $t );
				break;
			}
			if ( 'words' == $length_type ) { // Count words
				$w++;
			} else { // Count/trim characters
				if ( $finish == 'exact_w_spaces' ) {
					$chars = $t;
				} else {
					$chars = trim( $t );
				}
				$c = mb_strlen( $chars );
				if ( $c + $w > $length && 'sentence' != $finish ) { // Token is too long
					$c = ( 'word' == $finish ) ? $c : $length - $w; // Keep token to finish word
					$t = mb_substr( $t, 0, $c );
				}
				$w += $c;
			}
		}
		// Append what's left of the token
		$out .= $t;
	}

	return trim( force_balance_tags( $out ) );
}

/**
 * Function to create ID and classes for block type.
 *
 * @param	string	$class		Custom classes to append.
 * @return	string				ID and class for block element
 */
function ct_block_init( string $class = '' ) {

	// The array of arguments for registering a block type.
	global $ctblock;

	if ( !empty( $ctblock ) ) :

		$classes = array();

		if ( !empty( $ctblock['align'] ) ) :
			$classes[] = 'align-' . $ctblock['align'];
		endif;

		if ( !empty( $ctblock['align_text'] ) ) :
			$classes[] = 'has-text-align-' . $ctblock['align_text'];
		endif;

		if ( !empty( $ctblock['className'] ) ) :
			$classes[] = trim( $ctblock['className'] );
		endif;

		if ( !empty( $ctblock['data'][ acf_get_field( 'show_on' )['key'] ] ) ) :
			$classes[] = 'show-on-' . $ctblock['data'][ acf_get_field( 'show_on' )['key'] ];
		endif;

		if ( $class !== '' ) :
			$classes[] = trim( $class );
		endif;


		$id = str_replace( 'acf/', '', $ctblock['name'] ) . str_replace( 'block_', '-',$ctblock['id'] );
		if ( !empty( $ctblock['anchor'] ) ) :
			$id = $ctblock['anchor'];
		endif;

		$styles = array();

		foreach ( get_field( 'block_settings' ) as $key => $style ) {
			if ( !empty( $style ) && $style !== '0' && $style !== 'none' ) {
				$styles[] =	$key . ': ' . $style . ';';
			}
		}

		echo ' id="' . $id . '" style="' . esc_attr( implode( ' ' , $styles ) ) . '" class="' . esc_attr( implode( ' ' , $classes ) ) . '"';

	endif;

}


/**
 * Get direct assets directory path
 *
 * @return	string
 */
function assets_directory( $dist = true ) {
	if ( $dist ) {
		return get_template_directory() . '/assets/dist';
	}
	return get_template_directory() . '/assets';
}

/**
 * Get the assets URL path
 *
 * @return	string
 */
function assets_uri( $dist = true ) {
	if ( $dist ) {
		return get_template_directory_uri() . '/assets/dist';
	}
	return get_template_directory_uri() . '/assets';
}

/**
 * This function is for simplifying the enqueue process of JS
 *
 * @param	array of info about script
 *  Handle => array (
 *      path (used from theme assets),
 *      version (if empty will use filemtime),
 *      require all prior scripts (true, false (only require jQuery), define required),
 *      load in footer
 *  )
 *
 * @return	wp_enqueue_script
 */
function ct_enqueue_scripts( $scripts ) {
	foreach ( $scripts as $i => $script ) {
		if ( ! $script[2] || empty( $script[2] ) ) {
			$require = array( 'jquery' );
		} elseif ( $script[2] ) {
			$n       = array_keys( $scripts );
			$count   = array_search( $i, $n );
			$require = array_keys( array_slice( $scripts, 0, $count, true ) );
		} else {
			$require = $script[2];
		}
		if ( empty( $script[3] ) || !is_bool( $script[3] ) ) {
			$footer = true;
		} else {
			$footer = $script[3];
		}
		if ( strpos( $script[0], '//' ) === false ) {
			$src = assets_uri() . '/js/' . $script[0];
		} else {
			$src = $script[0];
		}
		if ( $script[1] !== '' ) {
			$ver = $script[1];
		} else if ( strpos( $script[0], '//' ) === false ) {
			$ver = filemtime( assets_directory() . '/js/' . $script[0] );
		} else {
			$ver = false;
		}
		wp_enqueue_script( $i, $src, $require, $ver, $footer );
	}
}

/**
 * This function is for simplifying the enqueue process of CSS
 *
 * @param	array of info about style
 *  Handle => array (
 *      path (used from theme assets),
 *      version (if empty will use filemtime),
 *      require all prior styles (true, false (only require jQuery), define required),
 *      media type for the stylesheet
 *  )
 *
 * @return	wp_enqueue_style
 */
function ct_enqueue_styles( $styles ) {
	foreach ( $styles as $i => $style ) {
		if ( !$style[2] || empty( $style[2] ) ) {
			$require = false;
		} elseif ( $style[2] ) {
			$n       = array_keys( $styles );
			$count   = array_search( $i, $n );
			$require = array_keys( array_slice( $styles, 0, $count, true ) );
		} else {
			$require = $style[2];
		}
		if ( empty( $styles[3] ) || !is_string( $styles[3] ) ) {
			$media = 'all';
		} else {
			$media = $styles[3];
		}
		if ( strpos( $style[0], '//' ) === false ) {
			$src = assets_uri() . '/css/' . $style[0];
		} else {
			$src = $style[0];
		}
		if ( $style[1] !== '' ) {
			$ver = $style[1];
		} else if ( strpos( $style[0], '//' ) === false ) {
			$ver = filemtime( assets_directory() . '/css/' . $style[0] );
		} else {
			$ver = false;
		}
		wp_enqueue_style( $i, $src, $require, $ver, $media );
	}
}

/**
 *  Function for outputting the logo
 *
 * @return	logo (PNG or SVG)
 */
function ct_logo() {
	if ( function_exists( 'get_field' ) ) :
		echo wp_get_attachment_image( get_field( 'logo_media', 'option' ), 'full' );
	endif;
}

/**
* Returns true if on a page which uses WooCommerce templates (cart and checkout are standard pages with shortcodes and which are also included)
*
* @return bool
*/
function is_realy_woocommerce_page() {
	if( function_exists ( "is_woocommerce" ) && is_woocommerce()){
		return true;
	}
	$woocommerce_keys = array ( "woocommerce_shop_page_id" ,
		"woocommerce_terms_page_id" ,
		"woocommerce_cart_page_id" ,
		"woocommerce_checkout_page_id" ,
		"woocommerce_pay_page_id" ,
		"woocommerce_thanks_page_id" ,
		"woocommerce_myaccount_page_id" ,
		"woocommerce_edit_address_page_id" ,
		"woocommerce_view_order_page_id" ,
		"woocommerce_change_password_page_id" ,
		"woocommerce_logout_page_id" ,
		"woocommerce_lost_password_page_id" ) ;

	foreach ( $woocommerce_keys as $wc_page_id ) {
		if ( get_the_ID () == get_option ( $wc_page_id , 0 ) ) {
			return true ;
		}
	}
	return false;
}

/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Coalition_Technologies
 */

if ( ! function_exists( 'ct_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function ct_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() )
		);

		echo '<span class="posted-on">' . $time_string . '</span>'; // WPCS: XSS OK.

	}
endif;

if ( ! function_exists( 'ct_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function ct_posted_by() {
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'ct' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

	}
endif;

if ( ! function_exists( 'ct_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function ct_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'ct' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links">' . esc_html__( 'TAGS %1$s', 'ct' ) . '</span>', $categories_list ); // WPCS: XSS OK.
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'ct' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				)
			);
			echo '</span>';
		}
	}
endif;

if ( ! function_exists( 'ct_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function ct_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>

			<div class="post-thumbnail">
				<?php the_post_thumbnail(); ?>
			</div><!-- .post-thumbnail -->

		<?php else : ?>

		<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
			<?php
			the_post_thumbnail(
				'post-thumbnail',
				array(
					'alt' => the_title_attribute(
						array(
							'echo' => false,
						)
					),
				)
			);
			?>
		</a>

			<?php
		endif; // End is_singular().
	}
endif;

if ( ! function_exists( 'wp_body_open' ) ) :
	/**
	 * Shim for sites older than 5.2.
	 *
	 * @link https://core.trac.wordpress.org/ticket/12563
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
endif;
