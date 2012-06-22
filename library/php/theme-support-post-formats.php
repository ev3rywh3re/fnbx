<?php 
/**
* FNBX Theme Post Formats Support
*
* Actions, filters, and functions used by the FNBX Theme to add post format support.
*
* @package FNBX Theme
* @subpackage Post Format Support
*/


/**
* Activate Post Format Default Support
*
* ISSUE: This is kinda stupid right now. We activate all the approved post formats that
* can be found. Use the fnbx_generate_default_post_formats filter to adjust for taste.
*
* @since 1.0
* @echo string
*/
function fnbx_post_formats_default_setup() {
	$what_post_formats = array_keys( get_post_format_strings() );
	$zero_post_format = array_search( 0, $what_post_formats );
	unset( $what_post_formats[$zero_post_format] );
	$what_post_formats = apply_filters( 'fnbx_generate_default_post_formats',  $what_post_formats );
	add_theme_support( 'post-formats', $what_post_formats );
}
add_action( 'fnbx_loaded', 'fnbx_post_formats_default_setup' );	
