<?php
/**
* FNBX Theme Template Functions
*
* Collection of functions used by the FNBX Theme.
*
* @package FNBX Theme
* @subpackage Functions
*/

/*
* FNBX Doctype
*
* Writes the theme doctype which can be modified by the fnbx_doctype
* and fnbx_doctype_text filters.
*
* @since 1.0
* @echo string
*/
function fnbx_doctype() {

	$doctype = apply_filters( 'fnbx_doctype',  'HTML' );
	
	switch( $doctype ) {
		case 'HTML':
			$doctype_text = '<!DOCTYPE html>';
			break;
		case 'XHTML 1.0 Strict':
			$doctype_text = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
			break;
		case 'XHTML 1.0 Transitional':
			$doctype_text = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
			break;
	}
	$doctype_text .= "\n";
	echo apply_filters( 'fnbx_doctype_text',  $doctype_text );
}

/**
* Core Mobile Viewport 
*
* Function used to set viewport for mobile devices.
*
* @since 0.4
*/
function fnbx_head_meta_viewport() {
	
	$viewport_defaults = array(
		'name' => 'viewport',
		'content'=> 'width=device-width, initial-scale=1, maximum-scale=1'
	);
	
	fnbx_write_meta_tag( apply_filters( 'fnbx_head_meta_viewport',  $viewport_defaults ) );	
}

/*
* FNBX Meta Content Type
*
* Writes the theme content type and character set to head.
*
* @since 1.0
* @echo string
*/
function fnbx_head_meta_content_type() {
	fnbx_write_meta_tag( array( 
		'http-equiv' => 'content-type',
		'content' => get_bloginfo('html_type') . '; charset=' . get_bloginfo('charset')
	) );
}

/*
* FNBX Meta Template
*
* Writes the template type to head and gives child theme credit.
*
* @since 1.0
* @echo string
*/
function fnbx_head_meta_template() {
	$theme = wp_get_theme();
	fnbx_write_meta_tag( array( 
		'name' => 'template',
		'content' => $theme->Name . ' ' . $theme->version
	) );
}

/*
* FNBX Meta Robots
*
* Writes the robots meta to head and tries to set indexing. 
* Index rules can be manipulated by the fnbx_head_meta_robots filter.
*
* @since 1.0
* @echo string
*/
function fnbx_head_meta_robots() {
	// Let WordPress write robot if the site is private
	if ( '0' == get_option('blog_public') ) return;

	// Robots
	$meta_robots = array(
		'is_home' => true,
		'is_front_page' => true,
		'is_attachment' => true,
		'is_single' => true,
		'is_singular' => true,
		'is_page' => true,
		'is_date' => true,
		'is_category' => true,
		'is_tag' => true,
		'is_search' => false,
		'is_author' => true,
		'is_404' => false
	);

	$meta_robots = apply_filters( 'fnbx_head_meta_robots',  $meta_robots );

	$robot_content = false;
	foreach( array_keys( $meta_robots ) as $test_is ) {
		if ( !fnbx_test_is( $test_is ) ) continue;

		if ( $meta_robots[ $test_is ] ) :
			$robot_content = 'index,follow';
		else :
			$robot_content = 'noindex,follow';
		endif;
	}

	if( $robot_content ) :
		fnbx_write_meta_tag( array( 
			'name' => 'robots',
			'content' => $robot_content
		) );
	endif;
}

/*
* FNBX Meta Author
*
* Writes the author meta to head giving post author credit. 
*
* @since 1.0
* @echo string
*/
function fnbx_head_meta_author() {
	global $wp_query; 

	// Author
	if( is_single() || is_page() ) :
		$curauth = get_userdata( $wp_query->post->post_author );
		if ( $curauth->display_name ) :
			$meta_author = $curauth->display_name;
		elseif ( $curauth->nickname ) :
			$meta_author = $curauth->nickname;
		elseif ( $curauth->first_name && $curauth->last_name ) :
			$meta_author = $curauth->first_name . ' ' . $curauth->last_name;			
		elseif ( $curauth->user_nicename ) :
			$meta_author = $curauth->user_nicename;
		else :
			$meta_author = $curauth->user_login;
		endif;
	endif;

	if( isset( $meta_author ) ) :
		fnbx_write_meta_tag( array( 
			'name' => 'author',
			'content' => $meta_author
		) );	
	endif;
}

/*
* FNBX Meta Copyright
*
* Writes the copyright meta with current year to head. 
*
* @since 1.0
* @echo string
*/
function fnbx_head_meta_copyright() {
	// Copyright
	if( is_single() || is_page() )
		$date = get_the_time( __( 'F Y','fnbx_lang' ) );
	else
		$date = date( __( 'Y','fnbx_lang' ) );

	fnbx_write_meta_tag( array( 
		'name' => 'copyright',
		'content' => apply_filters( 'fnbx_head_meta_copyright', __( 'Copyright (c) ','fnbx_lang' ) . $date ) 
	) );
}

/**
* FNBX Meta Revised
*
* Write meta revised tag to head.
*
* @since 1.0
* @echo string
*/
function fnbx_head_meta_revised() {
	// Modified
	if(is_single() || is_page())
		fnbx_write_meta_tag( array( 
			'name' => 'revised',
			'content' => get_the_modified_time( __( 'l, F jS, Y, g:i a', 'fnbx_lang' ) )
		) );		
}

/**
* FNBX Link Favicon
*
* Function used to add HTML to head for favicon stored in images directory.
*
* @since 0.4
*/
function fnbx_head_link_favicon() {

	$fnbx_favicon = apply_filters( 'fnbx_favicon',  FNBX_CHILD_DIR . '/images/favicon.ico' );
	if( !file_exists( $fnbx_favicon ) ) return;

	$favicon_default = array(
		'tag' => 'link',
		'tag_type' => 'single',
		'href' => FNBX_CORE_URL . '/images/favicon.ico',
		'type' => 'image/x-icon'
	);
	
	$favicon_default['rel'] = 'icon';
	fnbx_html_tag( $favicon_default );
	
	$favicon_default['rel'] = 'shortcut icon';
	fnbx_html_tag( $favicon_default );

}

/**
* FNBX Stylesheet Setup
*
* Used to setup the CSS Style sheet link for wp_head(). Needs to be called early and usuall in fnbx-loader.php
*
* @since 1.0
*/
function fnbx_stylesheet_init() {
    if ( !is_admin() ) {
		$theme  = wp_get_theme();
		wp_register_style( 'fnbx-style', get_stylesheet_uri(), false, $theme->version );
		wp_enqueue_style( 'fnbx-style' );
    }
 }

/**
* Write link pingback for head.
*
* @since 1.0
* @echo string
*/
function fnbx_head_link_pingback() {
	// Pingback
	fnbx_write_link_tag( array( 
		'rel' => 'pingback',
		'href' => get_bloginfo('pingback_url')
	) );			
}

/**
* Queue javascript for comments
*
* Adds the WordPress comment-reply javascript package to reply directly to individual
* comments
*
* @since 1.0
* @echo string
*/
function fnbx_enqueue_script_comment_reply() {
	if ( is_singular() ) wp_enqueue_script( 'comment-reply' );	
}

/**
* Write Accessible Breadcrumbs for head
*
* Attempts to provide accessibility options for impaired users
* allowing easier navigation through documents for screen readers
*
* @since 1.0
* @echo string
*/
function fnbx_head_link_breadcrumb() {
	global $post;

	if( !is_front_page() )
		fnbx_write_link_tag( array( 
			'rel' => 'home',
			'title' => get_bloginfo('name'),
			'href' => site_url()
		) );		
	if( is_single() || is_page() )
		fnbx_write_link_tag( array( 
			'rel' => 'bookmark',
			'title' => the_title_attribute('echo=0'),
			'href' => get_permalink()
		) );

/* 
ISSUE: WordPress 2.8 includes this! BUT how can we do this for
Archives, Authors, etc.
	$prev = get_previous_post();
	$next = get_next_post();

	if( $prev )
		fnbx_write_link_tag( array( 
			'rel' => 'previous',
			'title' => esc_html($prev->post_title ),
			'href' =>  get_permalink($prev->ID)
		) );	
	if( $next )
		fnbx_write_link_tag( array( 
			'rel' => 'next',
			'title' =>  esc_html($next->post_title ),
			'href' =>  get_permalink($next->ID)
		) );
*/
}

/**
* Main Menu HTML with Accessiblity Options
*
* Writes HTML for producing a main website menu structure for Pages
*
* @since 1.0
* @echo string
*/
function fnbx_access_menu() {

	$access_element_open = array(
		'tag_type' => 'open',
		'tag' => 'nav',
		'id' => 'access',
		'tag_content_before' => "\n",
		'tag_content_after' => "\n"		
	);

	$access_element_classes = array( 'access-' );
	$access_element_classes = apply_filters( 'fnbx_access_menu_class',  $access_element_classes );
	if ( is_array( $access_element_classes ) && !empty( $access_element_classes) )
		$access_element_classes_text = implode( ' ', $access_element_classes );

	if ( !empty( $access_element_classes_text ) || isset( $access_element_classes_text ) || $access_element_classes_text != '' )
		$access_element_open['class'] = $access_element_classes_text;
				
	$access_element_open = apply_filters( 'fnbx_access_element_open_options',  $access_element_open, 'access' );	

	fnbx_html_tag( $access_element_open );

	fnbx_html_tag( array(
		'tag_type' => 'open',
		'tag' => 'div',
		'class' => 'link-skip',
		'tag_content_before' => "\n",
		'tag_content_after' => "\n"		
	) );

	$access_skip_a_text = __( 'Skip to content', 'fnbx_lang' );
	fnbx_html_tag( array(
		'tag' => 'a',
		'href' => '#content',
		'tag_content' => $access_skip_a_text,
	) );

	fnbx_html_tag( array(
		'tag_type' => 'close',
		'tag' => 'div',
		'tag_content_before' => "\n",
		'tag_content_after' => "\n"		
	) );

	do_action( 'fnbx_access_menu_action');
	
	$access_element_close = array(
		'tag_type' => 'close',
		'tag' => 'nav',
		'tag_content_before' => "\n",
		'tag_content_after' => "\n"	
	);

	$access_element_close = apply_filters( 'fnbx_access_element_close_options',  $access_element_close, 'access' );	

	fnbx_html_tag( $access_element_close );
}

/**
* Main Website Title HTML
*
* Writes HTML for website title. H1 for home DIV for subpages
*
* @since 1.0
* @echo string
*/
function fnbx_default_title() {

	$title_link = fnbx_html_tag( array(
		'tag' => 'a',
		'href' => home_url(),
		'rel' => 'home',
		'title' => esc_html( get_bloginfo('name') ),
		'tag_content' => get_bloginfo('name'),
		'return' => true
	) );

	$title_defaults = array(
		'tag' => 'h1',
		'id' => 'blog-title',
		'class' => 'blog-title-',
		'tag_content' => $title_link,
		'tag_content_before' => "\n",
		'tag_content_after' => "\n"
	);

	$title_defaults = apply_filters( 'fnbx_default_title',  $title_defaults );	

	fnbx_html_tag( $title_defaults );	

}

/**
* Main Website Description HTML
*
* Writes HTML for website description or tag line.
*
* @since 1.0
* @echo string
*/
function fnbx_default_description() {

	$description_defaults = array(
		'tag' => 'div',
		'id' => 'blog-description',
		'class' => 'blog-description-',
		'tag_content' => get_bloginfo( 'description' ),
		'tag_content_before' => "\n",
		'tag_content_after' => "\n"
	);

	$description_defaults = apply_filters( 'fnbx_default_description',  $description_defaults );	

	fnbx_html_tag( $description_defaults );

}

/**
* FNBX Theme Document Title
*
* Function for writing HTML document title hopefully with decent SEO.
*
* @since 1.0
* @global mixed $post
* @global mixed $wp_query
* @echo string
*/
function fnbx_document_title() {
	global $post, $wp_query;

	$title_composite = array();

	if ( is_single() || is_attachment() || is_page() ) 
		if( !is_home() || !is_front_page() ) $title_composite[] = single_post_title( '', false );

	if ( is_category() ) 
		$title_composite[] = single_cat_title( '', false ) . __( ' Category Archives', 'fnbx_lang' );

	if ( is_tag() ) 
		$title_composite[] = single_tag_title( '', false ) . __( ' Tag Archives', 'fnbx_lang' );	

	if( is_search() ) 
		$title_composite[] = __( 'Search results for: ', 'fnbx_lang' ) . esc_attr( get_search_query() );

	if( is_day() ) 
		$title_composite[] = __( 'Archive for ','fnbx_lang' ) . get_the_time( __( 'F jS, Y','fnbx_lang' ) );

	if( get_query_var( 'w' ) ) 
		$title_composite[] = sprintf( __( 'Archive for week %1$s of %2$s ','fnbx_lang' ), get_the_time( __( 'W', 'fnbx_lang' ) ), get_the_time( __( 'Y','fnbx_lang' ) ) );

	if( is_month() ) 
		$title_composite[] = __( 'Archive for ','fnbx_lang' ) . single_month_title( ' ', false );

	if( is_year() ) 
		$title_composite[] = __( 'Archive for ', 'fnbx_lang' ) . get_the_time( __( 'Y', 'fnbx_lang' ) );

	if( is_404() ) 
		$title_composite[] = __( 'Error 404: Not Found', 'fnbx_lang' );

	// Check if is paged
	if( ( ( $page = $wp_query->get( 'paged' ) ) || ( $page = $wp_query->get('page') ) ) && $page > 1)
		$title_composite[] = __( ' Page ', 'fnbx_lang' ) . $page;

	$title_composite[] = apply_filters( 'fnbx_title_site_name',  esc_html( get_bloginfo('name') ) );

	$title_separator = apply_filters( 'fnbx_title_separator',  ' &raquo; ' );		
	$title_composite = apply_filters( 'fnbx_title_composite', $title_composite );

	$title = implode( $title_separator, $title_composite );

	echo strip_tags( $title );
}

/**
* FNBX Post and Page Entry Titles
*
* Writes post and page titles using FNBX Theme shortcode function. Shortcode can be filtered
* with fnbx_entry_title_shortcode filter and HTML block can be filtered using
* fnbx_entry_title filter.
*
* @since 1.0
* @echo string
*/
function fnbx_entry_title() {

	$entry_title_shortcode = apply_filters( 'fnbx_entry_title_shortcode',  __( '[permalink_link title=""][title][/permalink_link]', 'fnbx_lang' ) );

	$entry_title_defaults = array(
		'tag' => 'h1',
		'class' => 'entry-title',
		'tag_content' => fnbx_parse_meta_shortcode( $entry_title_shortcode ),
		'tag_content_before' => "\n",
		'tag_content_after' => "<!-- .entry-title -->\n"
	);

	$entry_title_defaults = apply_filters( 'fnbx_entry_title',  $entry_title_defaults );

	fnbx_html_tag( $entry_title_defaults );
}

/**
* FNBX Post and Page Entry Dates
*
* Writes post and page dates using FNBX Theme shortcode function. HTML block can be filtered using
* fnbx_entry_date filter.
*
* @since 1.0
* @echo string
*/
function fnbx_entry_date() {

	$meta_box_defaults = array(
		'tag' => 'div',
		'class' => 'entry-date',
		'tag_content' => fnbx_parse_meta_shortcode( __( '[date-abbr][date] at [time][/date-abbr]', 'fnbx_lang' ) ),
		'tag_content_before' => "\n",
		'tag_content_after' => "<!-- .entry-date -->\n"
	);

	$meta_box_defaults = apply_filters( 'fnbx_entry_date',  $meta_box_defaults );

	fnbx_html_tag( $meta_box_defaults );
}

/**
* FNBX Parent Titles
*
* Writes HTML for the parent of current entry using FNBX Theme shortcode function. Shortcode can
* be filtered using the fnbx_entry_parent_title_shortcode filter and HTML block can be filtered using
* fnbx_entry_parent_title filter.
*
* @since 1.0
* @echo string
*/
function fnbx_entry_parent_title() {

	$entry_title_shortcode = apply_filters( 'fnbx_entry_parent_title_shortcode',  __( '[parent_link title="Go back to "][title_parent][/parent_link]', 'fnbx_lang' ) );

	$entry_title_defaults = array(
		'tag' => 'h1',
		'class' => 'entry-parent-title',
		'tag_content' => fnbx_parse_meta_shortcode( $entry_title_shortcode ),
		'tag_content_before' => "\n",
		'tag_content_after' => "<!-- .entry-title -->\n"
	);

	$entry_title_defaults = apply_filters( 'fnbx_entry_parent_title',  $entry_title_defaults );

	fnbx_html_tag( $entry_title_defaults );

}

/**
* FNBX Page Titles
*
* Writes HTML for page titles used on archive, tag, author, and search areas. HTML block can be 
* filtered using fnbx_page_title_defaults filter.
*
* @since 1.0
* @echo string
*/
function fnbx_page_title_default() {

	$page_title_defaults = array (
		'tag' => 'h1',
		'class' => 'page-title'
	);

	$archive_title_span = array(
		'tag' => 'span',
		'return' => true
	);

	if ( is_day() ) {
		$archive_title_span['tag_content'] = get_the_time( get_option('date_format' ) );
		$archive_title = __( 'Daily Archives: ', 'fnbx_lang' );
	} elseif ( is_month() ) {
		$archive_title_span['tag_content'] = get_the_time( 'F Y' );
		$archive_title = __( 'Monthly Archives: ', 'fnbx_lang' );
	} elseif ( is_year() ) {
		$archive_title_span['tag_content'] = get_the_time( 'Y' );
		$archive_title = __( 'Yearly Archives: ', 'fnbx_lang' );
	} elseif ( isset($_GET['paged']) && !empty($_GET['paged']) ) {
		$archive_title = __( 'Blog Archives', 'fnbx_lang' );
	} elseif ( is_tag() ) {
		$archive_title_span['tag_content'] = single_tag_title( '', false );
		$archive_title = __( 'Tag Archives: ', 'fnbx_lang' );
	} elseif ( is_category() ) {
		$archive_title_span['tag_content'] = single_cat_title( '', false );
		$archive_title = __( 'Category Archives: ', 'fnbx_lang' );	
	} elseif ( is_author() ) {
		$archive_title = __( 'Author Archives: ', 'fnbx_lang' );
		$archive_title_span['tag_content'] = fnbx_parse_meta_shortcode( __( '[author pagetitle="true"][author_link title="View all posts by "][author_name][/author_link][/author]', 'fnbx_lang' ) );
		$page_title_defaults['class'] .= ' author';
	} elseif ( is_search() ) {
		$archive_title = __( 'Search Results for: ', 'fnbx_lang' );
		$archive_title_span['tag_content'] = get_search_query();
		$page_title_defaults['class'] .= ' search-query';
	}

	if ( isset( $archive_title_span['tag_content'] ) ) $archive_title .= fnbx_html_tag( $archive_title_span );	

	if ( isset( $archive_title ) ) $page_title_defaults['tag_content'] = $archive_title;

	$page_title_defaults = apply_filters( 'fnbx_page_title_defaults',  $page_title_defaults );

	fnbx_html_tag( $page_title_defaults );

}

/**
* FNBX Page Description HTML
*
* Writes HTML for descriptions. Only used now for author and category discriptions.
*
* @since 1.0
* @global mixed $authordata
* @echo string
*/
function fnbx_page_description_default() {
	global $authordata;

	$do_description = false;

	$description_box_defaults = array(
		'tag' => 'div',
		'class' => 'page-description',
		'tag_content_before' => "\n",
		'tag_content_after' => "<!-- .page-description -->\n"
	);

	if ( is_author() ) {
		if ( !empty( $authordata->user_description ) ) {
			$description_box_defaults['tag_content'] = $authordata->user_description;
			$description_box_defaults['class'] .= ' author-description';
			$do_description = true;
		}
	}

	if ( is_category() ) {
		$category_description =  category_description();
		if ( !empty( $category_description ) ) {
			$description_box_defaults['tag_content'] = $category_description;
			$description_box_defaults['class'] .= ' category-description';
			$do_description = true;
		}
	}

	if ( !$do_description ) return;

	$description_box_defaults = apply_filters( 'fnbx_page_description_default', $description_box_defaults );

	fnbx_html_tag( $description_box_defaults );
}

/**
* FNBX Article Header
*
* Writes HTML5 HEADER for use in ARTICLE. 
*
* @since 1.0
* @echo string
*/
function fnbx_article_header() {

	do_action( 'fnbx_article_header_before', 'article-header' );
	fnbx_layout_element_open( 'article-header' );
	do_action( 'fnbx_article_header_open', 'article-header' );

	fnbx_entry_title();
	
	// Entry date
	if ( !is_page() ) fnbx_entry_date();

	do_action( 'fnbx_article_header_close', 'article-header' );
	fnbx_layout_element_close( 'article-header' );
	do_action( 'fnbx_article_header_after', 'article-header' );

}

/**
* FNBX Article Footer
*
* Writes HTML5 FOOTER for use in ARTICLE. 
*
* @since 1.0
* @echo string
*/
function fnbx_article_footer() {

	do_action( 'fnbx_article_footer_before', 'article-footer' );
	fnbx_layout_element_open( 'article-footer' );
	do_action( 'fnbx_article_footer_open', 'article-footer' );

	// Content meta do we want brief or verbose, we could also filter or change with language files.
	if ( is_home() || ( is_archive() || is_search() ) ) 
		fnbx_post_meta_brief();
	// This should cover is_single, is_attachement, is_image
	elseif ( !is_page() )
		fnbx_post_meta_verbose();	
		
	do_action( 'fnbx_article_footer_close', 'article-footer' );
	fnbx_layout_element_close( 'article-footer' );
	do_action( 'fnbx_article_footer_after', 'article-footer' );
	
}


/**
* FNBX Entry Meta Box HTML
*
* Writes HTML post meta information. Uses fnbx_parse_meta_shortcode() function for dynamic meta
* information. Can be filtered using fnbx_post_meta_box filter.
*
* @since 1.0
* @echo string
*/
function fnbx_post_meta_box( $meta_string = '' ) {

	if ( $meta_string == '' ) return;

	$meta_box_defaults = array(
		'tag' => 'div',
		'class' => 'entry-meta',
		'tag_content' => fnbx_parse_meta_shortcode( $meta_string ),
		'tag_content_before' => "\n",
		'tag_content_after' => "<!-- .entry-meta -->\n"
	);

	$meta_box_defaults = apply_filters( 'fnbx_post_meta_box',  $meta_box_defaults );

	fnbx_html_tag( $meta_box_defaults );

}

/**
* FNBX Entry Meta Date HTML
*
* Writes HTML post meta date information. Passes string to fnbx_post_meta_box function for dynamic meta
* information. Can be filtered using fnbx_post_meta_date filter and fnbx_post_meta_box filter.
*
* @since 1.0
* @echo string
*/
function fnbx_post_meta_date() {

	$meta_string = __( '[date-abbr][date] at [time][/date-abbr]', 'fnbx_lang' );

	$meta_string = apply_filters( 'fnbx_post_meta_date',  $meta_string );

	fnbx_post_meta_box( $meta_string );

}

/**
* FNBX Entry Meta Edit HTML
*
* Writes HTML post edit links using fnbx_parse_meta_shortcode() shortcodes. Can be filtered
* using fnbx_post_meta_edit filter.
*
* @since 1.0
* @echo string
*/
function fnbx_post_meta_edit() {

	$meta_string = __( '[edit][edit_link tag="div" title="Click to edit "]Edit[/edit_link][/edit]', 'fnbx_lang' );

	$meta_string = apply_filters( 'fnbx_post_meta_edit',  $meta_string );

	echo fnbx_parse_meta_shortcode( $meta_string );
}

/**
* FNBX Brief Entry Meta HTML
*
* Writes HTML post meta information in brief format. Passes string to fnbx_post_meta_box function for dynamic meta
* information. Can be filtered using fnbx_post_meta_brief filter and fnbx_post_meta_box filter.
*
* @since 1.0
* @echo string
*/
function fnbx_post_meta_brief() {

	$meta_string = __( '[author][meta-block tag="span" class="meta-author"]By [author_link  title="View all posts by "][author_name][/author_link][/meta-block] [separator]|[/separator] [/author] [category][meta-block tag="span" class="meta-category-links"][category_text is_category="Also posted in "]Posted in [/category_text][category_links] [/meta-block] [separator]|[/separator] [/category] [tag][meta-block tag="span" class="meta-tag-links"][tag_text is_tag="Also Tagged "]Tagged [/tag_text][tag_links][/meta-block] [separator]|[/separator] [/tag][meta-block tag="span" class="meta-comments-link"][comments closed="Comments Off"][comments_link title="Leave a comment about "]Comments ([comments_number no_comments="No Comments"])[/comments_link][/comments][/meta-block] [edit][separator]|[/separator] [edit_link tag="span" title="Click to edit "]Edit[/edit_link][/edit]', 'fnbx_lang' );

	$meta_string = apply_filters( 'fnbx_post_meta_brief',  $meta_string );

	fnbx_post_meta_box( $meta_string );
}

/**
* FNBX Verbose Entry Meta HTML
*
* Writes HTML post meta information in verbose format. Passes string to fnbx_post_meta_box function for dynamic meta
* information. Can be filtered using fnbx_post_meta_verbose filter and fnbx_post_meta_box filter.
*
* @since 1.0
* @echo string
*/
function fnbx_post_meta_verbose() {

	$meta_string = __( '[meta-block tag="span" class="meta-info"][author]This entry was written by [author_link title="View all posts by "][author_name][/author_link][/author], posted on [date-abbr][date] at [time][/date-abbr][category][category_text], filed under [/category_text][category_links][/category][tag][tag_text] and tagged [/tag_text][tag_links][/tag]. Bookmark the [permalink_link title="Permalink to "]Permalink[/permalink_link].[comments_rss closed=""] Follow any comments here with the [comments_rss_link title="Comments RSS for "]RSS feed for this post[/comments_rss_link].[/comments_rss] [feedback closed="Comments and Pings are closed!"][comments closed="Comments are closed "]Post a [comments_link title="Leave a comment about "]comment([comments_number no_comments="No Comments"])[/comments_link][/comments][feedback_separator comments_closed="but you can " pings_closed=", but " both_closed=" and "] or [/feedback_separator][pings closed="Pings are closed"]leave a trackback: [pings_link title="Use this link when you write about "]Ping URL[/pings_link][/pings].[/feedback] [edit][edit_link tag="span" title="Click to edit "]Edit - [title][/edit_link][/edit][/meta-block]', 'fnbx_lang' );

	$meta_string = apply_filters( 'fnbx_post_meta_verbose',  $meta_string );

	fnbx_post_meta_box( $meta_string );
}

/**
* FNBX Automatic Previous and Next Posts
*
* Writes HTML for navigation between post and post pages that would appear above post entry lists.
*
* @since 1.0
* @echo string
*/
function fnbx_post_navigation_box_auto( $location ) {
	if ( is_attachment() )
		fnbx_post_navigation_box( $location, 'image' );
	elseif ( is_single() )
		fnbx_post_navigation_box( $location, 'post' );
	elseif ( is_search() )
		fnbx_post_navigation_box( $location, 'search' );		
	else
		fnbx_post_navigation_box( $location, 'posts' );
}

/**
* FNBX Automatic Previous and Next Comment Above HTML
*
* Writes HTML for navigation between comment pages that would appear above comment lists.
*
* @since 1.0
* @echo string
*/
function fnbx_comment_navigation_box_above() {
	fnbx_post_navigation_box( 'above-comments', 'comment' );
}

/**
* FNBX Automatic Previous and Next Comment Below HTML
*
* Writes HTML for navigation between comment pages that would appear below comment lists.
*
* @since 1.0
* @echo string
*/
function fnbx_comment_navigation_box_below() {
	fnbx_post_navigation_box( 'below-comments', 'comment' );
}

/**
* FNBX List Comments
*
* Writes HTML block for comment lists. Display can be filtered using fnbx_comment_list filter.
*
* @since 1.0
* @echo string
*/
function fnbx_comment_list_default() {

	$comment_list_defaults = array(
		'tag' => 'ol',
		'tag_type' => 'open',
		'class' => 'commentlist',
		'tag_content_after' => "\n",
		'tag_content_before' => "\n"
	);

	$comment_list_defaults = apply_filters( 'fnbx_comment_list', $comment_list_defaults );

	fnbx_html_tag( $comment_list_defaults );

	wp_list_comments();

	$comment_list_defaults['tag_type'] = 'close';
	unset( $comment_list_defaults['class'] );

	fnbx_html_tag( $comment_list_defaults );

}

/**
* FNBX Search Form
*
* Writes HTML block standard search form.
*
* @since 1.0
* @echo string
*/
// ISSUE: Incomplete. Needs filtering multi-usage.
function fnbx_search_form() {

	$search_row = fnbx_form_input_row( array( 'label' => __( 'Search', 'fnbx_lang' ), 'type' => 'text', 'name' => 's', 'value' => '', 'return' => true ) );

	$search_row .= fnbx_form_input( array( 'type' => 'submit', 'name' => 'search-submit', 'value' => __( 'Find', 'fnbx_lang' ), 'return' => true ) );

	fnbx_form( 'search', site_url(), 'get', $search_row );	

}

/**
* FNBX The Content
*
* Writes content. Basically a wrapper for the_content WP function.
*
* @since 1.0
* @echo string
*/
// ISSUE: Incomplete. Needs filtering multi-usage.
function fnbx_the_content() {
	the_content();
	// issue: is this right?
	if ( is_page() ) wp_link_pages('before=<div class="page-links">' . __( 'Pages:', 'fnbx_lang' ) . '&after=</div>');
}

/**
* FNBX The Content with More
*
* Writes content using more separator. Basically a wrapper for the_content WP function with a global $more override.
*
* @since 1.0
* @echo string
*/
// ISSUE: Incomplete. Needs filtering multi-usage.
function fnbx_the_content_more() {
	global $more;
	$more = 0;
	the_content();
	// issue: is this right?
	if ( is_page() ) wp_link_pages('before=<div class="page-links">' . __( 'Pages:', 'fnbx_lang' ) . '&after=</div>');
}

/**
* FNBX The Excerpt
*
* Writes excerpt. Basically a wrapper for the_excerpt WP function.
*
* @since 1.0
* @echo string
*/
// ISSUE: Incomplete. Needs filtering multi-usage.
function fnbx_the_excerpt() {
	the_excerpt();
}

/**
* FNBX Attachment Content
*
* Writes HTML block for attachments content.
*
* @since 1.0
* @echo string
*/
// ISSUE: Incomplete. Needs filtering multi-usage.
function fnbx_attachment_content() {
	global $post;

	$attachment = fnbx_html_tag( array (
		'tag' => 'a',
		'href' => wp_get_attachment_url( $post->ID ),
		'title' => esc_html( get_the_title( $post->ID ) ),
		'rel' => 'attachment',
		'tag_content' => basename( $post->guid ),
		'return' => true
	) );

	fnbx_html_tag( array (
		'tag' => 'div',
		'class' => 'entry-attachment',
		'tag_content' => $attachment
	) );

	if ( !empty( $post->post_excerpt ) )
		fnbx_html_tag( array (
			'tag' => 'div',
			'class' => 'entry-caption',
			'tag_content' => get_the_excerpt()
		) );

}

/**
* FNBX Image Content
*
* Writes HTML block for image content.
*
* @since 1.0
* @echo string
*/
// ISSUE: Incomplete. Needs filtering multi-usage.
function fnbx_image_content() {
	global $post;

	$attachment = fnbx_html_tag( array (
		'tag' => 'a',
		'href' => wp_get_attachment_url( $post->ID ),
		'title' => esc_html( get_the_title( $post->ID ) ),
		'rel' => 'attachment',
		'tag_content' => wp_get_attachment_image( $post->ID, 'medium' ),
		'return' => true
	) );

	fnbx_html_tag( array (
		'tag' => 'div',
		'class' => 'entry-attachment',
		'tag_content' => $attachment
	) );

	if ( !empty( $post->post_excerpt ) )
		fnbx_html_tag( array (
			'tag' => 'div',
			'class' => 'entry-caption',
			'tag_content' => get_the_excerpt()
		) );

}

/**
* FNBX The Comments Separated
*
* Handles loading of the comments.php template with comments separated.
*
* @since 1.0
*/
// ISSUE: Incomplete. Needs filtering multi-usage.
function fnbx_comments_template_separate() {
	comments_template( '', true );
}

/**
 * FNBX Comments Number
 *
 * Taken from wordpress comments_number() includes an echo option
 * Display the language string for the number of comments the current post has.
 *
 * @since 0.71
 * @uses $id
 * @uses apply_filters() Calls the 'comments_number' hook on the output and number of comments respectively.
 *
 * @param string $zero Text for no comments
 * @param string $one Text for one comment
 * @param string $more Text for more than one comment
 * @param string $deprecated Not used.
 */
function fnbx_comments_number( $zero = false, $one = false, $more = false, $do_echo = false ) {
	global $id;

	$number = get_comments_number($id);

	if ( $number > 1 )
		$output = str_replace('%', number_format_i18n($number), ( false === $more ) ? __('% Comments') : $more);
	elseif ( $number == 0 )
		$output = ( false === $zero ) ? __('No Comments') : $zero;
	else // must be one
		$output = ( false === $one ) ? __('1 Comment') : $one;

	$the_comments_number = apply_filters('comments_number', $output, $number);
	
	if ( !$do_echo ) 
		return $the_comments_number;
	else
		echo $the_comments_number;
}


/**
* FNBX Footer Content
*
* Writes HTML block for footer giving credit to FNBX and WordPress.
*
* @since 1.0
* @echo string
*/
function fnbx_footer_default() {

	$wordpress_link = fnbx_html_tag( array (
		'tag' => 'a',
		'href' => 'http://wordpress.org/',
		'title' =>  __( 'WordPress', 'fnbx_lang' ),
		'rel' => 'generator',
		'tag_content' => __( 'WordPress', 'fnbx_lang' ),
		'return' => true
	) );

	$fnbx_link = fnbx_html_tag( array (
		'tag' => 'a',
		'href' => 'http://funroe.net/projects/fnbx-theme/',
		'title' =>  __( 'FNBX Theme for WordPress', 'fnbx_lang' ),
		'rel' => 'designer',
		'tag_content' => __( 'FNBX', 'fnbx_lang' ),
		'return' => true
	) );

	fnbx_html_tag( array (
		'tag' => 'span',
		'class' => 'link-generator',
		'tag_content' => $wordpress_link,
		'tag_content_after' => ' '
	) );	

	fnbx_html_tag( array (
		'tag' => 'span',
		'class' => 'meta-sep',
		'tag_content' => __( '|', 'fnbx_lang' ),
		'tag_content_after' => ' '
	) );

	fnbx_html_tag( array (
		'tag' => 'span',
		'class' => 'link-theme',
		'tag_content' => $fnbx_link,
		'tag_content_after' => ' '
	) );

}
