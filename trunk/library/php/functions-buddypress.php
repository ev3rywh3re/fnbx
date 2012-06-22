<?php
/**
* Funbox Base Theme BuddyPress Compatibility
*
*
* Various functions and hooks for BuddyPress. In Progress
* ISSUE: How far do we go? It could be fun!

This code is taken directly from the default BuddyPress frameqork functions.php with modifications for relocating some utlities.

bp-sn-framework


*
* @package Funbox Base Theme
* @subpackage BuddyPress Compatibility
*/

/**
* Include BP Themepack
* See http://codex.buddypress.org/how-to-guides/wordpress-to-buddypress-theme/ 
*/
// include( FNBX_DIR . '/bp/bp-functions.php' );


/*
* Add some default stuff to Funbox for BuddyPress
*/
function fnbx_buddypress_init() {

	// Document title filter
	add_filter( 'fnbx_title_composite', 'fnbx_bp_page_title_filter' );

	// Meta Shortcode additions
	add_filter( 'fnbx_meta_shortcodes', 'fnbx_bp_meta_shortcodes' );
	add_filter( 'fnbx_meta_shortcodes_callbacks', 'fnbx_bp_meta_shortcode_callback' );

	add_action( 'fnbx_wp_head_before', 'bp_head_action' );

	add_action( 'fnbx_header_start', 'bp_before_header' );
	add_action( 'fnbx_header_end', 'bp_after_header' );

	add_action( 'fnbx_container_start', 'bp_before_container' );

	// BuddyPress Feeds
	if ( function_exists( 'bp_sitewide_activity_feed_link' ) ) add_action( 'fnbx_wp_head_after', 'fnbx_bp_sitewide_activity_feed_link' );

	add_action( 'fnbx_header_start', 'fnbx_bp_search_login_bar' );	

}
// add_action( 'fnbx_defaut_actions', 'fnbx_buddypress_init' );

/*
*
*/
function bp_head_action() {
	do_action( 'bp_head' );
}

/*
*
*/
function bp_before_header() {
	do_action( 'bp_before_header' );
}

/*
*
*/
function bp_after_header() {
	do_action( 'bp_after_header' );
}

/*
*
*/
function bp_before_container() {
	do_action( 'bp_before_container' );
}


/**
* Funbox BuddyPress Document Title
*
* Called in fnbx_document_title() to add BuddyPress specific elements to document title. 
* Sample taken from: function bp_get_page_title() in bp-core/bp-core-templatetags.php
*
* @since 1.0
*/
function fnbx_bp_page_title_filter( $title_composite_in ) {
	global $bp, $current_blog;

	if ( count( $title_composite_in ) >= 1 )
		$title_site_name = array_pop( $title_composite_in ); 

	if ( defined( 'BP_ENABLE_MULTIBLOG' ) ) {
		$title_site_name = get_blog_option( $current_blog->blog_id, 'blogname' );
	} else {
		$title_site_name = get_blog_option( BP_ROOT_BLOG, 'blogname' );		
	}

	$title_composite[] = apply_filters( 'fnbx_title_site_name',  esc_html( $title_site_name ) );

	if ( !empty( $bp->displayed_user->fullname ) ) {
	 	$title_composite[] = $bp->displayed_user->fullname;
	 	$title_composite[] = ucwords( $bp->current_component );
	 	$title_composite[] = $bp->bp_options_nav[$bp->current_component][$bp->current_action]['name'];
	} else if ( $bp->is_single_item ) {
		$title_composite[] = ucwords( $bp->current_component );
		$title_composite[] = $bp->bp_options_title;
	} else if ( $bp->is_directory ) {
		if ( !$bp->current_component )
			$title_composite[] = sprintf( __( '%s Directory', 'fnbx_lang' ), ucwords( BP_MEMBERS_SLUG ) );
		else
			$title_composite[] = sprintf( __( '%s Directory', 'fnbx_lang' ), ucwords( $bp->current_component ) );
	}

	if ( is_array( $title_composite_in ) ) 
		$title_composite_out = array_merge( $title_composite_in, $title_composite );
	else
		$title_composite_out = $title_composite;

	return $title_composite_out;
}

/**
* Funbox BuddyPress Activity Feed link
*
* Writes head link html tag for BuddyPress sitewide activitiy feeds.
*
* @since 1.0
* @echo string
*/
function fnbx_bp_sitewide_activity_feed_link() {	
	fnbx_write_link_tag( array( 
		'rel' => 'alternate',
		'type' => 'application/rss+xml',
		'title' => get_bloginfo('name') . __(' Site Wide Activity RSS Feed', 'fnbx_lang' ),
		'href' => bp_get_sitewide_activity_feed_link()
	) );
}

/**
* Funbox BuddyPress Search Form
*
* Writes HTML forms for login and searching. 
* Sample taken from: bp-sn-framework/header.php
*
* @since 1.0
* @echo string
*/
function fnbx_bp_search_form() {

	$form_contents = fnbx_form_input_row( array( 'label' => __( 'Search', 'fnbx_lang' ), 'type' => 'text', 'name' => 'search-terms', 'value' => '', 'return' => true ) );

	$form_contents .= bp_search_form_type_select();
	$form_contents .= fnbx_form_input( array( 'type' => 'submit', 'name' => 'search-submit', 'value' => __( 'Search', 'fnbx_lang' ), 'return' => true ) );
	$form_contents .= wp_nonce_field( 'bp_search_form', '_wpnonce', true, false );

	fnbx_form( 'search-form', bp_search_form_action(), 'post', $form_contents ); 
}

/**
* Funbox BuddyPress Log In Form
*
* Writes HTML forms for login and searching. 
* Sample taken from: bp-sn-framework/header.php
*
* @since 1.0
* @echo string
*/
function fnbx_bp_login_bar_logged_out() {
	global $bp;

	$user_input = array( 
		'label' => __( 'Username', 'fnbx_lang' ),
		'type' => 'text',
		'name' => 'log',
		'value' => '',
		'return' => true
	);

	$user_additions = array(
		'id' => 'user_login',
		'onfocus' => "if (this.value == '" . __( 'Username', 'fnbx_lang' ) . "') {this.value = '';}",
		'onblur' => "if (this.value == '') {this.value = '" . __( 'Username', 'fnbx_value' ). "';}"
	);

	$form_contents = fnbx_form_input_row( $user_input, $user_additions  );

	$password_input = array( 
		'label' => __( 'Password', 'fnbx_lang' ),
		'type' => 'password',
		'name' => 'pwd',
		'value' => '',
		'return' => true
	);

	$password_additions = array(
		'id' => 'user_pass'
	);

	$form_contents .= fnbx_form_input_row( $password_input, $password_additions  );

	$remember_input = array( 
		'type' => 'checkbox',
		'name' => 'rememberme', 
		'value' =>'forever', 
		'return' => true
	);

	$remember_additions = array( 
		'title' => __( 'Remember Me', 'fnbx_lang' ),
	);

	$form_contents .= fnbx_form_input( $remember_input, $remember_additions );

	$submit_input = array( 
		'type' => 'submit',
		'name' => 'wp-submit', 
		'value' => __( 'Log In', 'fnbx_lang' ), 
		'return' => true
	);

	$form_contents .= fnbx_form_input( $submit_input );

	if ( 'none' != bp_get_signup_allowed() && 'blog' != bp_get_signup_allowed() ) {
		$signup_input = array( 
			'type' => 'button',
			'name' => 'signup-submit', 
			'value' => __( 'Sign Up', 'fnbx_lang' ), 
			'return' => true
		);

		$signup_additions = array( 
			'title' => __( 'Remember Me', 'fnbx_lang' ),
		);

		$form_contents .= fnbx_form_input( $signup_input, $signup_additions );	

	}

	fnbx_form( 'login-form', $bp->root_domain . '/wp-login.php', 'post', $form_contents );

	// From bp-sn-parent/header.php if user logged out
	do_action( 'bp_login_bar_logged_out' );
}

function fnbx_bp_logout_link( $return = false ) {
	global $bp;

	$action_text = __( 'Log Out', 'fnbx_lang' );

	$action_link = fnbx_html_tag( array(
		'tag' => 'a',
		'class' => 'link-logout',
		'href' => wp_logout_url( $bp->root_domain ),
		'title' => $action_text,
		'tag_content' => $action_text,
	) );

	if ( $return == true ) return $action_link;

	echo $action_link;
}

function fnbx_bp_profile_link( $user_id, $content_before = '', $content_after = '', $return = false ) {
	$user_data = get_userdata( $user_id );

	$user_display_name = bp_core_get_user_displayname( $user_id );

	$user_link = fnbx_html_tag( array(
		'tag' => 'a',
		'class' => 'url fn n link-profile',
		'href' => bp_core_get_userlink( $user_id, false, true ),
		'title' => __( 'Profile for ' ) . $user_display_name,
		'tag_content' => $user_display_name,
		'return' => true
	) );

	$action_link = fnbx_html_tag( array(
		'tag' => 'span',
		'class' => 'author vcard author-'. $user_data->user_nicename,
		'tag_content' => $user_link,
		'tag_content_before' => $content_before,
		'tag_content_after' => $content_after,
		'return' => true
	) );

	if ( $return == true ) return $action_link;

	echo $action_link;
}

/**
* Funbox BuddyPress User Info and logout
*
* Writes HTML some current user info and avatar with logout link
* Sample taken from: bp-sn-parent/header.php
*
* @since 1.0
* @echo string
*/
function fnbx_bp_login_bar_logged_in() {
	global $bp;

	fnbx_layout_element_open( 'link-profile' );

	bp_loggedin_user_avatar( 'width=20&height=20' );

	fnbx_bp_profile_link( $bp->loggedin_user->id, '&nbsp;', ' / ' );

	fnbx_bp_logout_link();

	// From bp-sn-parent/header.php if user logged in
	do_action( 'bp_login_bar_logged_in' );

	fnbx_layout_element_close( 'link-profile' );
}

/**
* Funbox BuddyPress Search & Login Bar
*
* Writes HTML forms for login and searching. 
* Sample taken from: bp-sn-framework/header.php
*
* @since 1.0
* @echo string
*/
function fnbx_bp_search_login_bar() {	

	fnbx_layout_element_open( 'search-login-bar' );

	fnbx_bp_search_form();
	if ( !is_user_logged_in() ) {
		fnbx_bp_login_bar_logged_out();
	} else {
		fnbx_bp_login_bar_logged_in();
	}

	fnbx_layout_element_close( 'search-login-bar' );

}


/**
* Funbox Alternate BuddyPress Meta Shortcodes
*
* Adds elements to shortcode processor using fnbx_meta_shortcodes filter in fnbx_parse_meta_shortcode()
*
* @since 1.0
* @return array
*/
function fnbx_bp_meta_shortcodes( $shortcodes ) {
	$shortcodes[] = 'author_avatar';
	return $shortcodes;
}

/**
* Funbox Alternate BuddyPress Meta Shortcode Callbacks
*
* Adds callbacks for shortcode processoring using fnbx_meta_shortcodes_callbacks filter in 
* fnbx_parse_meta_shortcode(). Buddypress shortcodes run first so they can override defaults.
*
* @since 1.0
* @return array
*/
function fnbx_bp_meta_shortcode_callback( $callbacks ) {
	// These callbacks run first
	array_unshift( $callbacks, 'fnbx_bp_do_meta_shortcode' );
	return $callbacks;
}

/**
* Funbox Alternate BuddyPress Meta Shortcode Processing
*
* Shortcode processoring for BuddyPress elements. Passes enclosed strings and non-Buddpress
* shortcodes back. Replaces default author_link shortcode.
*
* @since 1.0
* @return array
*/
function fnbx_bp_do_meta_shortcode( $input = '' ) {
	global $wp_query, $post, $authordata;

	// Shortcodes enclosed in a shortcode must run
	if ( isset($input[4]) ) { 
		$content = fnbx_parse_meta_shortcode( $input[4] );
	}

	switch( $input[1] ) {		
		case 'author_link':
			$attr = shortcode_parse_atts( $input[2] );
			$author_link = fnbx_html_tag( array(
				'tag' => 'a',
				'class' => 'url fn n',
				'href' => bp_core_get_userlink( $authordata->ID, false, true ),
				'title' => $attr['title'] . $authordata->display_name,
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
		case 'author_avatar':
			//$meta_content = bp_core_fetch_avatar( array( 'item_id' => $authordata->ID, 'type' => $type, 'width' => $width, 'height' => $height ) );
			$meta_content = bp_core_fetch_avatar( array( 'item_id' => $authordata->ID ) );
			break;
		default:
			$meta_content = $input[0];
	}

	return $meta_content;
}
