<?php
/**
* Image Template
*
* Template for image attachments.
*
* @package Funbox Base Theme
* @subpackage Template
*/
?>
<?php get_header() ?>

		<!-- START: template_image -->
		<?php do_action( 'fnbx_template_image_start', 'template_image' ) ?>

			<?php
			/* Run The Loop
			 *
			 * If you want to overload this in a child theme then include a file
			 * called funbox-loop-image.php and that will be used instead.
			 * We also put the template part name 'image' into the global
			 * $fnbx->template_part_name so you can use it.
			 */

			 // Filter to catch this loop template part name into gloabal $fnbx
			 global $fnbx;
			 add_filter( 'get_template_part_funbox-loop', array(&$fnbx, 'get_template_part_filter'), 1, 2 );
			 get_template_part( 'funbox-loop', 'image' );
			 
			?>

		<?php do_action( 'fnbx_template_image_end', 'template_image' ) ?>
		<!-- END: template_image -->

<?php get_sidebar() ?>

<?php get_footer() ?>




