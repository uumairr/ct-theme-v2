/**
 * CT Configuration File
 *
 * 1. Edit the variables as per your project requirements.
 * 2. In paths you can add <<glob or array of globs>>.
 *
 * @package Coalition_Technologies
 */

// Project options.

// Local project URL of your already running WordPress site.
// > Could be something like "ct.local" or "localhost"
// > depending upon your local WordPress setup.
const projectURL          = '',

// Theme/Plugin URL. Leave it like it is, since our gulpfile.js lives in the root folder.
	  productURL          = './',
	  injectChanges       = true,
	  browserAutoOpen     = false,

// >>>>> Style options.
// Path to main .scss file.
	  styleSRC            = './assets/source/sass/**/*.scss',

// Path to place the compiled CSS file. Default set to root folder.
	  styleDestination    = './assets/dist/css/',

// Available options → 'compact' or 'compressed' or 'nested' or 'expanded'
	  outputStyle         = 'expanded',
	  errLogToConsole     = true,
	  precision           = 10,

// JS Vendor options.

// Path to JS vendor folder.
	  jsVendorSRC         = './assets/source/js/vendor/**/*.js',

// Path to place the compiled JS vendors file.
	  jsVendorDestination = './assets/dist/js/vendor/',

// JS Custom options.

// Path to JS custom scripts folder.
	  jsCustomSRC         = './assets/source/js/custom/**/*.js',

// Path to place the compiled JS custom scripts file.
	  jsCustomDestination = './assets/dist/js/custom/',

// JS Global options.

// Path to JS global scripts folder.
	  jsGlobalSRC         = './assets/source/js/global/**/*.js',

// Path to place the compiled JS global scripts file.
	  jsGlobalDestination = './assets/dist/js/',

// Compiled JS custom file name. Default set to custom i.e. custom.js.
	  jsGlobalFile        = 'global',

// Images options.

// Source folder of images which should be optimized and watched.
// > You can also specify types e.g. raw/**.{png,jpg,gif} in the glob.
	  imgSRC              = './assets/source/images/**/*',

// Destination folder of optimized images.
// > Must be different from the imagesSRC folder.
	  imgDST              = './assets/dist/images/',

// >>>>> Watch files paths.
// Path to all *.scss files inside css folder and inside them.
	  watchStyles         = './assets/source/sass/**/*.scss',

// Path to all vendor JS files.
	  watchJsVendor       = './assets/source/js/vendor/**/*.js',

// Path to all custom JS files.
	  watchJsCustom       = './assets/source/js/custom/**/*.js',

// Path to all global JS files.
	  watchJsGlobal       = './assets/source/js/global/**/*.js',

// Path to all PHP files.
	  watchPhp            = './**/*.php',

// >>>>> Zip file config.
// Must have.zip at the end.
	  zipName             = 'ct-theme.zip',

// Must be a folder outside of the zip folder.
	  zipDestination      = './../', // Default: Parent folder.
	  zipIncludeGlob      = [ './**/*' ], // Default: Include all files/folders in current directory.

// Default ignored files and folders for the zip file.
	  zipIgnoreGlob       = [
			'!./{node_modules,node_modules/**/*}',
			'!./.git',
			'!./.svn',
			'!./gulpfile.babel.js',
			'!./ct.config.js',
			'!./.eslintrc.js',
			'!./.eslintignore',
			'!./.editorconfig',
			'!./phpcs.xml.dist',
			'!./vscode',
			'!./package.json',
			'!./package-lock.json',
		],

// Package name.
	  packageName         = 'ct',

// Browsers you care about for auto-prefixing. Browserlist https://github.com/ai/browserslist
// The following list is set as per WordPress requirements. Though; Feel free to change.
	  BROWSERS_LIST       = [ 'last 40000 versions' ];

// Export.
	  module.exports      = {
			projectURL,
			productURL,
			browserAutoOpen,
			injectChanges,
			styleSRC,
			styleDestination,
			outputStyle,
			errLogToConsole,
			precision,
			jsVendorSRC,
			jsVendorDestination,
			jsCustomSRC,
			jsCustomDestination,
			jsGlobalSRC,
			jsGlobalDestination,
			jsGlobalFile,
			imgSRC,
			imgDST,
			watchStyles,
			watchJsVendor,
			watchJsCustom,
			watchJsGlobal,
			watchPhp,
			zipName,
			zipDestination,
			zipIncludeGlob,
			zipIgnoreGlob,
			packageName,
			BROWSERS_LIST
		};
