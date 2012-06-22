<?php
/**
* Funbox Base Theme Filter Functions
*
* Filter functions used in various add_filter by the Funbox Base Theme.
*
* @package Funbox Base Theme
* @subpackage Hooks
*/


/**
* Body Class Filter
*
* Adds various sematic classes the BODY tag.
*
* @since 1.0
* @return array
*/
function fnbx_body_class_filter( $classes ) {
	global $wp_query, $current_user;

	// It's surely a WordPress blog, right?
	$classes[] = 'wordpress';

	// Applies the time- and date-based classes (below) to BODY element
	//$date_classes = fnbx_date_classes( time() );
	$classes = array_merge( $classes, fnbx_date_classes( time() ) );

	// Special classes for BODY element when a single post
	if ( is_single() ) {
		the_post();

		// Adds classes for the month, day, and hour when the post was published
		if ( isset( $wp_query->post->post_date ) )
			$classes = array_merge( $classes, fnbx_date_classes( mysql2date( 'U', $wp_query->post->post_date ), 's-' ) );

		// Adds category classes for each category on single posts
		if ( $cats = get_the_category() )
			foreach ( $cats as $cat )
				$classes[] = 's-category-' . $cat->slug;

		// Adds tag classes for each tags on single posts
		if ( $tags = get_the_tags() )
			foreach ( $tags as $tag )
				$classes[] = 's-tag-' . $tag->slug;

		// Adds author class for the post author
		$classes[] = 's-author-' . sanitize_title_with_dashes( strtolower( get_the_author_meta( 'login' ) ) );

		rewind_posts();
	// Page author for BODY on 'pages'
	} elseif ( is_page() ) {
		the_post();
		$classes[] = 'page pageid-' . $wp_query->post->ID;
		$classes[] = 'page-author-' . sanitize_title_with_dashes( strtolower( get_the_author_meta('login') ) );

		rewind_posts();
	}

	if ( is_singular() ) $classes[] = 's-' . str_ireplace( '/', '', get_page_uri( $wp_query->post->ID ) );

	$widget_groups = wp_get_sidebars_widgets();
	foreach ( $widget_groups as $widget_group => $widget_elements ) {
		if ( $widget_group == 'wp_inactive_widgets' ) continue;
		$classes[] =  'widgets-' . sanitize_title_with_dashes( $widget_group ) . ( empty( $widget_elements ) ? '-inactive' : '-active' );
	}
	
	$classes = apply_filters( 'fnbx_body_class',  $classes );

	return $classes;
}

/**
* Content hFeed Class Filter
*
* Adds class microformat hfeed to posts and archives
*
* @since 1.0
* @return array
*/
function fnbx_hfeed_class_filter( $classes ) {
	if ( !is_page() ) $classes[] = 'hfeed';
	return $classes;
}

/**
* Post Class Filter
*
* Adds various sematic classes to post HTML containers.
*
* @since 1.0
* @return array
*/
function fnbx_post_class_filter( $classes ) {
	global $post;

	if ( !is_singular() ) {
		global $fnbx_alternating_posts;
		switch ( $fnbx_alternating_posts ) {
			case 'even':
				$fnbx_alternating_posts = 'odd';
				$classes[] = 'post-alternate';
				break;
			default:
				$fnbx_alternating_posts = 'even';
				break;
		}
	}

	// hentry for hAtom compliace, gets 'alt' for every other post DIV, describes the post type and p[n]
	$classes[] = $post->post_status;

	// add the post ID with the dash as class to differentiate id
	$classes[] = 'post-' . $post->ID . '-';	

	// Author for the post queried
	$classes[] = 'author-' . sanitize_title_with_dashes( strtolower( get_the_author_meta( 'login' ) ) );

	// For password-protected posts
	if ( $post->post_password )
		$classes[] = 'protected';

	// Applies the time- and date-based classes (below) to post DIV
	$classes = array_merge( $classes, fnbx_date_classes( mysql2date( 'U', $post->post_date ) ) );

	// And tada!
	return $classes;
}

/**
* Post entry Class Filter
*
* Adds class entry posts and pages
*
* @since 1.0
* @return array
*/
function fnbx_entry_class_filter( $classes ) {
	$classes[] = 'entry';
	return $classes;
}

/**
* Comment Class Filter
*
* Adds various sematic classes to comment HTML container.
*
* @since 1.0
* @return array
*/
function fnbx_comment_class_filter( $classes, $class, $comment_id, $post_id) {
	$classes = array_merge( $classes, fnbx_date_classes( get_comment_date( 'U' ) ) );
	
	$comment = get_comment($comment_id);

	if ( $post = get_post($post_id) ) {
		if ( $comment->user_id != $post->post_author )
			$classes[] = 'bypostauthor-no';
	}	
	
	return $classes;
}

/**
* Website Title Filter
*
* Switches the main website title between H1 for home page and DIV for sub-pages.
*
* @since 1.0
* @return array
*/
function fnbx_header_title_filter( $title_defaults ) {
	if( !is_front_page() ) $title_defaults['tag'] = 'div';
	return $title_defaults;
}

/**
* Entry Title Filter
*
* Converts the shortcode for entry titles to simply use the titles for single entries
*
* @since 1.0
* @return string
*/
function fnbx_entry_title_shortcode_filter( $shortcode_defaults ) {
	if( is_single() || is_page() ) $shortcode_defaults = '[title]';
	return $shortcode_defaults;
}

/**
* Entry Title Tag Filter
*
* Switches the titles to H2 from H1 for various views such as website home page, archives, and attachments.
*
* @since 1.0
* @return array
*/
function fnbx_entry_title_filter( $title_defaults ) {

	// Test tag change
	$test_is_group = array(
		'is_home',
		'is_front_page',
		'is_archive',
		'is_attachment'
	);

	foreach( $test_is_group as $test_is ) {
		if ( fnbx_test_is( $test_is ) ) $title_defaults['tag'] = 'h2';
	}	

	return $title_defaults;
}
