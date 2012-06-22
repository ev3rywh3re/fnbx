<?php 
/**
* Funbox Base Theme Custom Header Support
*
* Actions, filters, and functions used by the Funbox Base Theme to custom header support.
*
* @package Funbox Base Theme
* @subpackage Custom Header Support
*/

/**
* Activate Custom Header Default Support
*
* Custom header data is stored in the global $fnbx object so it can be used and filtered in
* multiple places. Some additional paramaters include the CSS background color, repeat, and alignment.
*
* @since 1.0
* @echo string
*/
function fnbx_custom_header_setup() {
	global $fnbx;
		
	// Define Custom Header Images
	define( 'NO_HEADER_TEXT', $fnbx->custom_header['no_header_text'] );
	define( 'HEADER_TEXTCOLOR', $fnbx->custom_header['header_textcolor'] );
	define( 'HEADER_IMAGE', $fnbx->custom_header['header_image'] );
	define( 'HEADER_IMAGE_WIDTH', $fnbx->custom_header['header_image_width'] ); // use width and height appropriate for your theme
	define( 'HEADER_IMAGE_HEIGHT', $fnbx->custom_header['header_image_height'] );
	
	add_custom_image_header('fnbx_custom_header_style', 'fnbx_custom_header_admin_style');
}
add_action( 'fnbx_loaded', 'fnbx_custom_header_setup' );


// gets included in the site header
function fnbx_custom_header_style() {
   global $fnbx;
      
   if ( !isset( $fnbx->custom_header['css_name'] ) || empty( $fnbx->custom_header['css_name'] ) ) return;
   
   $css_txt = '';
   
   $css_image = get_header_image();
   if ( empty( $css_image ) ) $css_image = $fnbx->custom_header['header_image'];
   
   if ( !empty( $fnbx->custom_header['css_bg_color'] ) ) $css_txt .= "\n background-color: " . $fnbx->custom_header['css_bg_color'] . ';';
   
   if ( !empty( $css_image ) ) {
	   $css_image = apply_filters( 'fnbx_custom_header_css_background_url',  $css_image );
	   $css_txt .= "\n background-image: " . ' url("' . $css_image . '");';
	   if ( !isset( $fnbx->custom_header['css_repeat_from_theme'] ) && $fnbx->custom_header['css_repeat_from_theme'] != true ) {
		   if ( !empty( $fnbx->custom_header['css_repeat'] ) ) $css_txt .= "\n background-repeat: " . $fnbx->custom_header['css_repeat'] . ';';
	   }
	   if ( !isset( $fnbx->custom_header['css_position_from_theme'] ) && $fnbx->custom_header['css_position_from_theme'] != true ) {
		   if ( !empty( $fnbx->custom_header['css_position_x'] ) ) $css_position_txt .= ' ' . $fnbx->custom_header['css_position_x'];
		   if ( !empty( $fnbx->custom_header['css_position_y'] ) ) $css_position_txt .= ' ' . $fnbx->custom_header['css_position_y'];
		   
			if ( !empty( $$css_position_txt ) ) $css_txt .= "\n background-position: " . $css_position_txt . ';';
	   }
	}
	

	$css_txt = apply_filters( 'fnbx_custom_header_css_background',  $css_txt );
   
   if ( empty( $css_txt ) ) return;
   
?><style type="text/css">
<?php echo $fnbx->custom_header['css_name']; ?> {
<?php echo $css_txt . "\n"; ?>
}
</style><?php 	

}

// gets included in the admin header
function fnbx_custom_header_admin_style() {
	global $fnbx;
 
	if ( !empty( $fnbx->custom_header['css_repeat'] ) ) 
		$css_background_repeat = $fnbx->custom_header['css_repeat'];
	else
		$css_background_repeat = 'no-repeat';
	 
	 ?><style type="text/css">
		#headimg {
			width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
			height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
			background-repeat: <?php echo $css_background_repeat; ?>;
		}
	</style>
<?php
}
