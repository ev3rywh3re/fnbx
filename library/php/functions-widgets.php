<?php
/**
* Funbox Base Theme Widget Functions
* 
* The Funbox Base Theme does not use sidebar terminology or functionality.
* Widget areas are noted by the plural "widgets" instead of "sidebar".
* Groups of widgets can be registerd by filter and initialized by the
* fnbx_generate_widgets function using actions.
*
* @package Funbox Base Theme
* @subpackage Widgets
*/

// Default Widget Setup must use a WordPress action
add_action( 'widgets_init', 'fnbx_widgets_init' );

// Widgets initialization
function fnbx_widgets_init() {
	if ( !function_exists('register_sidebars') )
		return;

	// Formats the Sandbox widgets, adding readability-improving whitespace
	$widget_format = array(
		'before_widget'  =>   "\n\t\t\t" . '<li id="%1$s" class="widget %2$s">',
		'after_widget'   =>   "\n\t\t\t</li>\n",
		'before_title'   =>   "\n\t\t\t\t". '<h3 class="widgettitle">',
		'after_title'    =>   "</h3>\n"
	);
	
	$widget_groups = array( 
		'Primary' => 'Primary sidebar after content container', 
		'Secondary'=> 'Secondary sidebar after content container and primary area' 
	);

	$widget_groups = apply_filters( 'fnbx_widgets',  $widget_groups );

	foreach ( $widget_groups as $widget_group => $widget_group_desc ) {
		$widget_format['name'] = $widget_group;
		$widget_format['id'] = sanitize_title_with_dashes( $widget_group );
		$widget_format['description'] = $widget_group_desc;
		$widget_format = apply_filters( 'fnbx_widgets_' . $widget_format['id'],  $widget_format );
		register_sidebar( $widget_format );
	}

}

// Funbox Widget Generator
function fnbx_generate_widgets( $widget_group ) {
 
	$widget_group = sanitize_title( $widget_group );

	if ( !is_active_sidebar( $widget_group ) ) return;

	$outer_element_classes = array( 'widgets-', 'widgets-' . $widget_group . '-' );
	$outer_element_classes = apply_filters( 'fnbx_generate_widgets_outer_class',  $outer_element_classes );
	if ( is_array( $outer_element_classes ) && !empty( $outer_element_classes) )
		$outer_element_classes_text = implode( ' ', $outer_element_classes );

	$inner_element_classes = array( 'xoxo' );
	$inner_element_classes = apply_filters( 'fnbx_generate_widgets_inner_class',  $inner_element_classes );
	if ( is_array( $inner_element_classes ) && !empty( $inner_element_classes) )
		$inner_element_classes_text = implode( ' ', $inner_element_classes );		

	$widgets_defaults = array(
		'outer' => array(
			'tag' => 'div',
			'id' => 'widgets-' . $widget_group,
			'tag_content_before' => "\n",
			'tag_content_after' => "\n"
		),
		'inner' => array(
			'tag' => 'ul',
			'tag_content_after' => "\n"			
		)
	);

	if ( !empty( $outer_element_classes_text ) || isset( $outer_element_classes_text ) || $outer_element_classes_text != '' )
		$widgets_defaults['outer']['class'] = $outer_element_classes_text;
	if ( !empty( $inner_element_classes_text ) || isset( $inner_element_classes_text ) || $inner_element_classes_text != '' )
		$widgets_defaults['inner']['class'] = $inner_element_classes_text;

	$widgets_defaults = apply_filters( 'fnbx_generate_widgets',  $widgets_defaults );

	if ( !is_array( $widgets_defaults ) ) return;

	$widgets_defaults['outer']['tag_type'] = 'open';
	$widgets_defaults['inner']['tag_type'] = 'open';

	fnbx_html_tag( $widgets_defaults['outer'] );

	// Perform action for widget group inside outer container
	do_action( 'fnbx_widgets_' . $widget_group . '_before' );

	fnbx_html_tag( $widgets_defaults['inner'] );

	dynamic_sidebar( $widget_group );

	fnbx_html_tag( array(
		'tag_type' => 'close',
		'tag' => $widgets_defaults['inner']['tag'],
		'tag_content_before' => "\n",
		'tag_content_after' => "\n"
	) );

	// Perform action for widget group inside outer container	
	do_action( 'fnbx_widgets_' . $widget_group . '_after' );

	fnbx_html_tag( array(
		'tag_type' => 'close',
		'tag' => $widgets_defaults['outer']['tag'],
		'tag_content_before' => "\n",
		'tag_content_after' => "\n"
	) );

}

// Default sidevbar widget groups
function fnbx_default_widget_sidebar() {
	fnbx_generate_widgets( 'Primary' );
	fnbx_generate_widgets( 'Secondary' );
}
