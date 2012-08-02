<?php 
/**
* FNBX Theme Custom Header Support
*
* Actions, filters, and functions used by the FNBX Theme to custom header support.
*
* @package FNBX Theme
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
	
	$header_args = array(
		'default-image' => $fnbx->custom_header['header_image'],
		'height' => $fnbx->custom_header['header_image_height'],
		'width' => $fnbx->custom_header['header_image_width'],
		'flex-height' => $fnbx->custom_header['header_image_flex_width'],
		'flex-width' => $fnbx->custom_header['header_image_flex_height'],
		'default-text-color' => $fnbx->custom_header['header_textcolor'],
		'header-text' => $fnbx->custom_header['no_header_text'],
		'random-default' => $fnbx->custom_header['random_default'],
		'wp-head-callback' => 'fnbx_custom_header_style',
		'admin-head-callback' => 'fnbx_custom_header_admin_style',
	);
	 
	add_theme_support( 'custom-header', $header_args );	
	
}
add_action( 'fnbx_loaded', 'fnbx_custom_header_setup' );


// gets included in the site header
function fnbx_custom_header_style() {
   global $fnbx;
      
   if ( !isset( $fnbx->custom_header['css_name'] ) || empty( $fnbx->custom_header['css_name'] ) ) return;
   
   $css_txt = '';
   
   $css_image = get_header_image();
   if ( empty( $css_image ) ) $css_image = $fnbx->custom_header['header_image'];
   
   $h_height = apply_filters( 'fnbx_custom_header_css_background_height',  get_custom_header()->height );
   $h_width = apply_filters( 'fnbx_custom_header_css_background_width',  get_custom_header()->width );
   
   if ( !empty( $h_height ) ) $css_txt .= "\n height: " . $h_height . 'px;';
   if ( !empty( $h_width ) ) $css_txt .= "\n width: " . $h_width . 'px;';
   
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
			width: <?php echo get_custom_header()->width; ?>px;
			height: <?php echo get_custom_header()->height; ?>px;
			background-repeat: <?php echo $css_background_repeat; ?>;
		}
	</style>
<?php
}
