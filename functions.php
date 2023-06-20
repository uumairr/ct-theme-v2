<?php
/**
 * Coalition Technologies functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Coalition_Technologies
 */

/**
 * Recursively search for a file.
 *
 * @param  string  $pattern The pattern. No tilde expansion or parameter substitution is done.
 * @param  integer $flags   Glob flags. @see https://www.php.net/manual/en/function.glob.php#refsect1-function.glob-parameters
 * @return string           Files matching pattern used.
 */
function ct_glob( $pattern, $flags = 0 ) {
	$files = glob( $pattern, $flags );
	foreach ( glob( dirname( $pattern ) . '/*', GLOB_ONLYDIR | GLOB_NOSORT ) as $dir ) {
		$files = array_merge( $files, ct_glob( $dir . '/' . basename( $pattern ), $flags ) );
	}
	return $files;
}

/**
 * Autoload all files from the functions.
 *
 * Will not load files that have the prefix -noload.php
 */
require_once get_stylesheet_directory() . '/functions/helper-functions.php';
require_once get_stylesheet_directory() . '/acf/init.php';
foreach ( ct_glob( get_stylesheet_directory() . '/functions/*.php' ) as $filename ) {
	if (
		( strpos( $filename, 'woocommerce' ) === false || class_exists( 'woocommerce' ) ) &&
		strpos( $filename, '-noload.php' ) === false &&
		$filename !== 'helper-functions.php'
	) {
		require_once $filename;
	}
}
