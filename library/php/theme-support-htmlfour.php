<?php 
/**
* FNBX Theme HTML5 Support
*
* Actions, filters, and functions used by the FNBX Theme to create HTML5 things.
*
* @package FNBX Theme
* @subpackage HTML5 Support
*/

function fnbx_filter_layout_elements( $attributes, $element ) {
	
	if ( $element == 'header' ) $attributes['tag'] = 'header';
	if ( $element == 'access' ) $attributes['tag'] = 'div';

	return $attributes;
}
add_filter( 'fnbx_header_options_open', 'fnbx_filter_layout_elements', 1, 2 );
add_filter( 'fnbx_header_options_close', 'fnbx_filter_layout_elements', 1, 2 );

add_filter( 'fnbx_access_element_open_options', 'fnbx_filter_layout_elements', 1, 2 );
add_filter( 'fnbx_access_element_close_options', 'fnbx_filter_layout_elements', 1, 2 );

add_filter( 'fnbx_post_open_options', 'fnbx_filter_layout_elements', 1, 2 );
add_filter( 'fnbx_post_close_options', 'fnbx_filter_layout_elements', 1, 2 );


