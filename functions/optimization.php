<?php

function ct_disable_default_dashboard_widgets() {
    remove_meta_box( 'dashboard_right_now', 'dashboard', 'core' );
    remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'core' );
    remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'core' );
    remove_meta_box( 'dashboard_plugins', 'dashboard', 'core' );
    remove_meta_box( 'dashboard_quick_press', 'dashboard', 'core' );
    remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'core' );
    remove_meta_box( 'dashboard_primary', 'dashboard', 'core' );
    remove_meta_box( 'dashboard_secondary', 'dashboard', 'core' );
}
add_action( 'admin_menu', 'ct_disable_default_dashboard_widgets', 990 );


/**
 * Remove RSD (Really Simple Discovery) Links from header
 *
 * RSD is only useful to keep if pingback is needed or
 * if a remote client is used to manage posts.
 */
remove_action( 'wp_head', 'rsd_link' );


/**
 * Disable Windows Live Writer
 */
remove_action( 'wp_head', 'wlwmanifest_link' );


/**
 * Disable Emoticons
 *
 * This remove extra code related to emojis from WordPress
 * which was added recently to support emoticons on older browsers.
 */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

// (Optional) Completely remove Emoticons
add_filter( 'option_use_smilies', '__return_false' );


/**
 * Remove Shortlinks
 *
 * WordPress adds shorter links to the header.
 *
 * @since WP version 3.0
 */
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );


/**
 * Disable Embeds
 *
 * @see https://codex.wordpress.org/Embeds#oEmbed WordPress oEmbeds
 * @since WP version 4.4
 */
function ct_disable_embed() {
    wp_dequeue_script( 'wp-embed' );
}
add_action( 'wp_footer', 'ct_disable_embed' );


/**
 * Disable XML-RPC
 *
 * WordPress API (XML-RPC) allows to publish/edit/delete a post,
 * edit/list comments, upload file remotely.
 *
 * If XML-RPC is not properly secure it may
 * lead to DDoS & brute force attacks.
 *
 * Unless there's specific requirements for this.
 * It's better to disable XML-RPC.
 */
add_filter( 'xmlrpc_enabled', '__return_false' );


/**
 * Hide WordPress Version
 *
 * This doesn't improve the performance of WP at all
 * but is useful to hide from a security point of view.
 */
remove_action( 'wp_head', 'wp_generator' );


/**
 * Remove WLManifest Link
 *
 * Kind of pointless to have WLManifest unless
 * Windows Live Writer is used to write the posts.
 * (most likely not the case).
 */
remove_action( 'wp_head', 'wp_generator' );


/**
 * Disable Self Pingback
 *
 * If you link to a post or page within the site,
 * the self pingback feature will send a notification
 * (as though you were linking to an external source).
 */
function ct_disable_pingback( &$links ) {
    foreach ( $links as $l => $link ) {
        if ( 0 === strpos( $link, get_option( 'home' ) ) ) {
            unset( $links[ $l ] );
        }
    }
}
add_action( 'pre_ping', 'ct_disable_pingback' );


/**
 * Disable Heartbeat API
 *
 * WordPress uses the heartbeat API to communicate with a browser
 * to the server by frequently calling admin-ajax.php.
 * This may slow down the overall page load time and
 * it increases CPU utilization even more-so if on a shared hosting.
 */
function ct_stop_heartbeat() {
    wp_deregister_script( 'heartbeat' );
}
add_action( 'init', 'ct_stop_heartbeat', 1 );


/**
 * Force all scripts to footer
 */
function ct_js_to_footer() {
    remove_action( 'wp_head', 'wp_print_scripts' );
    remove_action( 'wp_head', 'wp_print_head_scripts', 9 );
    remove_action( 'wp_head', 'wp_enqueue_scripts', 1 );
}
add_action( 'wp_enqueue_scripts', 'ct_js_to_footer', 0 );


/**
 * Allow Gravity Forms scripts to be pushed to the footer without
 * causing any jQuery errors.
 */
function ct_wrap_gform_cdata_open( $content = '' ) {
    if ( ! ct_do_wrap_gform_cdata() ) {
        return $content;
    }
    $content = 'document.addEventListener( "DOMContentLoaded", function() { ' . $content;
    return $content;
}
// add_filter( 'gform_cdata_open', 'ct_wrap_gform_cdata_open', 1 );


function ct_wrap_gform_cdata_close( $content = '' ) {
    if ( ! ct_do_wrap_gform_cdata() ) {
        return $content;
    }
    $content .= ' }, false );';
    return $content;
}
// add_filter( 'gform_cdata_close', 'ct_wrap_gform_cdata_close', 99 );


function ct_do_wrap_gform_cdata() {
    if ( is_admin()
        || ( defined( 'DOING_AJAX' ) && DOING_AJAX )
        || isset( $_POST['gform_ajax'] )
        || isset( $_GET['gf_page'] )
        || doing_action( 'wp_footer' )
        || did_action( 'wp_footer' )
    ) {
        return false;
    }
    return true;
}

// add_filter( 'gform_init_scripts_footer', '__return_true' );
