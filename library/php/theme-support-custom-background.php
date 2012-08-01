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
function fnbx_custom_background_setup() {
	global $fnbx;

	$bg_args = array( 
		'default-image' => $fnbx->custom_background['background_image'],
		'default-color' => $fnbx->custom_background['css_bg_color'],
		'wp-head-callback' => 'fnbx_custom_background'
	);
	
	add_theme_support( 'custom-background', $bg_args );	
}
add_action( 'fnbx_loaded', 'fnbx_custom_background_setup' );

function fnbx_custom_background() {
	global $fnbx;

	$fnbx->custom_background['background_image'] = get_background_image();
	$fnbx->custom_background['css_bg_color'] = get_background_image();

    $background = $fnbx->custom_background['background_image'];
    $color = $fnbx->custom_background['css_bg_color'];
        
    if ( ! $background && ! $color )
        return;
 
    $css_txt = $color ? "background-color: #$color;" : '';
 
    if ( $background ) {
		$background = apply_filters( 'fnbx_custom_background_css_background_url',  $background );
        $css_txt .= " background-image: url('$background');";
 
        $repeat = get_theme_mod( 'background_repeat', 'repeat' );
        if ( ! in_array( $repeat, array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) ) )
            $repeat = 'repeat';
        $css_txt .= " background-repeat: $repeat;";
 
        $position = get_theme_mod( 'background_position_x', 'left' );
        if ( ! in_array( $position, array( 'center', 'right', 'left' ) ) )
            $position = 'left';
        $css_txt .= " background-position: top $position;";
 
        $attachment = get_theme_mod( 'background_attachment', 'scroll' );
        if ( ! in_array( $attachment, array( 'fixed', 'scroll' ) ) )
            $attachment = 'scroll';
        $css_txt .= " background-attachment: $attachment;";
        
		$css_txt = apply_filters( 'fnbx_custom_background_css_background',  $css_txt );

    }
    
   if ( empty( $css_txt ) ) return;
?>
<style type="text/css">
<?php echo $fnbx->custom_background['css_name']; ?> { <?php echo trim( $css_txt ); ?> }
</style>
<?php
}

