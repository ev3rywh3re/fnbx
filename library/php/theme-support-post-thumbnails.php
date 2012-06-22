<?php 
/**
* FNBX Theme Post Thumbnail Support
*
* Actions, filters, and functions used by the FNBX Theme to add post thumbnail support.
*
* @package FNBX Theme
* @subpackage Post Thumbnail Support
*/


/**
* Activate Post Thumbnail Support
*
* ISSUE: This is kinda stupid right now. It's a wrapper to add_theme_support('post-thumbnails') 
* function, set default size of 100x100, add thumbnail as default image fnbx-post-thumbnail
*
* @since 1.0
* @echo string
*/
function fnbx_post_thumbnails_default_setup() {
	add_theme_support('post-thumbnails');
	set_post_thumbnail_size( 100, 100, true );
	// See http://markjaquith.wordpress.com/2009/12/23/new-in-wordpress-2-9-post-thumbnail-images/
	// add_image_size( 'fnbx-single-post-thumbnail', 400, 9999 ); // Register 400xInf image
	// the_post_thumbnail( 'single-post-thumbnail' ); // Use in theme template loop to call image
}

/**
* FNBX Post Thumbnail
*
* Writes post thumbnail HTML. Basically a wrapper for the_post_thumbnail WP function.
*
* @since 1.0
* @echo string
*/
// ISSUE: Incomplete. Needs filtering
function fnbx_the_post_thumbnail( $post_id = NULL, $size = 'post-thumbnail', $attr = '' ) {
	global $id;
	$post_id = ( NULL === $post_id ) ? $id : $post_id;
	
	add_filter( 'post_thumbnail_html', 'fnbx_post_thumbnail_html', 1, 4);
	echo get_the_post_thumbnail( $post_id, $size, $attr );
	remove_filter( 'post_thumbnail_html', 'fnbx_post_thumbnail_html' );
	
	return;
}

/**
* FNBX Get Post Thumbnail
*
* Returns post thumbnail HTML. Basically a wrapper for get_the_post_thumbnail WP function.
*
* @since 1.0
* @echo string
*/
// ISSUE: Incomplete. Needs filtering
function fnbx_get_the_post_thumbnail( $post_id = NULL, $size = 'post-thumbnail', $attr = '' ) {
	global $id;
	$post_id = ( NULL === $post_id ) ? $id : $post_id;
	
	add_filter( 'post_thumbnail_html', 'fnbx_post_thumbnail_html', 1, 4);
	$t_image = get_the_post_thumbnail( $post_id, $size, $attr );
	remove_filter( 'post_thumbnail_html', 'fnbx_post_thumbnail_html' );
	
	return $t_image;
}

/**
* FNBX Post Thumbnail HTML
*
* Filter the wrapper HTML for FNBX post thumbnails. Added by action to WordPress core action: begin_fetch_post_thumbnail_html
*
* @since 1.0
* @echo string
*/
function fnbx_post_thumbnail_html() {

	$args = func_get_args();
	if ( empty( $args ) || empty( $args[0] ) ) return $args[0];
	
	$class = array( 'thumbnail-container' );
	
	$html_defaults = array(
		'tag' => 'div',
		'tag_content' => $args[0],
		'tag_content_before' => "\n",
		'tag_content_after' => "\n",
		'return' => true
	);
	
	// is $size
	if ( isset( $args[3] ) ) {
		if( is_array( $args[3] ) )
			$size_class = implode( '-', $args[3] );
		else
			$size_class = $args[3];
		$class[] = 't-size-' . $size_class . '-container';
	}
	if ( isset( $args[2] ) ) {
		$class[] = 'thumbnail-'. $args[2] . '-'; // is $post_thumbnail_id 
		$html_defaults['id'] = 'thumbnail-'. $args[2];
	}
		
	if ( !empty( $class ) ) $html_defaults['class'] = implode( ' ', $class );
		
	return fnbx_html_tag( $html_defaults );
	
}
