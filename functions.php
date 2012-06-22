<?php
/**
 * FNBX theme functions file
 *
 * Includes files for theme framework and defines 
 * constants for theme and child themes. Initializes 
 * annoying nag as an inside joke.
 *
 * @package FNBX Theme
 * @subpackage Functions
 */

// Define core FNBX Theme path constants
define( 'FNBX_DIR', get_template_directory() );
define( 'FNBX_URI', get_template_directory_uri() );
define( 'FNBX_LIBRARY', FNBX_DIR . '/library' );
define( 'FNBX_LANGUAGES', FNBX_LIBRARY . '/languages' );

// Load FNBX Theme language localization files
load_theme_textdomain( 'fnbx_lang', FNBX_LANGUAGES );

// Define child theme path constants.
if ( !defined( 'FNBX_CHILD_DIR' ) ) define( 'FNBX_CHILD_DIR', get_stylesheet_directory() );
if ( !defined( 'FNBX_CHILD_URL' ) ) define( 'FNBX_CHILD_URL', get_stylesheet_directory_uri() );

// Load FNBX Theme core class used everywhere and initiate the default $fnbx object
require_once( FNBX_LIBRARY . '/php/class-core.php' );

// The fnbx-loader.php and fnbx.php files can be included by child themes and used to inititialize the view.
get_template_part( 'fnbx', 'loader' );

// Action to fire before $fnbx is initialized and FNBX theme core functions are loaded
do_action( 'fnbx_pre_init');

// Initialize the fnbx object (FNBX Themee Framework).
$fnbx = new fnbx();

// Load FNBX Theme core functions used everywhere
require_once( FNBX_LIBRARY . '/php/functions-utilities.php' );

// Load FNBX Theme template functions
require_once( FNBX_LIBRARY . '/php/functions-template.php' );

// Load FNBX Theme support for post thumbnails
if ( function_exists( 'add_theme_support' ) ) {
	
	// The check for add_theme_support() means you need filter support if feature is unsupported!
	if( $fnbx->theme_support['automatic-feed-links'] ) add_theme_support( 'automatic-feed-links' );
	
	// This theme styles the visual editor with editor-style.css to match the theme style.
	if( $fnbx->theme_support['editor-style'] ) add_editor_style();
	
	// Post thumbnail support added by additional function file
	if( $fnbx->theme_support['post-thumbnails'] ) require_once( FNBX_LIBRARY . '/php/theme-support-post-thumbnails.php' );
	
	// Nav menu support added by additional function file
	if( $fnbx->theme_support['nav-menu'] ) require_once( FNBX_LIBRARY . '/php/theme-support-nav-menu.php' );

	// Post formats support added by additional function file
	if( $fnbx->theme_support['post-formats'] ) require_once( FNBX_LIBRARY . '/php/theme-support-post-formats.php' );

	// Custom header support added by additional function file
	if( $fnbx->theme_support['custom-header'] ) require_once( FNBX_LIBRARY . '/php/theme-support-custom-header.php' );

	// Custom background support added by additional function file
	if( $fnbx->theme_support['custom-background'] ) require_once( FNBX_LIBRARY . '/php/theme-support-custom-background.php' );
}
			
// Load FNBX Theme widget functions and widgets
require_once( FNBX_LIBRARY . '/php/functions-widgets.php' );

// Load FNBX Theme filter utility functions
require_once( FNBX_LIBRARY . '/php/functions-filters.php' );

// BETA! BuddyPress Support
if ( class_exists( 'BP_Core_User' ) ) {
	require_once( FNBX_LIBRARY . '/php/functions-buddypress.php' );
	define( 'FNBX_BUDDYPRESS', true );
}

// Action to fire after FNBX theme core functions are loaded
do_action( 'fnbx_loaded');
