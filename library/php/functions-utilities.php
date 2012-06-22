<?php
/**
* Funbox Base Theme Utilities
* 
* Utility functions used by the Funbox Base Theme
*
* @package Funbox Base Theme
* @subpackage Functions
*/

/**
 * Copy an object.
 *
 * Returns a cloned copy of an object.
 * Taken from the deprecated wp_clone() function.
 *
 * @param object $object The object to clone
 * @return object The cloned object
 */
function fnbx_clone( $object ) {
	static $can_clone;
	if ( !isset( $can_clone ) ) {
		$can_clone = version_compare( phpversion(), '5.0', '>=' );
	}
	return $can_clone ? clone( $object ) : $object;
}

/**
* Funbox Date Class Function
*
* Taken from the old Sandbox Theme. The function provides date classes 
* message pandering for donations for Funbox. Drop a dime in the bucket if you like, but it's really 
* a ridiculous message to urge you to find or develop a child theme. Laugh, cry, or remove the code!
*
* @since 1.0
*/
function fnbx_date_classes( $t, $p = '' ) {
	$c = array();
	$t = $t + ( get_option('gmt_offset') * 3600 );
	$c[] = $p . 'y' . gmdate( 'Y', $t ); // Year
	$c[] = $p . 'm' . gmdate( 'm', $t ); // Month
	$c[] = $p . 'd' . gmdate( 'd', $t ); // Day
	$c[] = $p . 'h' . gmdate( 'H', $t ); // Hour
	return $c;
}

/**
* Funbox HTML Tag
*
* Core utility function for the writing and manipulation of HTML tags.
*
* @since 1.0
* @echo string
*/
if ( !function_exists( 'fnbx_html_tag' ) ) {
	function fnbx_html_tag( $html = array() ) {
	
		$attributes = '';
		$composite = '';
		$spacer = '';
		if ( !isset( $html['return'] ) ) $html['return'] = false;
		$reserved = array(
			'tag', 'tag_type', 'attributes', 'tag_content', 'tag_content_before', 'tag_content_after', 'return'
		);
	
		foreach ( $html as $name => $option ) {
			if ( in_array( $name, $reserved ) ) continue;
			$attributes .= $name . '="' . $option . '" ';
		}
		
		if ( isset( $html['attributes'] ) ) $attributes .= $html['attributes'] . ' ' . $attributes;
		
		if ( $attributes != '' ) {
			$attributes = rtrim( $attributes );
			$spacer = ' ';
		}
		
		if ( !isset( $html['tag_type'] ) ) $html['tag_type'] = 'default';
		
		if ( isset( $html['tag_content_before'] ) ) $composite .= $html['tag_content_before'];
		
		switch ( $html['tag_type'] ) {
			case 'single':
				if ( isset( $html['tag_content'] ) ) $composite .= $html['tag_content'];
				if ( isset( $html['tag'] ) ) $composite .= '<' . $html['tag'] . $spacer . $attributes . '/>';
				break;
			case 'open':
				if ( isset( $html['tag'] ) ) $composite .= '<' . $html['tag'] . $spacer . $attributes . '>';
				if ( isset( $html['tag_content'] ) ) $composite .= $html['tag_content'];			
				break;
			case 'close':
				if ( isset( $html['tag_content'] ) ) $composite .= $html['tag_content'];		
				if ( isset( $html['tag'] ) ) $composite .= '</' . $html['tag'] . '>';
				break;
			case 'attributes':
				$composite = $attributes;
				break;			
			case 'default':
				if ( isset( $html['tag'] ) ) $composite .= '<' . $html['tag'] . $spacer . $attributes . '>';
				if ( isset( $html['tag_content'] ) ) $composite .= $html['tag_content'];
				if ( isset( $html['tag'] ) ) $composite .= '</' . $html['tag'] . '>';			
				break;
		}
		
		if ( isset( $html['tag_content_after'] ) ) $composite .= $html['tag_content_after'];	
		
		if ( $html['return'] == true ) return $composite ;
	
		echo $composite;
	}
}

/**
* Rip off WordPress get_the_id() but return zero if necessary.
*
* @since 1.0
* @echo string
*/
function fnbx_get_the_id() {
	global $post;
	if ( !isset( $post->ID ) || empty( $post->ID ) ) return 0;
	return $post->ID;
}

/**
* Write out a meta tag merged with attributes from array
*
* @since 1.0
* @echo string
*/
function fnbx_write_meta_tag( $tag_merge = array() ) {
	if ( empty( $tag_merge ) ) return false;

	$meta_tag_default = array( 
		'tag_type' => 'single',
		'tag' => 'meta',
		'tag_content_after' => "\n"
	);

	fnbx_html_tag( array_merge( $meta_tag_default, $tag_merge ) );
}

/**
* Write out a link tag merged with attributes from array
*
* @since 1.0
* @echo string
*/
function fnbx_write_link_tag( $tag_merge = array() ) {
	if ( empty( $tag_merge ) ) return false;

	$meta_tag_default = array( 
		'tag_type' => 'single',
		'tag' => 'link',
		'tag_content_after' => "\n"
	);

	fnbx_html_tag( array_merge( $meta_tag_default, $tag_merge ) );
}

function fnbx_message( $message = '', $return = false, $message_classes = array() ) {

	$message = apply_filters( 'fnbx_error_message_class',  $message );

	$message_classes = apply_filters( 'fnbx_error_message_class',  array_merge( $message_classes, array( 'message' ) ) );

	$form_message_array = array(
		'tag' => 'div',
		'class' => implode( ' ', $message_classes ),
		'tag_content' => $message
	);

	if ( $return == true ) return fnbx_html_tag( $form_message_array );

	fnbx_html_tag( $form_message_array );	
}


function fnbx_form_container( $name = '', $content = '', $return = false ) {

	$form_container_defaults = array(
		'tag' => 'div',
		'id' => 'form-' . $name . '-container',
		'class' => 'form-' . $name . '-container-',
		'tag_content' => $content,
		'tag_content_before' => "\n",
		'tag_content_after' => "\n",
		'return' => $return
	);

	$form_container_defaults = apply_filters( 'fnbx_form_container_' . $name,  $form_container_defaults );

	if ( $return == true ) return fnbx_html_tag( $form_container_defaults );

	fnbx_html_tag( $form_container_defaults );	
}

/**
* Write out a form tag merged with attributes from array
*
* @since 0.4
*/
function fnbx_form( $name = '', $action = '', $method = '', $form_content = '', $return = false ) {

	$form_array = array( 
		'tag' => 'form',
		'id' => $name,
		'name' => $name,
		'enctype' => 'application/x-www-form-urlencoded',		
		'action' => $action,
		'method' => $method,
		'tag_content' => $form_content,
		'tag_content_before' => "\n",
		'tag_content_after' => "\n",
		'return' => true
	);

	$form_array = apply_filters( 'fnbx_form_' . $name,  $form_array );	

	$form_composite = fnbx_html_tag( $form_array );

	if ( $return == true ) return fnbx_form_container( $name, $form_composite, $return );

	fnbx_form_container( $name, $form_composite, $return );
}


/**
* Write out a form fieldset tag with legend merged with attributes from array
*
* @since 0.4
*/
function fnbx_fieldset( $legend_text = '', $fieldset_content = '', $return = false ) {

	$legend_tag = fnbx_html_tag( array(
		'tag' => 'legend',
		'tag_content' => $legend_text,
		'tag_content_after' => "\n",
		'return' => true
	) );

	$fieldset_array = array(
		'tag' => 'fieldset',
		'tag_content' => $legend_tag . $fieldset_content,
		'tag_content_after' => "\n",
		'return' => $return
	);

	if ( $return == true ) return fnbx_html_tag( $fieldset_array );

	fnbx_html_tag( $fieldset_array );		
}

/**
* Write out a form input tag merged with attributes from array
*
* $args are mixed arrays. First $args array should contain the following keys:
* - type: HTML input tag type.
* - name: String to be used for oject HTML class and id names
* - value: Default value for form element.
* - return: Boolean to return string or echo HTML
*
* @param array $args
* @since 0.4
* @return string|void
*/ 
function fnbx_form_input() {

    $arg_list = func_get_args();  
	if ( !is_array( $arg_list[0] ) ) return;		

	$input_tag = array(
		'tag_type' => 'single',
		'tag' => 'input',
		'id' => $arg_list[0]['name'],
		'name' => $arg_list[0]['name'],		
		'class' => 'input-' . $arg_list[0]['name'] . '-',
		'type' => $arg_list[0]['type'],
		'value' => $arg_list[0]['value'],
		'return' => $arg_list[0]['return']
	);

	if ( isset( $arg_list[1] ) && is_array( $arg_list[1] ) )
		$input_tag = array_merge( $input_tag, $arg_list[1] );		

	if ( $arg_list[0]['return'] == true ) return fnbx_html_tag( $input_tag );

	fnbx_html_tag( $input_tag );
}

/**
* Write out a form row with label and input tag merged with attributes from array
*
* $args are mixed arrays. First $args array should contain the following keys:
* - label: HTML label tag for element with text used as content.
* - type: HTML input tag type.
* - name: String to be used for oject HTML class and id names
* - value: Default value for form element.
* - return: Boolean to return string or echo HTML
*
* A second array prarmater, $input_override array is designed to override elements for the inclosed input html element.
*
* @param array $args
* @param array $input_override 
* @since 0.4
* @return string|void
*/ 
function fnbx_form_input_row() {

    $arg_list = func_get_args();  
	if ( !is_array( $arg_list[0] ) ) return;

	$label_tag_defaults = array(
		'tag' => 'label',
		'id' => 'label-' . $arg_list[0]['name'],		
		'class' => 'label-' . $arg_list[0]['name'] . '-',
		'for' => $arg_list[0]['name'],
		'tag_content' => $arg_list[0]['label'],
		'return' => true
	);

	$label_tag = fnbx_html_tag( $label_tag_defaults );

	$input_tag_defaults = array( 
		'type' => $arg_list[0]['type'],
		'name' => $arg_list[0]['name'],
		'value' => $arg_list[0]['value'],
		'return' => true
	);
	if ( isset( $arg_list[1] ) && is_array( $arg_list[1] ) )
		$input_tag = fnbx_form_input( $input_tag_defaults, $arg_list[1] );
	else
		$input_tag = fnbx_form_input( $input_tag_defaults );

	$input_span = fnbx_html_tag( array(
		'tag' => 'span',
		'class' => 'form-element',
		'tag_content' => $input_tag,
		'return' => true
	) );

	$form_row = array(
		'tag' => 'div',
		'class' => 'form-row',
		'tag_content' => $label_tag . $input_span,
		'return' => $arg_list[0]['return']
	); 

	if ( $arg_list[0]['label'] == true ) return fnbx_html_tag( $form_row );

	fnbx_html_tag( $form_row );
}

/**
* Write out a form button tag merged with attributes from array
*
* $args are mixed arrays. First $args array should contain the following keys:
* - type: HTML input tag type.
* - name: String to be used for oject HTML class and id names
* - value: Default value for form element.
* - return: Boolean to return string or echo HTML
* - tag_content: for this case a button can have enclosed elements (defaults to value)
*
* @param array $args
* @since 0.4
* @return string|void
*/ 
function fnbx_form_button() {

    $arg_list = func_get_args();  
	if ( !is_array( $arg_list[0] ) ) return;		
	
	$input_tag = array(
		'tag' => 'button',
		'id' => $arg_list[0]['name'],
		'name' => $arg_list[0]['name'],		
		'class' => 'button-' . $arg_list[0]['name'] . '-',
		'type' => $arg_list[0]['type'],
		'value' => $arg_list[0]['value'],
		'tag_content' => $arg_list[0]['tag_content'],		
		'return' => $arg_list[0]['return']
	);

	if ( !isset($arg_list[0]['tag_content']) ) $input_tag['tag_content'] = $input_tag['value'];

	if ( is_array( $arg_list[1] ) )
		$input_tag = array_merge( $input_tag, $arg_list[1] );		
			
	if ( $arg_list[0]['return'] == true ) return fnbx_html_tag( $input_tag );

	
	fnbx_html_tag( $input_tag );
}

// Default condition tests that surf through wp_query or is_* functions
function fnbx_test_is( $type = false ) {
	global $wp_query;

	$test_queries = array(
		'is_single',
		'is_page',
		'is_archive',
		'is_date',
		'is_year',
		'is_month',
		'is_day',
		'is_time',
		'is_author',
		'is_category',
		'is_tag',
		'is_tax',
		'is_search',
		'is_feed',
		'is_comment_feed',
		'is_trackback',
		'is_home',
		'is_404',
		'is_paged',
		'is_admin',
		'is_attachment',
		'is_singular',
		'is_robots',
		'is_posts_page',
		'is_front_page',
		'comments_open',
	);

	if ( !in_array( $type, $test_queries ) ) return false;

	if( !isset( $wp_query->$type ) ) {
		$the_test = call_user_func( $type );
	} else {
		$the_test = $wp_query->$type;
	}

	return $the_test;

}

// Meta - Parse string for shortcodes
function fnbx_parse_meta_shortcode( $content = '' ) {

	$meta_shortcodes = array( 
		'meta-block',
		'separator',
		'title',
		'title_parent',
		'author',
		'author_name',
		'author_link',
		'date-abbr',
		'date',
		'time',
		'category',
		'category_text',
		'category_links',
		'tag',
		'tag_text',
		'tag_links',
		'permalink_link',
		'parent_link',
		'comments_rss',
		'comments_rss_link',
		'feedback',
		'feedback_separator',
		'comments',
		'comments_number',
		'comments_link',
		'pings',
		'pings_link',
		'edit',
		'edit_link'
	);

	$meta_shortcodes_callbacks = array(
		'fnbx_do_meta_shortcode'	
	);

	// Allow alternate shortcode parsing before standard parse
	$meta_shortcodes = apply_filters( 'fnbx_meta_shortcodes',  $meta_shortcodes );
	$meta_shortcodes_callbacks = apply_filters( 'fnbx_meta_shortcodes_callbacks', $meta_shortcodes_callbacks );

	foreach ( $meta_shortcodes_callbacks as $shortcode_callback ) {
		$meta_shortcodes_regexp = join( '|', array_map( 'preg_quote', $meta_shortcodes ) );	
		$pattern =  '\[('.$meta_shortcodes_regexp.')\b(.*?)(?:(\/))?\](?:(.+?)\[\/\1\])?';
		$content = preg_replace_callback('/'.$pattern.'/s', $shortcode_callback, $content );	
	}

	return $content;
}

// Meta - Shortcodes logic
function fnbx_do_meta_shortcode( $input = '' ) {
	global $wp_query, $posts, $post, $authordata;

	if ( !isset( $post ) ) return;
	
	switch( $input[1] ) {
		case 'title':
			$tmp_title = get_the_title( $post->ID );
			if ( empty( $tmp_title ) ) $tmp_title = '<span class="untitled">[untitled]</span>';
			return $tmp_title;
		case 'title_parent':
			return get_the_title( $post->post_parent );			
		case 'date':
			return mysql2date( get_option('date_format'), $post->post_date );
		case 'time':
			return get_the_time();
		case 'author':
			if ( is_author() ) {
				$attr = shortcode_parse_atts( $input[2] );
				if ( $attr['pagetitle'] != 'true' ) return;
			}
			break;
		case 'author_name':
			return get_the_author();
		case 'category':			
			$cats = wp_get_object_terms( $post->ID, 'category');
			$current_cat = intval( get_query_var('cat') );
			foreach ( $cats as $i => $cat_obj ) {
				if ( $cat_obj->term_id == $current_cat ) {
					unset( $cats[$i] );
					break;
				}
			}
			if ( empty( $cats ) ) return;
			break;
		case 'tag':
			$tags = wp_get_object_terms( $post->ID, 'post_tag');
			$current_tag = intval( get_query_var('tag_id') );
			foreach ( $tags as $i => $tag_obj ) {
				if ( $tag_obj->term_id == $current_tag ) {
					unset( $tags[$i] );
					break;
				}
			}
			if ( empty( $tags ) ) return;
			break;
		case 'comments_rss':
			$attr = shortcode_parse_atts( $input[2] );
			if ( !('open' == $post->comment_status) ) return $attr['closed']; 
			break;
		case 'feedback':
			$attr = shortcode_parse_atts( $input[2] );
			if ( !('open' == $post->comment_status) && !('open' == $post->ping_status) ) return $attr['closed'];
			break;
		case 'feedback_separator':
			$attr = shortcode_parse_atts( $input[2] );
			if ( !('open' == $post->comment_status) && !('open' == $post->ping_status) ) return $attr['both_closed'];
			if ( !('open' == $post->comment_status) ) return $attr['comments_closed'];
			if ( !('open' == $post->ping_status) ) return $attr['pings_closed'];
			break;
		case 'comments':
			$attr = shortcode_parse_atts( $input[2] );
			if ( !('open' == $post->comment_status) ) return $attr['closed']; 
			break;
		case 'comments_number':
			$attr = shortcode_parse_atts( $input[2] );
			if ( !('open' == $post->comment_status) ) return $attr['closed'];
			if ( $post->comment_count == 0 ) return $attr['no_comments'];
			return $post->comment_count;
		case 'comments_link':
			if ( !('open' == $post->comment_status) ) return; 
			break;
		case 'pings':
			$attr = shortcode_parse_atts( $input[2] );
			if ( !('open' == $post->ping_status) ) return $attr['closed']; 
			break;
		case 'pings_link':
			if ( !('open' == $post->ping_status) ) return; 
			break;
		case 'edit':
			$edit_link = get_edit_post_link();
			if( !strstr( $edit_link, 'action=edit' ) ) return;
			break;
	}

	if ( isset($input[4]) ) { 
		$content = fnbx_parse_meta_shortcode( $input[4] );
	}

	switch( $input[1] ) {
		case 'meta-block':
			$attr = shortcode_parse_atts( $input[2] );
			$html_tag = ( isset( $attr['tag'] ) ? $attr['tag'] : 'span');
			$html_class = ( isset( $attr['class'] ) ? $attr['class'] : 'meta-block');
			$meta_content = fnbx_html_tag( array(
				'tag' => $html_tag,
				'class' => $html_class,
				'tag_content' => $content,
				'return' => true
			) );
			break;
		case 'separator':
			$meta_content = fnbx_html_tag( array(
				'tag' => 'span',
				'class' => 'meta-separator',
				'tag_content' => $content,
				'return' => true
			) );
			break;
		case 'author':
			$meta_content = $content;
			break;
		case 'date-abbr':
			$meta_content = fnbx_html_tag( array(
				'tag' => 'abbr',
				'class' => 'published',
				'title' => get_the_time('Y-m-d') . 'T' . get_the_time('H:i:sO'),
				'tag_content' => $content,
				'return' => true
			) );
			break;			
		case 'author_link':
			if ( !isset( $authordata ) ) break;
			$attr = shortcode_parse_atts( $input[2] );
			$title_prefix = ( isset( $attr['title'] ) ? $attr['title'] : '');
			$author_link = fnbx_html_tag( array(
				'tag' => 'a',
				'class' => 'url fn n',
				'href' => get_author_posts_url( $authordata->ID, $authordata->user_nicename ),
				'title' => $title_prefix . $authordata->display_name,
				'tag_content' => $content,
				'return' => true
			) );
			$meta_content = fnbx_html_tag( array(
				'tag' => 'span',
				'class' => 'author vcard author-'. $authordata->user_nicename,
				'tag_content' => $author_link,
				'return' => true
			) );
			break;
		case 'category':
			$meta_content = $content;
			break;
		case 'category_text':
			if( is_category() ) {
				$attr = shortcode_parse_atts( $input[2] );
				$meta_content = ( isset( $attr['is_category'] ) ? $attr['is_category'] : '');
				break;
			}
			$meta_content = $content;
			break;			
		case 'category_links':
			$current_cat = single_cat_title( '', false );
			$cats = explode( '|', get_the_category_list( '|', '', $post->ID ) );
			/*
			ISSUE: Something wrong here... you can send the ID to get_the_term_list, it passes to a
			get cat function that is asking for an ID but is getting an object.
			On some PHP CGI systems it doesn't seem to pass through properly.

			I want to use:
			$cats = explode( '|', get_the_term_list( $post->ID, 'category', '', '|', '' ) );
			*/
			foreach ( $cats as $i => $str ) {
				if ( strstr( $str, ">$current_cat<" ) ) {
					unset($cats[$i]);
					break;
				}
			}
			if ( empty( $cats ) ) return;
			$meta_content = implode( ', ', $cats );
			break;
		case 'tag':
			$meta_content = $content;
			break;
		case 'tag_text':
			if( is_tag() ) {
				$attr = shortcode_parse_atts( $input[2] );
				$meta_content = ( isset( $attr['is_tag'] ) ? $attr['is_tag'] : '');
				break;
			}
			$meta_content = $content;
			break;				
		case 'tag_links':
			$current_tag = single_tag_title( '', false );
			$tags = explode( '|', get_the_term_list( $post->ID, 'post_tag', '', '|', '' ) );
			foreach ( $tags as $i => $str ) {
				if ( strstr( $str, ">$current_tag<" ) ) {
					unset($tags[$i]);
					break;
				}
			}
			if ( empty( $tags ) ) return;
			$meta_content = implode( ', ', $tags );
			break;
		case 'permalink_link':
			$attr = shortcode_parse_atts( $input[2] );
			$title_prefix = ( isset( $attr['title'] ) ? $attr['title'] : '');
			$meta_content = fnbx_html_tag( array(
				'tag' => 'a',
				'class' => 'permalink',
				'href' => get_permalink( $post->ID ),
				'title' => $title_prefix . get_the_title( $post->ID ),
				'tag_content' => $content,
				'return' => true
			) );
			break;
		case 'parent_link':
			$attr = shortcode_parse_atts( $input[2] );
			$title_prefix = ( isset( $attr['title'] ) ? $attr['title'] : '');
			$meta_content = fnbx_html_tag( array(
				'tag' => 'a',
				'class' => 'permalink-parent',
				'href' => get_permalink( $post->post_parent ),
				'title' => $title_prefix . get_the_title( $post->post_parent ),
				'tag_content' => $content,
				'return' => true
			) );
			break;			
		case 'comments_rss':
			$meta_content = $content;
			break;
		case 'comments_rss_link':
			$title_prefix = ( isset( $attr['title'] ) ? $attr['title'] : '');
			$meta_content = fnbx_html_tag( array(
				'tag' => 'a',
				'class' => 'link-rss-comments',
				'href' => get_post_comments_feed_link(),
				'rel' => 'alternate',
				'type' => 'application/rss+xml',
				'title' => $title_prefix . the_title_attribute('echo=0'),
				'tag_content' => $content,
				'return' => true
			) );
			break;
		case 'feedback':
			$meta_content = $content;
			break;
		case 'feedback_separator':
			$meta_content = $content;
			break;
		case 'comments':
			$meta_content = $content;
			break;
		case 'comments_link':
			$attr = shortcode_parse_atts( $input[2] );
			$title_prefix = ( isset( $attr['title'] ) ? $attr['title'] : '');
			$meta_content = fnbx_html_tag( array(
				'tag' => 'a',
				'class' => 'link-comments',
				'href' => ( is_single() ) ? '#respond' : get_permalink() . '#comments',
				'title' => $title_prefix . the_title_attribute('echo=0'),
				'tag_content' => $content,
				'return' => true
			) );
			break;
		case 'pings':
			$meta_content = $content;
			break;
		case 'pings_link':
			$attr = shortcode_parse_atts( $input[2] );
			$title_prefix = ( isset( $attr['title'] ) ? $attr['title'] : '');
			$meta_content = fnbx_html_tag( array(
				'tag' => 'a',
				'class' => 'link-trackback',
				'href' => get_trackback_url(),
				'title' => $title_prefix . the_title_attribute('echo=0'),
				'tag_content' => $content,
				'return' => true
			) );
			break;
		case 'edit':
			$meta_content = $content;
			break;
		case 'edit_link':
			$attr = shortcode_parse_atts( $input[2] );
			$title_prefix = ( isset( $attr['title'] ) ? $attr['title'] : '');
			$html_tag = ( isset( $attr['tag'] ) ? $attr['tag'] : 'div');
			$edit_link = get_edit_post_link();
			if( !strstr( $edit_link, 'action=edit' ) ) break;
			$edit_link_tag = fnbx_html_tag( array(
				'tag' => 'a',
				'class' => 'link-edit',
				'href' => $edit_link,
				'title' => $title_prefix . the_title_attribute('echo=0'),
				'tag_content' => $content,
				'return' => true
			) );
			$meta_content = fnbx_html_tag( array(
				'tag' => $html_tag,
				'class' => 'link-wrapper-edit',
				'tag_content' => $edit_link_tag,
				'return' => true
			) );
			break;			
	}

	if ( isset( $meta_content ) ) return $meta_content;
}

// Funbox element Classes takes element and array and spits out a class= with some dynamic filtering.
function fnbx_layout_post_open( $the_post = 0 ) {	

	$element_classes = array();

	$layout_element_defaults = array(
		'tag' => 'div',
		'tag_type' => 'open',
		'return' => false,
		'id' => 'post-' . $the_post,
		'tag_content_before' => "\n",		
		'tag_content_after' => "\n",		
	);

	$element_classes = apply_filters( 'fnbx_post_class',  get_post_class( '', $the_post ) );

	$layout_element_defaults['class'] = implode( ' ', $element_classes );

	$layout_element_defaults = apply_filters( 'fnbx_post_attributes',  $layout_element_defaults );	

	fnbx_html_tag( $layout_element_defaults );
}

// Funbox element Classes takes element and array and spits out a class= with some dynamic filtering.
function fnbx_layout_post_close() {	

	$layout_element_defaults = array(
		'tag' => 'div',
		'tag_type' => 'close',
		'tag_content_before' => "\n",		
		'tag_content_after' => "\n",		
		'return' => false
	);

	$layout_element_defaults = apply_filters( 'fnbx_post_close_attributes',  $layout_element_defaults );	

	fnbx_html_tag( $layout_element_defaults );
}

// Funbox element Classes takes element and array and spits out a class= with some dynamic filtering.
function fnbx_layout_element_open( $element = '' , $return = false ) {	
	if ( $element == '' ) return;

	$element = sanitize_title_with_dashes( $element );

	$element_classes = array();

	$layout_element_defaults = array(
		'tag' => 'div',
		'tag_type' => 'open',
		'return' => $return,
		'id' => $element,
		'tag_content_before' => "\n",		
		'tag_content_after' => "\n"		
	);

	$element_classes[] = $element . '-';

	// So the filter is consistant. No dashes sorry!
	$element = str_replace( '-', '_', $element );
	$element_classes = apply_filters( 'fnbx_' . $element . '_class',  $element_classes );

	$layout_element_defaults['class'] = implode( ' ', $element_classes );

	$layout_element_defaults = apply_filters( 'fnbx_' . $element . '_attributes',  $layout_element_defaults );	

	fnbx_html_tag( $layout_element_defaults );
}

// Funbox element Classes takes element and array and spits out a class= with some dynamic filtering.
function fnbx_layout_element_open_class_only( $element = '' ) {	
	if ( $element == '' ) return;

	$element = sanitize_title_with_dashes( $element );

	$element_classes = array();

	$layout_element_defaults = array(
		'tag' => 'div',
		'tag_type' => 'open',
		'tag_content_before' => "\n",		
		'tag_content_after' => "\n",
		'return' => false,
	);

	$element_classes[] = $element;

	// So the filter is consistant. No dashes sorry!
	$element = str_replace( '-', '_', $element );
	$element_classes = apply_filters( 'fnbx_' . $element . '_class',  $element_classes );

	$layout_element_defaults['class'] = implode( ' ', $element_classes );

	$layout_element_defaults = apply_filters( 'fnbx_' . $element . '_open_attributes',  $layout_element_defaults );	

	fnbx_html_tag( $layout_element_defaults );
}

// Funbox element Classes takes element and array and spits out a class= with some dynamic filtering.
function fnbx_layout_element_close( $element = '' ) {	

	$layout_element_defaults = array(
		'tag' => 'div',
		'tag_type' => 'close',
		'tag_content_before' => "\n",		
		'tag_content_after' => "\n",		
		'return' => false
	);

	if ( $element != '' ) 
		$layout_element_defaults = apply_filters( 'fnbx_' . $element . '_close_attributes',  $layout_element_defaults );	

	fnbx_html_tag( $layout_element_defaults );
}

function fnbx_get_nav_meta( $direction = '' ) {

	$nav_meta_defaults = array(
		'tag' => 'span',
		'class' => 'nav-meta',
		'tag_content' => '',
		'tag_content_before' => ' ',
		'tag_content_after' => ' ',	
		'return' => true
	);

	$nav_meta_defaults['tag_content'] = ( $direction == 'next' ) ? __( '&raquo;', 'fnbx_lang' ) : __( '&laquo;', 'fnbx_lang' );

	$nav_meta_defaults = apply_filters( 'fnbx_get_nav_meta',  $nav_meta_defaults );	

	return fnbx_html_tag( $nav_meta_defaults );

}

// Stolen from WordPress adjacent post link
function fnbx_get_adjacent_post_link($format, $link, $in_same_cat = false, $excluded_categories = '', $previous = true) {
	if ( $previous && is_attachment() )
		$post = & get_post($GLOBALS['post']->post_parent);
	else
		$post = get_adjacent_post($in_same_cat, $excluded_categories, $previous);

	if ( !$post )
		return;

	$title = $post->post_title;

	if ( empty($post->post_title) )
		$title = $previous ? __( 'Previous Post [untitled]', 'fnbx_lang' ) : __( 'Next Post [untitled]', 'fnbx_lang' );

	$title = apply_filters('the_title', $title, $post);
	$date = mysql2date(get_option('date_format'), $post->post_date);

	$string = '<a href="'.get_permalink($post).'">';
	$link = str_replace('%title', $title, $link);
	$link = str_replace('%date', $date, $link);
	$link = $string . $link . '</a>';

	$format = str_replace('%link', $link, $format);

	$adjacent = $previous ? 'previous' : 'next';
	return apply_filters( "{$adjacent}_post_link", $format, $link );
}

/**
 * Display next or previous image link that has the same post parent.
 *
 * Retrieves the current attachment object from the $post global.
 *
 * @since 2.5.0
 *
 * @param bool $prev Optional. Default is true to display previous link, true for next.
 */
function fnbx_get_adjacent_image_link($prev = true, $size = 'thumbnail', $text = false) {
	global $post;
	$post = get_post($post);
	$attachments = array_values(get_children( array('post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID') ));

	foreach ( $attachments as $k => $attachment )
		if ( $attachment->ID == $post->ID )
			break;

	$k = $prev ? $k - 1 : $k + 1;

	if ( isset($attachments[$k]) )
		return wp_get_attachment_link($attachments[$k]->ID, $size, true, false, $text);
}

function fnbx_post_navigation( $direction = '', $type = '' ) {

	$post_nav_defaults = array(
		'layout' => array(
			'tag' => 'div',
			'tag_content_before' => "\n",		
			'tag_content_after' => "\n",
			'return' => true
		),
		'options' => array(
			'format' => '',
			'link' => '%title',
			'in_same_cat' => false,
			'excluded_categories' => '',
			'previous' => true
		)
	);	

	switch ( $direction ) {
		case 'previous':
			$post_nav_defaults['layout']['class'] = 'nav-previous';
			if ( $type == 'comment' )
				$post_nav_defaults['options']['format'] = __( 'Older Comments', 'fnbx_lang' );
			elseif ( $type == 'post' )
				$post_nav_defaults['options']['format'] = __( '%link', 'fnbx_lang' );
			elseif ( $type == 'search' )
				$post_nav_defaults['options']['format'] = __( 'Newer Results', 'fnbx_lang' );
			else
				$post_nav_defaults['options']['format'] = __( 'Newer Posts', 'fnbx_lang' );
			$post_nav_defaults['options']['previous'] = true;
			break;
		case 'next':
			$post_nav_defaults['layout']['class'] = 'nav-next';
			if ( $type == 'comment' )
				$post_nav_defaults['options']['format'] = __( 'Newer Comments', 'fnbx_lang' );
			elseif ( $type == 'post' )
				$post_nav_defaults['options']['format'] = __( '%link', 'fnbx_lang' );
			elseif ( $type == 'search' )
				$post_nav_defaults['options']['format'] = __( 'Older Results', 'fnbx_lang' );
			else
				$post_nav_defaults['options']['format'] = __( 'Older Posts', 'fnbx_lang' );
			$post_nav_defaults['options']['previous'] = false;	
			break;	
	}
	
	$post_nav_defaults = apply_filters( 'fnbx_post_nav_defaults',  $post_nav_defaults );

	switch ( $type ) {
		case 'post':	
			$post_nav_defaults['layout']['tag_content'] = fnbx_get_adjacent_post_link( 
				$post_nav_defaults['options']['format'], 
				$post_nav_defaults['options']['link'], 
				$post_nav_defaults['options']['in_same_cat'], 
				$post_nav_defaults['options']['excluded_categories'], 
				$post_nav_defaults['options']['previous']
			);
			break;
		case 'posts':	
			if ( $direction == 'previous' ) $post_nav_defaults['layout']['tag_content'] = get_previous_posts_link( 
				$post_nav_defaults['options']['format']
			);
			if ( $direction == 'next' ) $post_nav_defaults['layout']['tag_content'] = get_next_posts_link( 
				$post_nav_defaults['options']['format']
			); 
			break;
		case 'search':	
			if ( $direction == 'previous' ) $post_nav_defaults['layout']['tag_content'] = get_previous_posts_link( 
				$post_nav_defaults['options']['format']
			);
			if ( $direction == 'next' ) $post_nav_defaults['layout']['tag_content'] = get_next_posts_link( 
				$post_nav_defaults['options']['format']
			); 
			break;
		case 'image':	
			if ( $direction == 'previous' ) $post_nav_defaults['layout']['tag_content'] = fnbx_get_adjacent_image_link( 
				true,
				'thumbnail',
				false
			);
			if ( $direction == 'next' ) $post_nav_defaults['layout']['tag_content'] = fnbx_get_adjacent_image_link(
				false,
				'thumbnail',
				false
			); 
			break;
		case 'comment':	
			if ( $direction == 'previous' ) $post_nav_defaults['layout']['tag_content'] = get_previous_comments_link( $post_nav_defaults['options']['format'] );
			if ( $direction == 'next' ) $post_nav_defaults['layout']['tag_content'] = get_next_comments_link( $post_nav_defaults['options']['format'] );
			break;	
	}

	if ( !empty( $post_nav_defaults['layout']['tag_content'] ) ) {

		if ( $type != 'image' ) $post_nav_meta = fnbx_get_nav_meta( $direction );

		switch ( $direction ) {
			case 'previous':
				$post_nav_defaults['layout']['tag_content'] = $post_nav_meta . $post_nav_defaults['layout']['tag_content'];
				break;
			case 'next':
				$post_nav_defaults['layout']['tag_content'] = $post_nav_defaults['layout']['tag_content'] . $post_nav_meta;	
				break;	
		}
		$post_nav_defaults['layout'] = apply_filters( "fnbx_post_navigation_{$direction}",  $post_nav_defaults['layout'] );		
		return fnbx_html_tag( $post_nav_defaults['layout'] );
	}

}

// Previous and Next Post Navigation blocks
function fnbx_post_navigation_box( $position = '', $type = '' ) {

	$navigation_content = '';
	
	$navigation_content = fnbx_post_navigation( 'previous', $type );
	$navigation_content .= fnbx_post_navigation( 'next', $type );
	
	if ( $navigation_content == '' ) return;

	$position = 'nav-' . sanitize_title_with_dashes( $position );
	$nav_class = 'navigation';
	
	if ( $position != 'nav-' ) $nav_class .= ' ' . $position . '-';	

	$nav_box_defaults = array(
		'tag' => 'div',
		'id' => $position,
		'class' => $nav_class,
		'tag_content' => $navigation_content,
		'tag_content_before' => "\n",		
		'tag_content_after' => "\n"
	);

	fnbx_html_tag( $nav_box_defaults );

}

function fnbx_pagelist_box( $list_args = 'sort_column=menu_order&title_li=', $heading = '', $description = '' ) {

	$pagelist_box_defaults = array(
		'tag' => 'div',
		'tag_type' => 'open',
		'class' => 'page-list',
		'tag_content_after' => "\n",
		'tag_content_before' => "\n"
	);

	$pagelist_heading_defaults = array(
		'tag' => 'h2',
		'tag_content' => $heading,
		'tag_content_after' => "\n",
		'tag_content_before' => "\n"
	);

	$pagelist_description_defaults = array(
		'tag' => 'p',
		'tag_content' => $description,
		'tag_content_after' => "\n",
		'tag_content_before' => "\n"
	);	

	$pagelist_ul_defaults = array(
		'tag' => 'ul',
		'tag_type' => 'open',
		'tag_content_after' => "\n",
		'tag_content_before' => "\n"
	);	

	fnbx_html_tag( $pagelist_box_defaults );

	if ( $heading != '' ) fnbx_html_tag( $pagelist_heading_defaults );
	if ( $description != '' ) fnbx_html_tag( $pagelist_description_defaults );

	fnbx_html_tag( $pagelist_ul_defaults );

	wp_list_pages( $list_args);

	$pagelist_ul_defaults['tag_type'] = 'close';
	fnbx_html_tag( $pagelist_ul_defaults );

	$pagelist_box_defaults['tag_type'] = 'close';
	unset( $pagelist_box_defaults['class'] );
	fnbx_html_tag( $pagelist_box_defaults );

}

/**
* Funbox Debug
*
* Print some 
*
* @since 1.0
* @echo string
*/
// ISSUE: Incomplete. Needs filtering multi-usage.
function fnbx_debug_vars() {
	global $post, $wpdb;

	echo '<h1>WP $post</h1>';
	print_r( $post );

	echo '<h1>WP $wpdb</h1>';
	print_r( $wpdb );	
}

/**
* Show Statistics
*
* Writes HTML showing rendering statistics.
*
* @since 1.0
* @echo string
*/
function fnbx_stats() {
	fnbx_html_tag( array(
		'tag' => 'div',
		'class' => 'wp-stats',
		'tag_content' => 'Queries: ' . get_num_queries() . ' | Seconds: ' . timer_stop(0),
		'tag_content_before' => "\n",
		'tag_content_after' => "\n"
	) );
}
