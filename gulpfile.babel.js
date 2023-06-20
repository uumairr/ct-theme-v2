/**
 * Gulpfile.
 *
 * Gulp with WordPress.
 *
 * Implements:
 *      1. Live reloads browser with BrowserSync.
 *      2. CSS: Sass to CSS conversion, error catching, Autoprefixing, Sourcemaps,
 *         CSS minification, and Merge Media Queries.
 *      3. JS: Concatenates & uglifies Vendor and Custom JS files.
 *      4. Images: Minifies PNG, JPEG, GIF and SVG images.
 *      5. Watches files for changes in CSS or JS.
 *      6. Watches files for changes in PHP.
 *      7. Corrects the line endings.
 *      8. InjectCSS instead of browser page reload.
 *      9. Generates .pot file for i18n and l10n.
 *
 * @tutorial https://github.com/ahmadawais/WPGulp
 * @author Ahmad Awais <https://twitter.com/MrAhmadAwais/>
 */

/**
 * Load WPGulp Configuration.
 *
 * TODO: Customize your project in the wpgulp.js file.
 */
var config       = require( './ct.config.js' ),

/**
 * Load Plugins.
 *
 * Load gulp plugins and passing them semantic names.
 */
	gulp         = require( 'gulp' ), // Gulp of-course.

// CSS related plugins.
	sass         = require('gulp-sass')(require('sass')), // Gulp plugin for Sass compilation.
	minifycss    = require( 'gulp-uglifycss' ), // Minifies CSS files.
	autoprefixer = require( 'gulp-autoprefixer' ), // Autoprefixing magic.
	mmq          = require( 'gulp-merge-media-queries' ), // Combine matching media queries into one.

// JS related plugins.
	uglify       = require( 'gulp-uglify' ), // Minifies JS files.
	concat       = require( 'gulp-concat' ), // Concatenates JS files.

// Image related plugins.
	imagemin     = require( 'gulp-imagemin' ), // Minify PNG, JPEG, GIF and SVG images with imagemin.

// Utility related plugins.
	beep         = require( 'beepbeep' ),
	zip          = require( 'gulp-zip' ), // Zip plugin or theme file.
	cache        = require( 'gulp-cache' ), // Cache files in stream for later use.
	filter       = require( 'gulp-filter' ), // Enables you to work on a subset of the original files by filtering them using a glob.
	notify       = require( 'gulp-notify' ), // Sends message notification to you.
	plumber      = require( 'gulp-plumber' ), // Prevent pipe breaking caused by errors from gulp plugins.
	sourcemaps   = require( 'gulp-sourcemaps' ), // Maps code in a compressed file ( E.g. style.css ) back to it’s original position in a source file ( E.g. structure.scss, which was later combined with other css files to generate style.css ).
	browserSync  = require( 'browser-sync' ).create(), // Reloads browser and injects CSS. Time-saving synchronized browser testing.
	lineec       = require( 'gulp-line-ending-corrector' ); // Consistent Line Endings for non UNIX systems. Gulp Plugin for Line Ending Corrector ( A utility that makes sure your files have consistent line endings ).

/**
 * Custom Error Handler.
 *
 * @param Mixed err
 */
var errorHandler = r => {
	notify.onError( '\n\n❌ Error!\n' )( r );
	beep();

	// this.emit( 'end' );
};

/**
 * Task: `browser-sync`.
 *
 * Live Reloads, CSS injections, Localhost tunneling.
 * @link http://www.browsersync.io/docs/options/
 *
 * @param { Mixed } done Done.
 */
var browsersync = done => {
	browserSync.init( {
		proxy: config.projectURL,
		open: config.browserAutoOpen,
		injectChanges: config.injectChanges,
		watchEvents: [ 'change', 'add', 'unlink', 'addDir', 'unlinkDir' ]
	} );
	done();
};

// Helper function to allow browser reload with Gulp 4.
var reload = done => {
	browserSync.reload();
	done();
};

/**
 * Task: `styles`.
 *
 * Compiles Sass, Autoprefixes it and Minifies CSS.
 *
 * This task does the following:
 *    1. Gets the source scss file
 *    2. Compiles Sass to CSS
 *    3. Writes Sourcemaps for it
 *    4. Autoprefixes it and generates style.css
 *    5. Renames the CSS file with suffix .min.css
 *    6. Minifies the CSS file and generates style.min.css
 *    7. Injects CSS or reloads the browser via browserSync
 */
gulp.task( 'styles', () => {
	return gulp
		.src( config.styleSRC, { allowEmpty: true } )
		.pipe( plumber( errorHandler ) )
		.pipe( sourcemaps.init() )
		.pipe(
			sass( {
				errLogToConsole: config.errLogToConsole,
				outputStyle: config.outputStyle,
				precision: config.precision
			} )
		)
		.on( 'error', sass.logError )
		.pipe( sourcemaps.write( { includeContent: false } ) )
		.pipe( sourcemaps.init( { loadMaps: true } ) )
		.pipe( autoprefixer( config.BROWSERS_LIST ) )
		.pipe( sourcemaps.write( './' ) )
		.pipe( filter( '**/*.css' ) ) // Filtering stream to only css files.
		.pipe( mmq( { log: true } ) ) // Merge Media Queries only for .min.css version.
		.pipe( browserSync.stream() ) // Reloads style.css if that is enqueued.
		.pipe( minifycss() )
		.pipe( lineec() ) // Consistent Line Endings for non UNIX systems.
		.pipe( gulp.dest( config.styleDestination ) )
		.pipe( filter( '**/*.css' ) ) // Filtering stream to only css files.
		.pipe( browserSync.stream() ); // Reloads style.min.css if that is enqueued.
} );

/**
 * Task: `vendorsJS`.
 *
 * Concatenate and uglify vendor JS scripts.
 *
 * This task does the following:
 *     1. Gets the source folder for JS vendor files
 *     2. Concatenates all the files and generates vendors.js
 *     3. Renames the JS file with suffix .min.js
 *     4. Uglifes/Minifies the JS file and generates vendors.min.js
 */
gulp.task( 'vendorsJS', () => {
	return gulp
		.src( config.jsVendorSRC ) // Only run on changed files.
		.pipe( plumber( errorHandler ) )
		.pipe( uglify() )
		.pipe( lineec() ) // Consistent Line Endings for non UNIX systems.
		.pipe( gulp.dest( config.jsVendorDestination ) );
} );

/**
 * Task: `globalJS`.
 *
 * Concatenate and uglify global JS scripts.
 *
 * This task does the following:
 *     1. Gets the source folder for JS global files
 *     2. Concatenates all the files and generates global.js
 *     3. Renames the JS file with suffix .min.js
 *     4. Uglifes/Minifies the JS file and generates global.min.js
 */
gulp.task( 'globalJS', () => {
	return gulp
		.src( config.jsGlobalSRC ) // Only run on changed files.
		.pipe( plumber( errorHandler ) )
		.pipe( concat( config.jsGlobalFile + '.js' ) )
		.pipe( uglify() )
		.pipe( lineec() ) // Consistent Line Endings for non UNIX systems.
		.pipe( gulp.dest( config.jsGlobalDestination ) );
} );

/**
 * Task: `customJS`.
 *
 * Concatenate and uglify custom JS scripts.
 *
 * This task does the following:
 *     1. Gets the source folder for JS custom files
 *     2. Renames the JS file with suffix .min.js
 *     3. Uglifes/Minifies the JS file and generates custom.min.js
 */
gulp.task( 'customJS', () => {
	return gulp
		.src( config.jsCustomSRC ) // Only run on changed files.
		.pipe( plumber( errorHandler ) )
		.pipe( uglify() )
		.pipe( lineec() ) // Consistent Line Endings for non UNIX systems.
		.pipe( gulp.dest( config.jsCustomDestination ) );
} );

/**
 * Task: `images`.
 *
 * Minifies PNG, JPEG, GIF and SVG images.
 *
 * This task does the following:
 *     1. Gets the source of images raw folder
 *     2. Minifies PNG, JPEG, GIF and SVG images
 *     3. Generates and saves the optimized images
 *
 * This task will run only once, if you want to run it
 * again, do it with the command `gulp images`.
 *
 * Read the following to change these options.
 * @link https://github.com/sindresorhus/gulp-imagemin
 */
gulp.task( 'images', () => {
	return gulp
		.src( config.imgSRC )
		.pipe(
			cache(
				imagemin( [
					imagemin.gifsicle( { interlaced: true } ),
					imagemin.mozjpeg( { quality: 90, progressive: true } ),
					imagemin.optipng( { optimizationLevel: 3 } ), // 0-7 low-high.
					imagemin.svgo( {
						plugins: [ { removeViewBox: false } ]
					} )
				] )
			)
		)
		.pipe( gulp.dest( config.imgDST ) );
} );

/**
 * Task: `clear-images-cache`.
 *
 * Deletes the images cache. By running the next "images" task,
 * each image will be regenerated.
 */
gulp.task( 'clearCache', function( done ) {
	return cache.clearAll( done );
} );

/**
 * Zips theme or plugin and places in the parent directory
 *
 * zipIncludeGlob: Files to be included in the zip file
 * zipIgnoreGlob: Files to be ignored from the zip file
 * zipDestination: Must be a folder outside of the zip folder.
 * zipName: theme.zip or plugin.zip
 */
gulp.task( 'zip', () => {
	var src = [...config.zipIncludeGlob, ...config.zipIgnoreGlob];
	return gulp.src( src ).pipe( zip( config.zipName ) ).pipe( gulp.dest( config.zipDestination ) );
} );

/**
 * Run Tasks.
 *
 * Runs specific tasks.
 */
gulp.task( 'run', gulp.parallel( 'styles', 'vendorsJS', 'globalJS', 'customJS', 'images' ) );

/**
 * Watch Tasks.
 *
 * Watches for file changes and runs specific tasks.
 */
gulp.task(
	'watch',
	gulp.parallel( 'styles', 'vendorsJS', 'globalJS', 'customJS', 'images', () => {
		gulp.watch( config.watchStyles, gulp.parallel( 'styles' ) );
		gulp.watch( config.watchJsVendor, gulp.series( 'vendorsJS' ) );
		gulp.watch( config.watchJsGlobal, gulp.series( 'globalJS' ) );
		gulp.watch( config.watchJsCustom, gulp.series( 'customJS' ) );
		gulp.watch( config.imgSRC, gulp.series( 'images' ) );
	} )
 );

/**
 * Watch Tasks.
 *
 * Watches for file changes and runs specific tasks.
 */
gulp.task(
	'default',
	gulp.parallel( 'styles', 'vendorsJS', 'globalJS', 'customJS', 'images', browsersync, () => {
		gulp.watch( config.watchPhp, reload ); // Reload on PHP file changes.
		gulp.watch( config.watchStyles, gulp.parallel( 'styles' ) ); // Reload on SCSS file changes.
		gulp.watch( config.watchJsVendor, gulp.series( 'vendorsJS', reload ) ); // Reload on vendorsJS file changes.
		gulp.watch( config.watchJsGlobal, gulp.series( 'globalJS', reload ) ); // Reload on globalJS file changes.s.
		gulp.watch( config.watchJsCustom, gulp.series( 'customJS', reload ) ); // Reload on customJS file changes.
		gulp.watch( config.imgSRC, gulp.series( 'images', reload ) ); // Reload on images file changes.
	} )
 );
