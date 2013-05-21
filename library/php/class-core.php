<?php

if ( !class_exists( 'fnbx' ) ) {

/**
* FNBX Theme Framework Class
*
* This class gathers up 
* @package FNBX
* @subpackage FNBX Class
*/
    class fnbx { 
 
		/**
		* Initialize private variables. Set for php4 compatiblity. 
		*/		
		var $theme_support;
		var $content_width;

		var $custom_header;
		var $custom_background;
		
		var $tempalte_file;
		var $template_name;
		var $template_part_name;
		
		/**
		* Magic function used by PHP5 as the constructor
		*/			
		function __construct() {
			$this->fnbx();
		}
	
		/**
		* Constructor initializes private variables. Set for php4 compatiblity. 
		*/	
        function fnbx() {
			global $content_width;

			// Set global $content_width for WordPress images
			$this->content_width = apply_filters( 'fnbx_content_width', 532 );
			$content_width = $this->content_width;
			
			// Set and filter WordPress theme support features
			$this->theme_support = array( 
				'automatic-feed-links' => true,			
				'post-thumbnails' => true,
				'nav-menu' => true,
				'post-formats' => true,
				'custom-header' => true,
				'custom-background' => true,
				'editor-style' => true,
			);
			$this->theme_support = apply_filters( 'fnbx_theme_support', $this->theme_support );

			// Custom Headers
			if ( $this->theme_support['custom-header'] ) {
				$this->custom_header = array(
					'no_header_text' => false,
					'header_textcolor' => '',
					'header_image' => '',
					'header_image_thumbnail' => '',
					'header_image_width' => null,
					'header_image_height' => null,
					'header_image_flex_width' => false,
					'header_image_flex_height' => false,
					'css_name' => '.header-',
					'css_bg_color' => 'transparent',
					'css_repeat' => 'no-repeat',
					'css_repeat_from_theme' => false, // Force repeat from Theme style.css
					'css_position_x' => 'center',
					'css_position_y' => 'top',
					'css_position_from_theme' => false, // Force position from Theme style.css
					'css_attachment' => '',
					'random_default' => false
				);
				$this->custom_header = apply_filters( 'fnbx_custom_header',  $this->custom_header );
				
			}

			// Custom Backgrounds
			if ( $this->theme_support['custom-background'] ) {
				$this->custom_background = array(
					'background_image' => '',
					'css_name' => 'body',
					'css_bg_color' => '',
					'css_repeat' => 'no-repeat',
					'css_position_x' => 'center',
					'css_position_y' => 'top',
					'css_attachment' => ''
				);
				$this->custom_background = apply_filters( 'fnbx_custom_background',  $this->custom_background );
			}

			$this->template_parts = array();
			
			// BETA! Filter to capture current public view template file.
			add_filter( 'template_include', array(&$this, 'template_include_filter') );
			
        }
        
		/**
		* Template  Filter
		*
		* Store the file name with path of current loaded template in view.
		* uses template_include filter to store info into $fnbx object
		*
		* @since 1.0
		* @return string
		*/
		function template_include_filter( $template ) {
			$this->template_file = $template;
			return $template;
		}
		
		/**
		* Get Template Part Filter
		*
		* Store information about the current view so it can be used in
		* the get_template_part file.
		*
		* @uses do_action( "get_template_part{$slug}", $slug, $name ); 
		* @since 1.0
		* @return string
		*/
		function get_template_part_filter( $slug, $name ) {
			$this->template_part_name = $name;
			return;
		}		

    }

}

/* 

// My Notes with a simple Class Object doing WordPress filter majic.
class Profanity_Filter() {

	// This is for PHP5, which uses a new magical function as the constructor
	function __construct() {
		$this->Profanity_Filter();
	}
	
	// On intitialazation of the object we add the filters, actions etc.
	function Profanity_Filter() {
		add_filter('the_content', array(&$this, 'filter'));
		add_filter('comment_text', array(&$this, 'filter'));
	}
	
	// The class filter 
	function filter( $unfiltered_text ) {
		$profane_things = array('water', 'cold', 'fast', 'cheese');
		$clean_things = array('wine', 'hot', 'slow', 'pasta');
		$filtered_text = str_replace($profane_things, $clean_things, $unfiltered_text);
		return $filtered_text;
	}

}

$profanity_filter = new Profanity_Filter; 
*/
