<?php 
/**
* Funbox Base Theme Post Thumbnail Support
*
* Actions, filters, and functions used by the Funbox Base Theme to add post thumbnail support.
*
* @package Funbox Base Theme
* @subpackage Post Thumbnail Support
*/


/**
* Activate Nav Menu Default Support
*
* ISSUE: This is kinda stupid right now. It's a wrapper to add_theme_support('nav-menu')
* It also adds the default to the access div looking for a "Top Menu" or just getting 
* the first menu created.
*
* @since 1.0
* @echo string
*/
function fnbx_nav_menus_default_setup() {
	add_theme_support('nav-menus');
	
	// Register Top Menu
	register_nav_menu( 'menu-top', __( 'Top Menu' ) );
	// Widget Menu Sidebar Group
	add_action( 'fnbx_access_menu_action', 'fnbx_nav_menus_default_menu' );	
	// Accessiblity and Menu - Add menu to access area
	// ISSUE: deleted add_action( 'fnbx_access_menu_action', 'fnbx_menu' );

}


/**
* Funbox Nav Menu Default HTML
*
* Display the default FNBX Nav Menu called "Top Menu" or the first menu in the list. Filter
* the default arguments using fnbx_generate_default_menu so they can be overridden.
*
* @since 1.0
* @echo string
*/
function fnbx_nav_menus_default_menu() {

	$menu_defaults = array( 
		'menu' => 'Top Menu', 
		'theme_location' => 'menu-top',
		'container' => 'div',
		'container_class' => 'menu-top-container-',
		'container_id' => 'menu-top-container',
		'menu_class' => 'menu-top',
		'echo' => true,
		'fallback_cb' => '', 
		'before' => '',
		'after' => '',
		'link_before' => '',
		'link_after' => '',
		'depth' => 0,
		'walker' => '',
		'context' => 'frontend'
	);
	
	// Filter $menu_defaults['name'] to change the default menu used
	$menu_defaults = apply_filters( 'fnbx_generate_default_menu',  $menu_defaults );
	
	wp_nav_menu( $menu_defaults );

}
